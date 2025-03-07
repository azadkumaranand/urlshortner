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
    function index (Request $request){
        $users = User::withCount([
                            'usersUnderClient',
                            'ShortUrlGeneretedByMember',
                        ])->withSum('shortUrlCountByMember', 'count')
                        ->where('client_id', auth()->user()->id)
                        ->paginate(1, ['*'], 'user_page');
        
        $urlQuery = $createdShortUrls = ShortUrl::where('client_id', auth()->user()->id)->orderBy('created_at', 'desc');
        if($request->query('q') && !is_null($request->query('q'))){
            if($request->query('q') == 'tm'){
                $urlQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            }elseif($request->query('q') == 'lm'){
                $urlQuery->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
            }elseif($request->query('q') == 'lw'){
                $urlQuery->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
            }elseif($request->query('q') == 'today'){
                $urlQuery->whereDate('created_at', now()->today());
            }
        }
        $createdShortUrls = $urlQuery->paginate(3, ['*'], 'url_page');
        // return $users;
        return view('dashboards.client-admin.dashboard', ['users'=>$users, 'createdShortUrls'=>$createdShortUrls]);
    }

    public function downloadReport(){
        $shortUrl = ShortUrl::where('client_id', auth()->user()->id)->get()->map(function($url){
            return [
                'id' => $url->id,
                "long_url"=>$url->original_url,
                'short_url'=>url($url->short_url),
                'hits'=>$url->count,
                'Name'=>$url->user->name,
                'created_at'=>date('d M y', strtotime($url->created_at))
            ];
        });
        $heading = [
            'id',
            'Long Url',
            'Short Url',
            'Hits',
            'Name',
            'Created On'
        ];
        return \Excel::download(new GenratedShortUrlExport($shortUrl, $heading), 'short-url-report.xlsx');
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
