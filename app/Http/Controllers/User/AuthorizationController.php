<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RentNFT;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\UserParentAffiliate;


class AuthorizationController extends Controller
{
    protected function checkCodeValidity($user,$addMin = 2)
    {
        if (!$user->ver_code_send_at){
            return false;
        }
        if ($user->ver_code_send_at->addMinutes($addMin) < Carbon::now()) {
            return false;
        }
        return true;
    }

    public function authorizeForm()
    {
        $user = auth()->user();
        if (!$user->status) {
            $pageTitle = 'Banned';
            $type = 'ban';
        }elseif ($user->is_block == 1) {
            $pageTitle = 'Blocked';
            $type = 'block';
        }elseif(!$user->ev) {
            $type = 'email';
            $pageTitle = 'Verify Email';
            $notifyTemplate = 'EVER_CODE';
        }elseif (!$user->sv) {
            $type = 'sms';
            $pageTitle = 'Verify Mobile Number';
            $notifyTemplate = 'SVER_CODE';
        }elseif (!$user->tv) {
            $pageTitle = '2FA Verification';
            $type = '2fa';
        }else{
            return to_route('user.home');
        }

        if (!$this->checkCodeValidity($user) && ($type != '2fa') && ($type != 'ban') && ($type != 'block')) {
            $user->ver_code = verificationCode(6);
            $user->ver_code_send_at = Carbon::now();
            $user->save();
            notify($user, $notifyTemplate, [
                'code' => $user->ver_code
            ],[$type]);
        }

        return view($this->activeTemplate.'user.auth.authorization.'.$type, compact('user', 'pageTitle'));

    }

    public function sendVerifyCode($type)
    {
        $user = auth()->user();

        if ($this->checkCodeValidity($user)) {
            $targetTime = $user->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $targetTime - time();
            throw ValidationException::withMessages(['resend' => 'Please try after ' . $delay . ' seconds']);
        }

        $user->ver_code = verificationCode(6);
        $user->ver_code_send_at = Carbon::now();
        $user->save();

        if ($type == 'email') {
            $type = 'email';
            $notifyTemplate = 'EVER_CODE';
        } else {
            $type = 'sms';
            $notifyTemplate = 'SVER_CODE';
        }

        notify($user, $notifyTemplate, [
            'code' => $user->ver_code
        ],[$type]);

        $notify[] = ['success', 'Verification code sent successfully'];
        return back()->withNotify($notify);
    }

