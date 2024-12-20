<?php

namespace App\Http\Controllers\Nft;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MinerNft;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\GeneralSetting;

class NftmineController extends Controller
{
    public function purchase(Request $request){
        $request->validate([
            'mineOption' => 'required',
            'amount' => ['required', 'integer', 'min:0'],
        ], [
            'mineOption.required' => 'Select quantity is required.',
            'amount.required' => 'Quantity amount is required.',
            'amount.integer' => 'Quantity amount only number without decimal.',
            'amount.min' => 'Quantity amount must be a positive number.',
        ]);

        $general            = GeneralSetting::first();

        $ft_rate = ($general->price_ft)?$general->price_ft:1;
        $user = Auth::user();
        $date = Carbon::now()->toDateTimeString();
        $nextProfitDate = Carbon::now()->addDays(1)->format('Y-m-d');
        $contractExpiryDate = Carbon::now()->addYears(5)->format('Y-m-d');
        $receipt = new MinerNft;
        if($request->mineOption=='whole'){
            if($request->minePaymentMethod=='deposit'){
                $ra = ($request->amount*2000);
                if ($user->deposit_ft < ($ra / $ft_rate)) {
                    $notify[] = ['error', 'You do not have sufficient balance to buy using deposit wallet.'];
                    return back()->withNotify($notify);
                }
                $paymentMethod = 'Deposit Wallet';
            }else if($request->minePaymentMethod=='RewardsCube'){
                if ($user->interest_wallet<($request->amount*2000)) {
                    $notify[] = ['error', 'You do not have sufficient balance to buy using Rewards Cube.'];
                    return back()->withNotify($notify);
                }
                $paymentMethod = 'Rewards Cube';
            }else if($request->minePaymentMethod=='NftsCube'){
                if ($user->pool_4<($request->amount*2000)) {
                    $notify[] = ['error', 'You do not have sufficient balance to buy using NFTs Cube.'];
                    return back()->withNotify($notify);
                }
                $paymentMethod = 'NFTs Cube';
            }
            $receipt->next_profit_date = $nextProfitDate;
            $receipt->contract_expiry_date = $contractExpiryDate;
            $receipt->partial_consume_status = "yes";
            $totalAmount = $request->amount*2000;
            $receipt->partial_total_amount = 0;
        }else{
            if($request->minePaymentMethod=='deposit'){
                $ra = ($request->amount*20);
                if ($user->deposit_ft < ($ra / $ft_rate)) {
                    $notify[] = ['error', 'You do not have sufficient balance to buy using deposit wallet.'];
                    return back()->withNotify($notify);
                }
                $paymentMethod = 'Deposit Wallet';
            }else if($request->minePaymentMethod=='RewardsCube'){
                if ($user->interest_wallet<($request->amount*20)) {
                    $notify[] = ['error', 'You do not have sufficient balance to buy using Rewards Cube.'];
                    return back()->withNotify($notify);
                }
                $paymentMethod = 'Rewards Cube';
            }else if($request->minePaymentMethod=='NftsCube'){
                if ($user->pool_4<($request->amount*20)) {
                    $notify[] = ['error', 'You do not have sufficient balance to buy using NFTs Cube.'];
                    return back()->withNotify($notify);
                }
                $paymentMethod = 'NFTs Cube';
            }
            $receipt->partial_consume_status = "no";
            $totalAmount = $request->amount*20;

            //Calculate previously added partial ft's price
            $partialNft = MinerNft::where('user_id', auth()->id())
            ->where('partial_consume_status', 'no')
            ->where('mine_quantity_type', 'partial')
            ->latest()
            ->first();

            if(!isset($partialNft)){
                $receipt->partial_total_amount = $totalAmount;
            }else{
                //Calculate if partial amount is not reached to 2000
                if(($partialNft->partial_total_amount+$totalAmount)>=2000){
                    $receipt->next_profit_date = $nextProfitDate;
                    $receipt->contract_expiry_date = $contractExpiryDate;
                    $receipt->partial_total_amount = $totalAmount+$partialNft->partial_total_amount;
                    $receipt->partial_consume_status = 'yes';

                    //Update all previous partial contracts to consume
                    MinerNft::where('user_id', auth()->id())->update([
                        'partial_consume_status' => 'yes'
                    ]);
                }else{
                    $receipt->partial_total_amount = $totalAmount+$partialNft->partial_total_amount;
                }
            }


        }

        $receipt->user_meta_mask_info = 'by '.$paymentMethod;
        $receipt->one_nft_price = 2000;
        $receipt->partial_nft_price = 20;
        $receipt->ft_price = 1;
        $receipt->mine_nft = $request->amount;
        $receipt->mine_quantity_type = $request->mineOption;
        $receipt->buying_date = $date;
        $receipt->user_id = $user->id;
        $receipt->payment_method = $paymentMethod;
        $receipt->save();


        //Deduct from user wallet (based on selected wallet) and log transaction
        if($request->minePaymentMethod=='deposit'){
            $walletBalance = $user->deposit_ft;
            User::where('id', $user->id)->update([
                'deposit_ft' => ($user->deposit_ft-($totalAmount / $ft_rate)),
            ]);
        }else if($request->minePaymentMethod=='RewardsCube'){
            $walletBalance = $user->interest_wallet;
            User::where('id', $user->id)->update([
                'interest_wallet' => $user->interest_wallet-$totalAmount,
            ]);
        }else if($request->minePaymentMethod=='NftsCube'){
            $walletBalance = $user->pool_4;
            User::where('id', $user->id)->update([
                'pool_4' => $user->pool_4-$totalAmount,
            ]);
        }



        //Log deposit wallet
        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $totalAmount;
        $transaction->charge       = 0;
        $transaction->post_balance = $walletBalance;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx;
        $transaction->remark       = 'MinerNFT';
        $transaction->wallet_type  = $paymentMethod;
        $transaction->details      = showAmount($totalAmount) . ' ' . $general->cur_text . ' deducted from '.$paymentMethod.' for MinetNFT.';
        $transaction->save();

        $notify[] = ['success', 'NFT mined from '.$paymentMethod];
        return redirect()->back()->withNotify($notify);

    }

