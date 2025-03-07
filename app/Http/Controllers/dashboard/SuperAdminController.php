<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invitation;
use App\Notifications\InvitationEmail;
use App\Models\ShortUrl;
use App\Exports\GenratedShortUrlExport;

class SuperAdminController extends Controller
{
    function index (Request $request){
        $users = User::withCount([
            'usersUnderClient',
            'ShortUrlGeneretedByClientMembers',
        ])->withSum('shortUrlCountByClientMembers', 'count')->where('role', '=', 'client_admin')->orderBy('created_at', 'desc')->paginate(3, ['*'], 'user_page');
        $urlQuery = $createdShortUrls = ShortUrl::with('client')->orderBy('created_at', 'desc');
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
        // return $createdShortUrls;
        return view('dashboards.super-admin.dashboard', ['users'=>$users, 'createdShortUrls'=>$createdShortUrls]);
    }

    public function invitationForm(){
        return view('dashboards.super-admin.invitation-form');
    }

    public function downloadReport(){
        $shortUrl = ShortUrl::all()->map(function($url){
            return [
                'id' => $url->id,
                "long_url"=>$url->original_url,
                'short_url'=>url($url->short_url),
                'hits'=>$url->count,
                'Name'=>$url->client->name,
                'created_at'=>date('d M y', strtotime($url->created_at))
            ];
        });
        $heading = [
            'id',
            'Long Url',
            'Short Url',
            'Hits',
            'Client',
            'Created On'
        ];
        return \Excel::download(new GenratedShortUrlExport($shortUrl, $heading), 'short-url-report.xlsx');
    }

    public function sendInvitationToClient(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('123456789'),
                'role' => 'client_admin',
            ]);
            $password = bin2hex(random_bytes(10));
            Invitation::create([
                'user_id' => $user->id,
                'invited_by' => auth()->user()->email,
                'token' => $password
            ]);
            
            $user->notify(new InvitationEmail($user, $password));
            return redirect()->route("super-admin.dashboard")->with('success', 'Invitation sent successfully');
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
