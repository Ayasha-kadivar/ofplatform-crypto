<?php

namespace App\Console\Commands;

use App\Models\RentNFT;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\Transaction;

class calculate_rent_daily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyRentCalculation:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dd('in');
        $profitContracts = RentNFT::where('next_profit_date', date("Y-m-d"))
        ->where('contract_expiry_date', '>', date("Y-m-d"))
        ->get();

        foreach ($profitContracts as $pContract)
        {
            //Calculate the profict and debit it into system transaction table
            $contractDetails = RentNFT::where('id', $pContract->id)->first();
            
            //Clculate 2$ profit on each NFT rented
            $contractProfit = ($contractDetails['rented_nft']*2);
            
            $updatedPorfit = $contractDetails['total_profit']+$contractProfit;

            //Logic of Profict => After nine days daily profit of 2$ per NFT and it will goes to 90 days 
            //Profit will be divided into all 4 pools

            //Set next 1 day date of contract
            $nextProfitDate = Carbon::now()->addDays(1)->format('Y-m-d');

            RentNFT::where('id', $pContract->id)->update([
                'next_profit_date' => $nextProfitDate,
                'total_profit' => $updatedPorfit,
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
            
            $general            = GeneralSetting::first();

            //**************************** Start Log transactions *************************************

            //Log interest wallet
            $trx = getTrx();

            $transaction               = new Transaction();
            $transaction->user_id      = $contractDetails['user_id'];
            $transaction->amount       = $indPoolProfit;
            $transaction->charge       = 0;
            $transaction->post_balance = $userDetails['interest_wallet'];
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'interest';
            $transaction->wallet_type  = 'interest_wallet';
            $transaction->details      = showAmount($indPoolProfit) . ' ' . $general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id'];
            $transaction->save();

            //Log Pool2
            $trx = getTrx();

            $transaction               = new Transaction();
            $transaction->user_id      = $contractDetails['user_id'];
            $transaction->amount       = $indPoolProfit;
            $transaction->charge       = 0;
            $transaction->post_balance = $userDetails['pool_2'];
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'interest';
            $transaction->wallet_type  = 'Vouchers Cube';
            $transaction->details      = showAmount($indPoolProfit) . ' ' . $general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id'];
            $transaction->save();        
            
            //Log Pool3
            $trx = getTrx();

            $transaction               = new Transaction();
            $transaction->user_id      = $contractDetails['user_id'];
            $transaction->amount       = $indPoolProfit;
            $transaction->charge       = 0;
            $transaction->post_balance = $userDetails['pool_3'];
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'interest';
            $transaction->wallet_type  = 'Staking Cube';
            $transaction->details      = showAmount($indPoolProfit) . ' ' . $general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id'];
            $transaction->save();  
            
            //Log Pool4
            $trx = getTrx();

            $transaction               = new Transaction();
            $transaction->user_id      = $contractDetails['user_id'];
            $transaction->amount       = $indPoolProfit;
            $transaction->charge       = 0;
            $transaction->post_balance = $userDetails['pool_4'];
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'interest';
            $transaction->wallet_type  = 'NFTs Cube';
            $transaction->details      = showAmount($indPoolProfit) . ' ' . $general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id'];
            $transaction->save();                
            
            //************************ End Log Transactions *********************
        }
    }
}
