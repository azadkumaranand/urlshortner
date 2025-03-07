<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Invitation;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function invitation($token){
        try {
            $token = base64_decode($token);
            $invitation = Invitation::where('token', $token)->first();
            if($invitation){
                $invitation->count = (int)$invitation->count + 1;
                $invitation->save();
                if($invitation->created_at->addMinutes(30) < now()){
                    return "Your token has been expired";
                }else{
                    $user = $invitation->user;
                    Auth::login($user);
                    if(Auth::check()){
                        if($invitation->user->role == 'client_admin'){
                            return redirect()->route('client-admin.dashboard');
                        }
                        return "You are unauthorized to perform this action";
                    }else{
                        return "Invalid email or password";
                    }
                }
            }
            return "Invalid token";
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }

    public function clientMemberInvitation($token){
        try {
            $token = base64_decode($token);
            $invitation = Invitation::where('token', $token)->first();
            if($invitation){
                $invitation->count = (int)$invitation->count + 1;
                $invitation->save();
                if($invitation->created_at->addMinutes(30) < now()){
                    return "Your token has been expired";
                }else{
                    $user = $invitation->user;
                    Auth::login($user);
                    if(Auth::check()){
                        if($invitation->user->role == 'client_members'){
                            return redirect()->route('client-member.dashboard');
                        }
                        return "You are unauthorized to perform this action";
                    }else{
                        return "Invalid email or password";
                    }
                }
            }
            return "Invalid token";
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
