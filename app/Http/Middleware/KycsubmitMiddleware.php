<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KycsubmitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
//for old user
        if (is_null($user->created_at)) {
            if(empty($user->maintenance_expiration_date) || $user->maintenance_expiration_date <=  date("Y-m-d")) {
                $notify[] = ['warning', 'Maintenance fee payment must be paid and verified before submitting KYC !'];
                    return redirect()->back()->withNotify($notify);
            } 
            // elseif ($user->interest_wallet <=  100 && $user->launch_nft_owner == 0) {
            //     $notify[] = ['warning', 'You need to have 100$ in Reward Cube'];
            //         return redirect()->back()->withNotify($notify);
            // }
            // else {
            //     // Not eligible
            //     $notify[] = ['success', 'You are able to submit KYC'];
            //         // return redirect()->back()->withNotify($notify);
            //         return $next($request);
            // }
        }
        
        if (!is_null($user->created_at)) {
            if(empty($user->maintenance_expiration_date) || $user->maintenance_expiration_date <=  date("Y-m-d")) {
                $notify[] = ['warning', 'Maintenance fee payment must be paid and verified before submitting KYC !'];
                    return redirect()->back()->withNotify($notify);
            } 
            // elseif ($user->interest_wallet <= 100 && $user->launch_nft_owner == 0) {
            //     // $notify[] = ['warning', 'You are unable to submit KYC, 100$ in Reward Cube'];
            //     $notify[] = ['warning', 'You need to have 100$ in Reward Cube'];
            //         return redirect()->back()->withNotify($notify);
            // }
            // else {
            //     // Not eligible
            //     $notify[] = ['success', 'You are able to submit KYC'];
            //         return redirect()->back()->withNotify($notify);
            // }
        }
        return $next($request);
    }
}
