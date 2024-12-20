<?php

namespace App\Http\Controllers;

use App\Lib\HyipLab;
use App\Models\GeneralSetting;
use App\Models\Invest;
use App\Models\User;
use App\Models\RentNFT;
use App\Models\RenewNFT;
use App\Models\Transaction;
use App\Models\UserParentAffiliate;
use Carbon\Carbon;

class CronController extends Controller
{
    public function cron()
    {

        $now           = Carbon::now();
        $before_30days = Carbon::now()->subDays(30)->format('Y-m-d');
        $after_30days  = Carbon::now()->addDays(30)->format('Y-m-d');
        $general       = GeneralSetting::first();
        //dd('h');



        if(date('G') == 1){
            \DB::statement("UPDATE `users` SET `status`=1 , ban_reason = NULL , ban_type = NULL , till_ban_date = NULL WHERE status = 0 and ban_type='temporary' and till_ban_date < CURDATE()");
        }

        // check affliate amount paid or not
        if(date('G') > 8){
            $affliate = \DB::select("SELECT user_sponsor_affiliate_with_fees.sponsor_id,user_sponsor_affiliate_with_fees.user_id,user_sponsor_affiliate_with_fees.affiliate_amount,date(users.created_at) as cdate,users.* FROM `users` INNER JOIN user_sponsor_affiliate_with_fees ON users.id = user_sponsor_affiliate_with_fees.user_id WHERE DATE(users.created_at) > CURDATE() - INTERVAL 65 DAY and user_sponsor_affiliate_with_fees.user_id > 0 and users.created_at IS NOT NULL and users.created_at !='0000-00-00 00:00:00' and user_sponsor_affiliate_with_fees.is_forwarded = 0 limit 400");

            $lastday_affiliate = Carbon::now()->subDays(61)->format('Y-m-d');

            if($affliate) {
                foreach($affliate as $ak=>$av){
                    $user_sp = User::find($av->sponsor_id);
                    $before_update_ar = $user_sp->affiliate_reward;
                    $before_update_pool1 = $user_sp->interest_wallet;
                    if($lastday_affiliate >= $av->cdate && ($av->maintenance_expiration_date == NULL || empty($av->maintenance_expiration_date))){
                        if($av->affiliate_amount > 0 && $av->affiliate_amount <= $user_sp->affiliate_reward){

                            $user_sp->affiliate_reward -= $av->affiliate_amount;
                            $user_sp->save();

                            Transaction::create([
                                'user_id'      => $av->sponsor_id,
                                'amount'       => $av->affiliate_amount,
                                'charge'       => 0,
                                'post_balance' => $before_update_ar,
                                'trx_type'     => '-',
                                'trx'          => getTrx(),
                                'remark'       => 'referral bonus',
                                'wallet_type'  => 'affiliate_reward',
                                'details'      => showAmount($av->affiliate_amount) . ' ' . $general->cur_text . ' referral bonus removed from affiliate (not paid fees) referance user conatct#'.$av->user_id
                            ]);

                            
                            UserParentAffiliate::where('user_id', $av->user_id)->where('sponsor_id', $av->sponsor_id)->update(['is_forwarded' => 1]);
                        }
                    }else{
                        if($av->maintenance_expiration_date && $av->affiliate_amount > 0 && $av->affiliate_amount <= $user_sp->affiliate_reward){
                            $user_sp->affiliate_reward -= $av->affiliate_amount;
                            $user_sp->interest_wallet += $av->affiliate_amount;
                            $user_sp->save();


                            Transaction::create([
                                'user_id'      => $av->sponsor_id,
                                'amount'       => $av->affiliate_amount,
                                'charge'       => 0,
                                'post_balance' => $before_update_ar,
                                'trx_type'     => '-',
                                'trx'          => getTrx(),
                                'remark'       => 'referral bonus',
                                'wallet_type'  => 'affiliate_reward',
                                'details'      => showAmount($av->affiliate_amount) . ' ' . $general->cur_text . ' referral bonus removed from affiliate referance user conatct#'.$av->user_id
                            ]);
                            

                            Transaction::create([
                                'user_id'      => $av->sponsor_id,
                                'amount'       => $av->affiliate_amount,
                                'charge'       => 0,
                                'post_balance' => $before_update_pool1,
                                'trx_type'     => '+',
                                'trx'          => getTrx(),
                                'remark'       => 'referral bonus',
                                'wallet_type'  => 'interest_wallet',
                                'details'      => showAmount($av->affiliate_amount) . ' ' . $general->cur_text . ' referral bonus transferred from Affiliate to Pool1 referance user conatct#'.$av->user_id
                            ]);

                            UserParentAffiliate::where('user_id', $av->user_id)->where('sponsor_id', $av->sponsor_id)->update(['is_forwarded' => 1]);
                        }
                    }
                }
            }
        }

        // check affliate amount paid or not
        
        $which_month_cube2_zero = $general->which_month_done;
        if($which_month_cube2_zero != date('m')){
            \DB::statement("UPDATE `general_settings` SET `which_month_done`= ".date('m'));
            \DB::statement("UPDATE `users` SET `pool_2`=96 WHERE pool_2 > 0");
        }

        if(date('Y-m-d') < '2025-01-15'){
            // user maintenance expired
            
           // User::whereNotNull('maintenance_expiration_date')->whereDate('maintenance_expiration_date', '>', $before_30days)->update(['is_block' => 0]);
            
            $updateData = [
                'maintenance_fee' => NULL,
                'fee_status' => 0,
                'is_suspend' => 1,
                //'maintenance_expiration_date' => null,
            ];
            User::where('fee_status', '!=', 1)->where(function ($query) use($now) {
                $query->where('maintenance_expiration_date', '<=', $now)
                    ->orWhereNull('maintenance_expiration_date');
            })->where('created_at', '<', Carbon::now()->subDays(30)->toDateTimeString())->update($updateData);

            User::where('fee_status', 1)->where(function ($query) use($now) {
                $query->where('maintenance_expiration_date', '<=', $now)
                    ->orWhereNull('maintenance_expiration_date');
            })->where('created_at', '<', Carbon::now()->subDays(30)->toDateTimeString())->update(['is_suspend' => 1]);

            User::where(function ($query) use($now) {
                $query->where('maintenance_expiration_date', '>', $now)
                    ->where('created_at', '>', Carbon::now()->subDays(30)->toDateTimeString());
            })->orWhere('maintenance_expiration_date', '>', $now)->update(['is_suspend' => 0]);

            // User::where(function ($query) use($now) {
            //     $query->where('maintenance_expiration_date', '<=', $now)
            //         ->orWhereNull('maintenance_expiration_date');
            // })->where('created_at', '<', Carbon::now()->subDays(61)->toDateTimeString())->update(['is_block' => 1]);

            User::whereNull('maintenance_expiration_date')->where('created_at', '<', Carbon::now()->subDays(61)->toDateTimeString())->update(['is_block' => 1]);

            User::whereNotNull('maintenance_expiration_date')->whereDate('maintenance_expiration_date', '<', $before_30days)->update(['is_block' => 1]);
            
            // end user maintenance expired
        }

        if(date('Y-m-d') == '2024-03-16'){

            if(date('G') == 0){
                \DB::statement("DELETE from rent_nft where contract_expiry_date < CURRENT_DATE");
            }

        }

        if(date('Y-m-d') > '2024-03-16'){

            if(date('G') == 0){
                //\DB::statement("DELETE from rent_nft where contract_expiry_date <= CURRENT_DATE and (contract_expiry_date + INTERVAL 3 DAY) <= CURRENT_DATE");
                \DB::statement("DELETE rent_nft from rent_nft INNER JOIN users ON rent_nft.user_id = users.id WHERE users.is_suspend = 0 and users.is_block = 0 and rent_nft.contract_expiry_date <= CURRENT_DATE and (rent_nft.contract_expiry_date + INTERVAL 3 DAY) <= CURRENT_DATE and contract_expiry_date <= next_profit_date ");
            }
        }

        // $file = 'a.txt';
        // // Open the file to get existing content
        // $current = file_get_contents($file);
        // $current .=  \Request::ip()."\n";
        // // Write the contents back to the file
        // file_put_contents('a.txt', $current);

        $ft_rate = ($general->price_ft)?$general->price_ft:1;

        
        //check expiry or not for VIP
        \DB::statement("update users set vip_user = 0 where vip_user_date < CURRENT_DATE and vip_user = 1 and vip_user_date IS NOT NULL");
        //check expiry or not for VIP

        // auto renewal  12$ familynfts

        //$autoRenewalList = DB::select("SELECT count(rent_nft.user_id) FROM `rent_nft` LEFT JOIN users ON  users.id = rent_nft.user_id  WHERE rent_nft.`contract_expiry_date` < CURRENT_DATE and users.vip_user =1");
        $autoRenewalList = RentNFT::whereNotNull('contract_expiry_date')->where('contract_expiry_date','<=', date("Y-m-d"))->where('auto_renewal','1')->take(500)->orderBy('id','desc')->get();


        if($autoRenewalList){
            $paymentMethod = 'interest_wallet';
            foreach ($autoRenewalList as $ki => $vitem) {
             
                
                $nft_amount = ($vitem->rented_nft * 12);  // 12$ for renewal
                $nft_amount = ($nft_amount / $ft_rate);
                $user = User::find($vitem->user_id);

                $rent_id = $vitem->id;

                if($nft_amount <= $user->interest_wallet && $user->vip_user == 1 && $vitem->auto_renewal == 1 && $user->is_suspend == 0 && $user->is_block == 0){

                    $parentUser = [];
                    $reward_amt = $vitem->rented_nft;
                    if(isset($user->ref_by) && !empty($user->ref_by)){
                        $parentUser = User::find($user->ref_by);
                    }

                    // $plus90days = date('Y-m-d', strtotime('+89 days'));
                    // $currentdays = date('Y-m-d');

                    // if($vitem->contract_expiry_date < date('Y-m-d')){
                    //     $plus90days = date('Y-m-d', strtotime('+89 days'));
                    //     $currentdays = date('Y-m-d');
                    //     $vitem->next_profit_date = $currentdays;
                    // }else{
                        $date_c = Carbon::createFromFormat('Y-m-d', $vitem->contract_expiry_date);
                        $plus90days = $date_c->addDays(90);
                        $currentdays = date('Y-m-d');
                    // }

                    $walletBalance = $user->interest_wallet;
                    $user->interest_wallet= ($user->interest_wallet-$nft_amount);
                    $user->save();
                    $vitem->contract_expiry_date = $plus90days;
                    $vitem->last_renew_date = $currentdays;
                    $vitem->expired_mail = NULL;
                    $vitem->update();

                    
                    RenewNFT::create([
                        'user_id'     => $user->id,
                        'amount'      => $nft_amount,
                        'wallet_type' => $paymentMethod,
                        'rent_id'     => $rent_id,
                        'renew_date'  => $currentdays
                    ]);

                    $trx = getTrx();
                    Transaction::create([
                        'user_id'      => $user->id,
                        'amount'       => $nft_amount,
                        'charge'       => 0,
                        'post_balance' => $walletBalance,
                        'trx_type'     => '-',
                        'trx'          => $trx,
                        'remark'       => 'FamilyNFT',
                        'wallet_type'  => $paymentMethod,
                        'details'      => showAmount($nft_amount) . ' ' . $general->cur_text . ' deducted from '.$paymentMethod.' for Renewal FamilyNFT.'
                    ]);
                    
                    if($parentUser && !empty($parentUser)){

                        //REWARD $1 to the sponsor
                        User::where('id', $parentUser['id'])->update([
                            'interest_wallet' => ($parentUser['interest_wallet']+($reward_amt)),
                        ]);

                        //Added 1$ to sponser  // paid by company 
                        
                        $trx2 = getTrx();

                        Transaction::create([
                            'user_id'      => $parentUser['id'],
                            'amount'       => $reward_amt,
                            'charge'       => 0,
                            'post_balance' => ($parentUser['interest_wallet']),
                            'trx_type'     => '+',
                            'trx'          => $trx2,
                            'remark'       => 'referral renewal bonus',
                            'wallet_type'  => 'interest_wallet',
                            'details'      => showAmount($reward_amt) . ' ' . $general->cur_text . ' referral renewal bonus transferred Contract#' . $vitem->id
                        ]);
                    }
                }
                

            }
        }

        // auto renewal

        // cron for rent nft ==============================

            \Log::info('oin daily rent');
            // $profitContracts = RentNFT::where('next_profit_date', date("Y-m-d"))
            // ->where('contract_expiry_date', '>', date("Y-m-d"))->take(1000)
            // ->get();
            // $profitContracts = RentNFT::whereNotNull('next_profit_date')->where('next_profit_date','<=', date("Y-m-d"))->whereRaw('contract_expiry_date > next_profit_date')->with(['user' => function($q){$q->where('is_block', 0)->where('is_suspend', 0);}])->take(3000)->get();

            $profitContracts = RentNFT::whereNotNull('next_profit_date')->where('next_profit_date','<=', date("Y-m-d"))->whereRaw('contract_expiry_date > next_profit_date')->whereHas('user', function($q){$q->where('is_block', 0)->where('is_suspend', 0);})->with('user')->take(3000)->get();

            
            //dd($profitContracts);
            //\Log::info($profitContracts);
    
            if($profitContracts){
                $general            = GeneralSetting::first();
                foreach ($profitContracts as $pContract)
                {
                    //Calculate the profict and debit it into system transaction table
                    $contractDetails = $pContract;//RentNFT::where('id', $pContract->id)->with('user')->first();
                    // dd($contractDetails);
                    if(isset($contractDetails->user) && $contractDetails->user->is_suspend == 0 && $contractDetails->user->is_block == 0){
                        //Clculate 2$ profit on each NFT rented
                        $contractProfit = ($contractDetails['rented_nft']*2);
                                            
                        $updatedPorfit = $contractDetails['total_profit']+$contractProfit;

                        //Profit will be divided into all 4 pools

                        //Set next 1 day date of contract
                        //$nextProfitDate = Carbon::now()->addDays(1)->format('Y-m-d');
                        $nextProfitDate = Carbon::parse($pContract->next_profit_date)->addDay()->format('Y-m-d');

                        RentNFT::where('id', $pContract->id)->update([
                            'next_profit_date' => $nextProfitDate,
                            'total_profit' => $updatedPorfit
                        ]);

                        //Divide profit in 4 pools
                        $indPoolProfit = ($contractProfit/4);
                        $userDetails = User::where('id', $contractDetails['user_id'])->first();

                        User::where('id', $contractDetails['user_id'])->update([
                            'interest_wallet' => $userDetails['interest_wallet']+$indPoolProfit,
                            'pool_2' => $userDetails['pool_2']+$indPoolProfit,
                            'pool_3' => $userDetails['pool_3']+$indPoolProfit,
                            'pool_4' => $userDetails['pool_4']+$indPoolProfit,
                        ]);



                        //**************************** Start Log transactions *************************************

                        //Log interest wallet
                        
                        $iv = $userDetails['interest_wallet']?$userDetails['interest_wallet']:0;
                        Transaction::create([
                            'user_id'      => $contractDetails['user_id'],
                            'amount'       => $indPoolProfit,
                            'charge'       => 0,
                            'post_balance' => $iv,
                            'trx_type'     => '+',
                            'trx'          => getTrx(),
                            'remark'       => 'interest',
                            'wallet_type'  => 'interest_wallet',
                            'details'      => showAmount($indPoolProfit) . ' ' .$general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id']
                        ]);


                        //Log Pool2

                        $p2 = $userDetails['pool_2']?$userDetails['pool_2']:0;
                        Transaction::create([
                            'user_id'      => $contractDetails['user_id'],
                            'amount'       => $indPoolProfit,
                            'charge'       => 0,
                            'post_balance' => $p2,
                            'trx_type'     => '+',
                            'trx'          => getTrx(),
                            'remark'       => 'interest',
                            'wallet_type'  => 'Vouchers Cube',
                            'details'      => showAmount($indPoolProfit) . ' ' . $general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id']
                        ]);

                             

                        //Log Pool3

                        $p3 = $userDetails['pool_3']?$userDetails['pool_3']:0;
                        Transaction::create([
                            'user_id'      => $contractDetails['user_id'],
                            'amount'       => $indPoolProfit,
                            'charge'       => 0,
                            'post_balance' => $p3,
                            'trx_type'     => '+',
                            'trx'          => getTrx(),
                            'remark'       => 'interest',
                            'wallet_type'  => 'Staking Cube',
                            'details'      => showAmount($indPoolProfit) . ' ' . $general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id']
                        ]);

                       
                        //Log Pool4

                        $p4 = $userDetails['pool_4']?$userDetails['pool_4']:0;
                        Transaction::create([
                            'user_id'      => $contractDetails['user_id'],
                            'amount'       => $indPoolProfit,
                            'charge'       => 0,
                            'post_balance' => $p4,
                            'trx_type'     => '+',
                            'trx'          => getTrx(),
                            'remark'       => 'interest',
                            'wallet_type'  => 'NFTs Cube',
                            'details'      => showAmount($indPoolProfit) . ' ' . $general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id']
                        ]);
                        
                        //************************ End Log Transactions *********************
                    }
                }
            }
                

        //end  cron for rent nft ==============================

        

        
        //ALTER TABLE `rent_nft` ADD `expired_mail` DATE NULL DEFAULT NULL AFTER `auto_renewal`;

        //INSERT INTO `notification_templates` (`id`, `act`, `name`, `subj`, `email_body`, `sms_body`, `shortcodes`, `email_status`, `sms_status`, `firebase_status`, `firebase_body`, `created_at`, `updated_at`) VALUES (NULL, 'RENTNFT_EXPIRED', 'RENTNFT - expired', 'Your FamilyNFT is expired', '<div style=\"font-family: Montserrat, sans-serif;\">Your FamilyNFT is expired.</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\">FamilyNFT : <b><u>{{rented_nft}}</u></b></font><br><font size=\"4\">BuyingDate : <b><u>{{buying_date}}</u></b></font></div>', '-', '{\"rented_nft\":\"rented nft \",\"buying_date\":\"buying date\"}', '1', '0', '0', '-', '2024-04-18 17:30:00', '2024-04-18 17:30:00')


        if(date('G') >= 5){
            $expiredContracts = RentNFT::with('user')->whereNull('expired_mail')->where('contract_expiry_date','<=', date("Y-m-d"))->orderBy('id', 'DESC')->take(35)->get();
    
            if($expiredContracts){
                foreach($expiredContracts as $ke => $ve){
                    if(isset($ve->user)){
                        $u = $ve->user;
                        notify($u, 'RENTNFT_EXPIRED', [
                            'rented_nft'   => $ve->rented_nft,
                            'expired_date' => date('d.m.Y', strtotime($ve->contract_expiry_date)),
                            'buying_date'  => date('d.m.Y', strtotime($ve->buying_date))
                        ]);
                        $ve->expired_mail = date("Y-m-d");
                        $ve->save();
                    }
                }
            }
    
        }

        $day    = strtolower(date('D'));
        $offDay = (array) $general->off_day;
        if (array_key_exists($day, $offDay)) {
            echo "Holiday";
            exit;
        }

        $invests = Invest::where('status', 1)->where('next_time', '<=', $now)->orderBy('last_time')->take(100)->get();
        foreach ($invests as $invest) {
            $now  = $now;
            $next = HyipLab::nextWorkingDay($invest->plan->time);
            $user = $invest->user;

            $invest->return_rec_time += 1;
            $invest->paid += $invest->interest;
            $invest->should_pay -= $invest->period > 0 ? $invest->interest : 0;
            $invest->next_time = $next;
            $invest->last_time = $now;

            // Add Return Amount to user's Interest Balance
            $user->interest_wallet += $invest->interest;
            $user->save();

            $trx = getTrx();

            // Create The Transaction for Interest Back
            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $invest->interest;
            $transaction->charge       = 0;
            $transaction->post_balance = $user->interest_wallet;
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'interest';
            $transaction->wallet_type  = 'interest_wallet';
            $transaction->details      = showAmount($invest->interest) . ' ' . $general->cur_text . ' interest from ' . @$invest->plan->name;
            $transaction->save();

            // Give Referral Commission if Enabled
            if ($general->invest_commission == 1) {
                $commissionType = 'invest_return_commission';
                HyipLab::levelCommission($user, $invest->interest, $commissionType, $trx, $general);
            }

            // Complete the investment if user get full amount as plan
            if ($invest->return_rec_time >= $invest->period && $invest->period != -1) {
                $invest->status = 0; // Change Status so he do not get any more return

                // Give the capital back if plan says the same
                if ($invest->capital_status == 1) {
                    $capital = $invest->amount;
                    $user->interest_wallet += $capital;
                    $user->save();

                    $transaction               = new Transaction();
                    $transaction->user_id      = $user->id;
                    $transaction->amount       = $capital;
                    $transaction->charge       = 0;
                    $transaction->post_balance = $user->interest_wallet;
                    $transaction->trx_type     = '+';
                    $transaction->trx          = $trx;
                    $transaction->wallet_type  = 'interest_wallet';
                    $transaction->remark       = 'capital_return';
                    $transaction->details      = showAmount($capital) . ' ' . $general->cur_text . ' capital back from ' . @$invest->plan->name;
                    $transaction->save();
                }
            }

            $invest->save();

            notify($user, 'INTEREST', [
                'trx'          => $invest->trx,
                'amount'       => showAmount($invest->interest),
                'plan_name'    => @$invest->plan->name,
                'post_balance' => showAmount($user->interest_wallet),
            ]);
        }


        $general->last_cron = Carbon::now();
        $general->save();
    }
}
