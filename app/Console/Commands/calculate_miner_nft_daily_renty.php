<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MinerNft;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use App\Models\GeneralSetting;

class calculate_miner_nft_daily_renty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyMinerNftRentCalculation';

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