    public function emailVerification(Request $request)
    {
        $request->validate([
            'code'=>'required'
        ]);

        $user = auth()->user();

        if ($user && $user->ver_code == $request->code && $user->ev == 0) {

            // From Register controller
            
            $general = gs();
            $parentUser = User::find($user->ref_by);

            if(!$parentUser) {
                $user->ev = 1;
                $user->ver_code = null;
                $user->ver_code_send_at = null;
                $user->save();
                return to_route('user.home');
            }


            if($parentUser['pool_2'] == NUll || $parentUser['pool_2'] < 25){
                if($parentUser){
                    notify($parentUser, 'REFERRAL_JOIN', [
                        'ref_username' => $user->username
                    ]);
                }
                $user->ev = 1;
                $user->ver_code = null;
                $user->ver_code_send_at = null;
                $user->save();
        
                return to_route('user.home');
            }


            $rentNFTGet = RentNFT::where('user_id',$user->id)->first();
            if(!$rentNFTGet){
                $date = Carbon::now()->toDateTimeString();
                $nextProfitDate = Carbon::now()->addDays(9)->format('Y-m-d');
                $contractExpiryDate = Carbon::now()->addDays(89)->format('Y-m-d');

                $user_nft = RentNFT::create([
                    'user_meta_mask_info'  => 'referal bonus',
                    'one_nft_price'        => 24,
                    'ft_price'             => GeneralSetting::first()->price_ft,
                    'rented_nft'           => 1,
                    'buying_date'          => $date,
                    'next_profit_date'     => $nextProfitDate,
                    'contract_expiry_date' => $contractExpiryDate,
                    'user_id'              => $user->id,
                ]);
                
                
                $calSponsorOnePercent = ($parentUser['pool_2']-24)/100;
                $initialPool2Balance = $parentUser['pool_2'];
                $affiliatebalance = isset($parentUser['affiliate_reward'])?$parentUser['affiliate_reward']:0;

                
                if($parentUser){
                    $ua = UserParentAffiliate::where('user_id',$user->id)->where('sponsor_id',$parentUser['id'])->first();
                    if(!$ua){
                        UserParentAffiliate::create([
                            'user_id'          => $user->id,
                            'sponsor_id'       => $parentUser['id'],
                            'affiliate_amount' => $calSponsorOnePercent,
                        ]);
                    }
                }


                                
                /* Start calculation of referrals (Temp area we will shift this code where necessary) */   

                //Charge $24 to the sponsor
                User::where('id', $parentUser['id'])->update([
                    //'pool_2' => (($parentUser['pool_2']-25)-$calSponsorOnePercent), // removed 1$ for voucher wallet to deducted
                    'pool_2' => (($parentUser['pool_2']-24)-$calSponsorOnePercent),
                    // 'interest_wallet' => ($parentUser['interest_wallet']+($calSponsorOnePercent+1)),
                    'affiliate_reward' => ($affiliatebalance+$calSponsorOnePercent),
                ]);

                
                $trx = getTrx();

                Transaction::create([
                    'user_id'      => $user->id,
                    'amount'       => GeneralSetting::first()->price_ft,
                    'charge'       => 0,
                    'post_balance' => 0,
                    'trx_type'     => '+',
                    'trx'          => $trx,
                    'remark'       => 'referal bonus',
                    'wallet_type'  => 'reward_cubes',
                    'details'      => 'referal bonus because registered via voucher / referal link',
                ]);
                                


                /* Start Log Transactions */

                //Remove 24$ from sponser pool2

                Transaction::create([
                    'user_id'      => $parentUser['id'],
                    'amount'       => 24,
                    'charge'       => 0,
                    'post_balance' => $initialPool2Balance,
                    'trx_type'     => '-',
                    'trx'          => getTrx(),
                    'remark'       => 'referral',
                    'wallet_type'  => 'pool_2',
                    'details'      => showAmount(24) . ' ' . $general->cur_text . ' referral deducted from Pool2',
                ]);

                Transaction::create([
                    'user_id'      => $parentUser['id'],
                    'amount'       => $calSponsorOnePercent,
                    'charge'       => 0,
                    'post_balance' => ($initialPool2Balance-24),
                    'trx_type'     => '-',
                    'trx'          => getTrx(),
                    'remark'       => 'referral bonus',
                    'wallet_type'  => 'pool_2',
                    'details'      => showAmount($calSponsorOnePercent) . ' ' . $general->cur_text . ' referral bonus transferred from Pool2 to Affiliate',
                ]);

                Transaction::create([
                    'user_id'      => $parentUser['id'],
                    'amount'       => $calSponsorOnePercent,
                    'charge'       => 0,
                    'post_balance' => $affiliatebalance,
                    'trx_type'     => '+',
                    'trx'          => getTrx(),
                    'remark'       => 'referral bonus',
                    'wallet_type'  => 'affiliate_reward',
                    'details'      => showAmount($calSponsorOnePercent) . ' ' . $general->cur_text . ' referral bonus transferred',
                ]);
                
            }
            


            //Added 1$ from sponser pool2 to Pool1   // paid by company 
            /*$transaction               = new Transaction();
            $transaction->user_id      = $parentUser['id'];
            $transaction->amount       = 1;
            $transaction->charge       = 0;
            $transaction->post_balance = $parentUser['interest_wallet'];
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'referral bonus';
            $transaction->wallet_type  = 'interest_wallet';
            $transaction->details      = showAmount(1) . ' ' . $general->cur_text . ' referral bonus transferred ';
            $transaction->save();      
            
            //Added 1% amount of pool2 transferred to Pool1 from sponsor account
            $transaction               = new Transaction();
            $transaction->user_id      = $parentUser['id'];
            $transaction->amount       = 1;
            $transaction->charge       = 0;
            $transaction->post_balance = ($initialPool2Balance-24)/100;
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'referral bonus';
            $transaction->wallet_type  = 'pool_2';
            $transaction->details      = showAmount(($initialPool2Balance-24)/100) . ' ' . $general->cur_text . ' referral bonus transferred from Pool2 to Pool1';
            $transaction->save();  */
            
            /* End Log Transactions */

            //Check if the sponsor has 100 referrals

            // below is commented by hitesh because remove 100 referal to transfered balance cube 1

            // $totalReferrals = User::where('ref_by', $parentUser['id'])
            // ->where('referral_consumed', 'no')
            // ->count();
            // if($totalReferrals>=100){
            //     //Shift balance from Pool2 - Pool1
            //     User::where('id', $parentUser['id'])->update([
            //         'interest_wallet' => $parentUser['interest_wallet']+$parentUser['pool_2'],
            //         'pool_2' => 0
            //     ]);

            //     //update referral status to consumed (referral_consumed)
            //     User::where('ref_by', $parentUser['id'])->update([
            //         'referral_consumed' => 'yes'
            //     ]);
            // }
            /* End calculation of referrals */


            // if with pool balance client can be send nft we can use that part so, now me removed because naty said send family nft if user come via copy link or referal
            
            // Assign 1 NFT Contract
            /* $date = Carbon::now()->toDateTimeString();
            $nextProfitDate = Carbon::now()->addDays(9)->format('Y-m-d');
            $contractExpiryDate = Carbon::now()->addDays(89)->format('Y-m-d');
            $receipt = new RentNFT;
            $receipt->user_meta_mask_info = 'referal bonus';
            $receipt->one_nft_price = 24;
            $receipt->ft_price = GeneralSetting::first()->price_ft;
            $receipt->rented_nft = 1;
            $receipt->buying_date = $date;
            $receipt->next_profit_date = $nextProfitDate;
            $receipt->contract_expiry_date = $contractExpiryDate;
            $receipt->user_id = $user->id;
            $receipt->save();        
            
            if($parentUser){
                notify($parentUser, 'REFERRAL_JOIN', [
                    'ref_username' => $user->username
                ]);
            }*/

            // from register controller

            $user->ev = 1;
            $user->ver_code = null;
            $user->ver_code_send_at = null;
            $user->save();

            if($parentUser){
                notify($parentUser, 'REFERRAL_JOIN', [
                    'ref_username' => $user->username
                ]);
            }

            return to_route('user.home');
        }
        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    public function mobileVerification(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);


        $user = auth()->user();
        if ($user->ver_code == $request->code) {
            $user->sv = 1;
            $user->ver_code = null;
            $user->ver_code_send_at = null;
            $user->save();
            return to_route('user.home');
        }
        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    public function g2faVerification(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $notify[] = ['success','Verification successful'];
        }else{
            $notify[] = ['error','Wrong verification code'];
        }
        return back()->withNotify($notify);
    }
}
