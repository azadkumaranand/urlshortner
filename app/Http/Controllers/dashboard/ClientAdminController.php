<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShortUrl;
use App\Models\Invitation;
use App\Notifications\ClientMemberInvitation;
use Auth;
use App\Exports\GenratedShortUrlExport;

class ClientAdminController extends Controller
{
    function index (){
        $users = User::withCount([
                            'usersUnderClient',
                            'ShortUrlGeneretedByMember',
                        ])->withSum('shortUrlCountByMember', 'count')
                        ->where('client_id', auth()->user()->id)
                        ->paginate(1);
        $createdShortUrls = ShortUrl::where('client_id', auth()->user()->id)->paginate(1);
        // return $users;
        return view('dashboards.client-admin.dashboard', ['users'=>$users, 'createdShortUrls'=>$createdShortUrls]);
    }

    public function downloadReport(){
        $shortUrl = ShortUrl::all()->map(function($url){
            return [
                'id' => $url->id,
                "long_url"=>$url->original_url,
                'short_url'=>url($url->short_url),
                'hits'=>$url->count,
                'Name'=>$url->user->name,
                'created_at'=>date('d M y', strtotime($url->created_at))
            ];
        });
        return \Excel::download(new GenratedShortUrlExport($shortUrl), 'short-url-report.xlsx');
    }

    public function invitationForm(){
        return view('dashboards.client-admin.invitation-form');
    }

    public function sendInvitationToClient(Request $request){
        $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:client_members,client_admin'
            ],
            [
                'email.unique'=>"This user is already invited."
            ]
            );
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'client_id'=>Auth::user()->id,
                'password' => bcrypt('123456789'),
                'role' => $request->role,
            ]);
            $password = bin2hex(random_bytes(10));
            Invitation::create([
                'user_id' => $user->id,
                'invited_by' => auth()->user()->email,
                'token' => $password
            ]);
            
            $user->notify(new ClientMemberInvitation($user, $password));
            return redirect()->route("client-admin.dashboard")->with('success', 'Invitation sent successfully');
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