    public function aqeel(){
        $profitContracts = MinerNft::where('next_profit_date', date("Y-m-d"))
        ->where('contract_expiry_date', '>', date("Y-m-d"))
        ->get();

        foreach ($profitContracts as $pContract)
        {
            $totalProfit=0;

            //Calculate the profict and debit it into system transaction table
            $contractDetails = MinerNft::where('id', $pContract->id)->first();

            //Set next 1 day date of contract
            $nextProfitDate = Carbon::now()->addDays(1)->format('Y-m-d');
            if($contractDetails['mine_quantity_type']=='whole'){
                $totalProfit = (10*$contractDetails['mine_nft']);
            }else{
                $totalProfit = 10;
            }

            MinerNft::where('id', $pContract->id)->update([
                'next_profit_date' => $nextProfitDate,
                'total_profit' => $pContract->total_profit+$totalProfit
            ]);

            $userDetails = User::where('id', $contractDetails['user_id'])->first();
            $postBalance = $userDetails['interest_wallet'];
            User::where('id', $contractDetails['user_id'])->update([
                'interest_wallet' => $userDetails['interest_wallet']+$totalProfit
            ]);

            $general            = GeneralSetting::first();

            //**************************** Start Log transactions *************************************

            //Log interest wallet
            $trx = getTrx();

            $transaction               = new Transaction();
            $transaction->user_id      = $contractDetails['user_id'];
            $transaction->amount       = $totalProfit;
            $transaction->charge       = 0;
            $transaction->post_balance = $postBalance;
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'Pool 4';
            $transaction->wallet_type  = 'rent_generated';
            $transaction->details      = showAmount($totalProfit) . ' ' . $general->cur_text . ' interest from MineNFT';
            $transaction->save();

        }
    }
}
