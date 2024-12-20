<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Gateway;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function pending()
    {
        $pageTitle = 'Pending Deposits';
        $deposits  = $this->depositData('pending');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function approved()
    {
        $pageTitle = 'Approved Deposits';
        $deposits  = $this->depositData('approved');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function successful()
    {
        $pageTitle = 'Successful Deposits';
        $deposits  = $this->depositData('successful');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function rejected(Request $request)
    {
        $pageTitle = 'Rejected Deposits';
        $deposits  = $this->depositData('rejected');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function initiated(Request $request)
    {
        $pageTitle = 'Initiated Deposits';
        $deposits  = $this->depositData('initiated');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function deposit(Request $request)
    {
        $pageTitle   = 'Deposit History';
        $depositData = $this->depositData($scope = null, $summery = true);
        $deposits    = $depositData['data'];
        $summery     = $depositData['summery'];
        $successful  = $summery['successful'];
        $pending     = $summery['pending'];
        $rejected    = $summery['rejected'];
        $initiated   = $summery['initiated'];
        return view('admin.deposit.log', compact('pageTitle', 'deposits', 'successful', 'pending', 'rejected', 'initiated'));
    }

    protected function depositData($scope = null, $summery = false,)
    {
        $request = request();
        if ($scope) {
            $deposits = Deposit::$scope()->with(['user', 'gateway'])->whereHas('user', function ($query) {
                $query->where('users.id', '>', 0);
            });
        } else {
            $deposits = Deposit::with(['user', 'gateway'])->whereHas('user', function ($query) {
                $query->where('users.id', '>', 0);
            });
        }

        $deposits = $deposits->searchable(['trx', 'user:username'])->dateFilter();

        // Filter by method
        if ($request->method) {
            $method = Gateway::where('alias', $request->method)->firstOrFail();
            $deposits = $deposits->where('method_code', $method->code);
        }

        // Filter by deposit_hash
        if ($request->deposit_hash) {
            $deposits = $deposits->where('deposit_hash', $request->deposit_hash);
        }

        if (!$summery) {
            return $deposits->orderBy('id', 'desc')->paginate(getPaginate());
        } else {
            $successful = clone $deposits;
            $pending = clone $deposits;
            $rejected = clone $deposits;
            $initiated = clone $deposits;

            $successfulSummery = $successful->where('status', 1)->sum('amount');
            $pendingSummery = $pending->where('status', 2)->sum('amount');
            $rejectedSummery = $rejected->where('status', 3)->sum('amount');
            $initiatedSummery = $initiated->where('status', 0)->sum('amount');

            return [
                'data' => $deposits->orderBy('id', 'desc')->paginate(getPaginate()),
                'summery' => [
                    'successful' => $successfulSummery,
                    'pending' => $pendingSummery,
                    'rejected' => $rejectedSummery,
                    'initiated' => $initiatedSummery,
                ],
            ];
        }
    }

    public function details($id)
    {
        $general   = gs();
        $deposit   = Deposit::where('id', $id)->with(['user', 'gateway'])->whereHas('user', function ($query) {
            $query->where('users.id','>',0);
        })->firstOrFail();
        $pageTitle = (isset($deposit->user->username)?$deposit->user->username:'') . ' requested ' . showAmount($deposit->amount) . ' ' . $deposit->method_currency;
        $details   = ($deposit->detail != null) ? json_encode($deposit->detail) : null;
        return view('admin.deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }

    public function approve($id)
    {
        $deposit = Deposit::where('id', $id)->where('status', 2)->firstOrFail();
        PaymentController::userDataUpdate($deposit, true);

        $notify[] = ['success', 'Deposit request approved successfully'];
        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'message' => 'required|string|max:255',
        ]);
        $deposit = Deposit::where('id', $request->id)->where('status', 2)->firstOrFail();

        $deposit->admin_feedback = $request->message;
        $deposit->status         = 3;
        $deposit->save();

        notify($deposit->user, 'DEPOSIT_REJECT', [
            'method_name'       => $deposit->gatewayCurrency()->name,
            'method_currency'   => $deposit->method_currency,
            'method_amount'     => showAmount($deposit->final_amo),
            'amount'            => showAmount($deposit->amount),
            'charge'            => showAmount($deposit->charge),
            'rate'              => showAmount($deposit->rate),
            'trx'               => $deposit->trx,
            'rejection_message' => $request->message,
        ]);

        $notify[] = ['success', 'Deposit request rejected successfully'];
        return to_route('admin.deposit.pending')->withNotify($notify);
    }


    public function updateFt(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'ft' => 'required',
        ]);
        $deposit = Deposit::where('id', $request->id)->where('status', 2)->firstOrFail();
        if(!$deposit){
            $notify[] = ['success', 'Deposit request already approved or something is wrong.'];
            return back()->withNotify($notify);
        }

        $deposit->amount = $request->ft;
        $deposit->final_amo = $request->ft;
        $deposit->save();

        $user = User::where('id',$deposit->user_id)->first();
        notify($user, 'DEFAULT', [
            'subject' => 'Deposit Request Modified Successfully.',
            'message' => '<div><span style="font-weight: bolder; font-size: 1rem; text-align: var(--bs-body-text-align);">Details of your Deposit :</span><br></div><div><br></div><div>New Amount : '.$request->ft.' '.$deposit->method_currency.'</div><div>Transaction Number : '.$deposit->trx.'</div><div><br></div><div><br style="font-family: Montserrat, sans-serif;"></div>',
        ]);


        $notify[] = ['success', 'Deposit FT modified successfully'];
        return back()->withNotify($notify);
    }
}
