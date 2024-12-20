<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WithdrawalsRequestCubeOneToWallet;
use App\Models\User;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class CubeOneToWalletWithdrawalController extends Controller
{
    public function pending() {
        $pageTitle   = 'Pending Cube One to Wallet Withdrawals';
        $cubeonetowithdraws = $this->withdrawalData('pending');
        return view('admin.cubeonetowithdraw.cubeonetowithdraws', compact('pageTitle', 'cubeonetowithdraws'));
    }

    public function approved() {
        $pageTitle   = 'Approved Cube One to Wallet Withdrawals';
        $cubeonetowithdraws = $this->withdrawalData('approved');
        return view('admin.cubeonetowithdraw.cubeonetowithdraws', compact('pageTitle', 'cubeonetowithdraws'));
    }

    public function rejected() {
        $pageTitle   = 'Rejected Cube One to Wallet Withdrawals';
        $cubeonetowithdraws = $this->withdrawalData('rejected');
        return view('admin.cubeonetowithdraw.cubeonetowithdraws', compact('pageTitle', 'cubeonetowithdraws'));
    }

    public function log() {
        $pageTitle      = 'Withdrawals Log';
        $withdrawalData = $this->withdrawalData($scope = null, $summery = true);
        $withdrawals    = $withdrawalData['data'];
        $summery        = $withdrawalData['summery'];
        $successful     = $summery['successful'];
        $pending        = $summery['pending'];
        $rejected       = $summery['rejected'];

        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals', 'successful', 'pending', 'rejected'));
    }

    protected function withdrawalData($scope = null, $summery = false) { 
        if ($scope) {
            $withdrawals = WithdrawalsRequestCubeOneToWallet::$scope();
        } else {
            $withdrawals = WithdrawalsRequestCubeOneToWallet::where('status', '!=', 0);
        }
        $withdrawals = $withdrawals->searchable(['trx','user:username'])->dateFilter();

        $request = request();
        
        if (!$summery) {
            return $withdrawals->with(['user', 'method'])->whereHas('user', function ($query) {
                        $query->where('users.id','>',0);
                })->orderBy('id', 'desc')->paginate(getPaginate());
        } else {
            $successful = clone $withdrawals;
            $pending    = clone $withdrawals;
            $rejected   = clone $withdrawals;

            $successfulSummery = $successful->where('status', 2)->sum('amount');
            $pendingSummery    = $pending->where('status', 1)->sum('amount');
            $rejectedSummery   = $rejected->where('status', 3)->sum('amount');

            return [
                'data'    => $withdrawals->with(['user', 'method'])->whereHas('user', function ($query) {
                    $query->where('users.id','>',0);
                })->orderBy('id', 'desc')->paginate(getPaginate()),
                'summery' => [
                    'successful' => $successfulSummery,
                    'pending'    => $pendingSummery,
                    'rejected'   => $rejectedSummery,
                ],
            ];
        }
    }

    public function details($id) {
        $general    = gs();
        $cubeonetowithdraw = WithdrawalsRequestCubeOneToWallet::where('id', $id)->where('status', '!=', 0)->with(['user', 'method'])->firstOrFail();
        $pageTitle  = (isset($cubeonetowithdraw->user)?$cubeonetowithdraw->user->username:'') . ' Cube One to Wallet Withdraw Requested ' . showAmount($cubeonetowithdraw->amount) . ' ' . $general->cur_text;
        $details    = $cubeonetowithdraw->withdraw_information ? json_encode($cubeonetowithdraw->withdraw_information) : null;
        return view('admin.cubeonetowithdraw.detail', compact('pageTitle', 'cubeonetowithdraw', 'details'));
    }

    public function approve(Request $request) {
        $request->validate(['id' => 'required|integer']);
        $withdraw                 = WithdrawalsRequestCubeOneToWallet::where('id', $request->id)->where('status', 1)->with('user')->firstOrFail();
        $userId = $withdraw->user_id;
        $amount = $withdraw->amount;
        $ft = $withdraw->ft;
        $trx = $withdraw->trx;
        

        $price_ft = GeneralSetting::first();
        
        $user = User::find($userId);
        $interestWallet = $user->interest_wallet;
        $depositWallet = $user->deposit_wallet;
        $currentFT = $user->deposit_ft;
        $newFT = $ft;

        if ($amount > $user->interest_wallet) {
            $notify[] = ['error', 'You do not have sufficient balance for withdraw.'];
            return back()->withNotify($notify);
        }

        $withdraw->status         = 2;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        // // Calculate the new wallet balances
        $newInterestWallet = $interestWallet - $amount;
        $newDepositWallet = $depositWallet + $amount;
        $newFTValue = $currentFT + $newFT;
        
        // // Update the user's wallet balances
        $user->interest_wallet = $newInterestWallet;
        $user->deposit_wallet = $newDepositWallet;
        $user->deposit_ft = $newFTValue;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = getAmount($newFT);
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->trx          = getTrx();
        // $transaction->wallet_type  = 'Deposit Wallet FT';
        // $transaction->remark       = 'balance_transfer';
        $transaction->wallet_type  = 'Deposit Wallet';
        $transaction->remark       = 'Balance Transfer';
        $transaction->details      = 'Balance credited Rewards Cube1 to Deposit Wallet FT';
        $transaction->post_balance = getAmount($currentFT);
        $transaction->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = getAmount($amount);
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx;
        // $transaction->wallet_type  = 'Deposit Wallet FT';
        // $transaction->remark       = 'balance_transfer';
        $transaction->wallet_type  = 'interest_wallet';
        $transaction->remark       = 'Balance Transfer';
        $transaction->details      = 'Balance Transfer Rewards Cube1 to Deposit Wallet FT';
        $transaction->post_balance = getAmount($interestWallet);
        $transaction->save();

        $notify[] = ['success', 'Withdrawal approved successfully'];
        return to_route('admin.cubeonetowithdraw.pending')->withNotify($notify);
    }

    public function reject(Request $request) {
        $general = gs();
        $request->validate(['id' => 'required|integer']);
        $withdraw = WithdrawalsRequestCubeOneToWallet::where('id', $request->id)->where('status', 1)->with('user')->firstOrFail();
        $withdraw->status = 3;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        $notify[] = ['success', 'Withdrawal rejected successfully'];
        return to_route('admin.cubeonetowithdraw.pending')->withNotify($notify);
    }

}
