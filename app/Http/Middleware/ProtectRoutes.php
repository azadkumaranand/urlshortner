<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ProtectRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Define allowed routes for each role
        $roleRoutes = [
            'super_admin' => [
                'super-admin.dashboard',
                'super-admin.invitation-form',
                'super-admin.send-invitation',
                'super-admin.download'
            ],
            'client_admin' => [
                'client-admin.dashboard',
                'client-admin.invitation-form',
                'client-admin.send-invitation',
                'client-admin.download',
                'short_url'
            ],
            'client_members' => [
                'client-member.dashboard',
                'url.shortner.form',
                'short_url',
                'client-member.download'
            ],
        ];

        // Get the current route name
        $currentRoute = $request->route()->getName();

        // If user role is defined and their route access is restricted
        if (isset($roleRoutes[$user->role])) {
            if (!in_array($currentRoute, $roleRoutes[$user->role])) {
                return redirect()->route($roleRoutes[$user->role][0]); // Redirect to the default dashboard for that role
            }
        }

        return $next($request);
    }
}
