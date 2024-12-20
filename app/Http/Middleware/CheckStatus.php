<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Carbon\Carbon;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = auth()->user();
            if($user->is_suspend == 1 && $user->is_block == 0){
                // if ($user->maintenance_expiration_date == null || $user->maintenance_expiration_date <= Carbon::now()) {
                    return redirect()->route('user.maintenance-fee');
                // }    
            }
            
            if ($user->is_block == 0 && $user->status && $user->ev && $user->sv && $user->tv) {
                return $next($request);
            } else {
                if ($request->is('api/*')) {
                    $notify[] = 'You need to verify your account first.';
                    return response()->json([
                        'remark'  => 'unverified',
                        'status'  => 'error',
                        'message' => ['error' => $notify],
                        'data'    => [
                            'is_ban'          => $user->status,
                            'email_verified'  => $user->ev,
                            'mobile_verified' => $user->sv,
                            'twofa_verified'  => $user->tv,
                        ],
                    ]);
                } else {
                    return to_route('user.authorization');
                }
            }
        }
        abort(403);
    }
}
