<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invitation;
use App\Notifications\InvitationEmail;
use App\Models\ShortUrl;

class SuperAdminController extends Controller
{
    function index (){
        $users = User::withCount([
            'usersUnderClient',
            'ShortUrlGeneretedByClientMembers',
        ])->withSum('shortUrlCountByClientMembers', 'count')->where('role', '=', 'client_admin')->paginate(10);
        
        $createdShortUrls = ShortUrl::with('client')->paginate(1);
        // return $createdShortUrls;
        return view('dashboards.super-admin.dashboard', ['users'=>$users, 'createdShortUrls'=>$createdShortUrls]);
    }

    public function invitationForm(){
        return view('dashboards.super-admin.invitation-form');
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
