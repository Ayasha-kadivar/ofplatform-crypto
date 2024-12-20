<?php
namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\HyipLab;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HASHID;


class PaymentController extends Controller
{

    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('name')->get();
        $pageTitle = 'Deposit Methods';
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }

    public function depositInsert(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency'    => 'required',
            'deposit_hash' => [
                function ($attribute, $value, $fail) use ($request) {
                    // Validate the pattern (66 alphanumeric characters)
                    if (!preg_match('/^[a-zA-Z0-9]{66}$/', $value)) {
                        $fail(' Transaction HASH ID must contain 66 alphanumeric characters, Please check again and resubmit compliant HASH ID!');
                    }

                    // Check uniqueness in the users table
                    $userExists = User::where('maintenance_fee_hash', $value)->exists();

                    if ($userExists) {
                        $fail('Transaction HASH ID already exist in our database, Open support ticket or send e-mail to issues@ourfamily.support');
                    }

                    

                    // Check uniqueness in the deposits table
                    $depositExists = Deposit::where('deposit_hash', $value)->first();

                    if ($depositExists && $depositExists->status != 0) {
                        $fail('Transaction HASH ID already exist in our database, Open support ticket or send e-mail to issues@ourfamily.support');
                    } else {
                        if ($depositExists && $depositExists->status == 0) {
                            $depositExists->delete();
                            $hashexist = HASHID::where('hash_id', $value)->first();
                            if($hashexist){
                                $hashexist->delete();
                            }
                        }
                    }

                    
                    $check_fee_hash_id_exists = checkHashPayment($value);
                    
                    if(!$check_fee_hash_id_exists){
                        $fail('Transaction HASH ID already exist in our database, Open support ticket or send e-mail to issues@ourfamily.support');
                    }
                },
            ],
        ]);

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        $user      = auth()->user();

        if($user->maintenance_expiration_date < date("Y-m-d")){
            $notify[] = ['error', 'Please pay maintenance fees first.'];
            return to_route('user.maintenance-fee')->withNotify($notify);
        }

        // if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
        //     $notify[] = ['error', 'Please follow deposit limit'];
        //     return back()->withNotify($notify);
        // }

        $data = self::insertDeposit($gate, $request->amount, $request->deposit_hash);

        
        $arr=[
            "user_id"=>$user->id,
            "reflect_user_id" => $user->id,
            "amount"=>$request->amount,
            "trx_type"=>'deposit',
            "hash_id"=>$request->deposit_hash,
            "remark"=>'user manually added deposit FT',
            "created_at"=>Carbon::now()
        ];
                                        
        $t_hashid = \DB::table('payment_transaction_hash_id')->insertGetId($arr);
        
        $data  = Deposit::with('gateway')->where('status', 0)->where('trx', $data->trx)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }

        $manual_deposit_transaction_id = $request->deposit_hash;
        
        if($manual_deposit_transaction_id){
            $unique_deposit_exist  = Deposit::with('gateway')->where('manual_deposit_transaction_id', $manual_deposit_transaction_id)->first();
            if($unique_deposit_exist){
                $notify[] = ['error', 'Something went wrong with the Transaction ID Please enter the valid and latest Transaction ID.'];
                return \Redirect::back()->withNotify($notify);
            }
        }

        $data->detail = NULL;
        $data->manual_deposit_transaction_id = $manual_deposit_transaction_id;
        $data->status = 2; // pending
        $data->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = 'Deposit request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amo),
            'amount'          => showAmount($data->amount),
            'charge'          => showAmount($data->charge),
            'rate'            => showAmount($data->rate),
            'trx'             => $data->trx,
        ]);

        // session()->put('Track', $data->trx);
        // return to_route('user.deposit.confirm');

        $notify[] = ['success', ' Deposit successfuly sent, Kindly wait for Admin approval!'];
        return to_route('user.deposit.history')->withNotify($notify);
    }

    public static function insertDeposit($gateway, $amount, $deposit_hash, $investPlan = null)
    {
        $general = GeneralSetting::first();
        $user      = auth()->user();
        $charge    = $gateway->fixed_charge + (($amount * $general->price_ft) * $gateway->percent_charge / 100);
        $payable   = ($amount * $general->price_ft) + $charge;
        $final_amo = $payable;

        $data = new Deposit();
        if ($investPlan) {
            $data->plan_id = $investPlan->id;
        }
        $data->user_id         = $user->id;
        $data->method_code     = $gateway->method_code;
        $data->method_currency = strtoupper($gateway->currency);
        $data->amount          = $amount;
        $data->charge          = $charge;
        $data->rate            = $gateway->rate;
        $data->final_amo       = $final_amo;
        $data->btc_amo         = 0;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();
        $data->deposit_hash    = $deposit_hash;
        $data->save();

        return $data;
    }

    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception$ex) {
            return "Sorry, invalid URL.";
        }
        $data = Deposit::where('id', $id)->where('status', 0)->orderBy('id', 'DESC')->firstOrFail();
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    public function depositConfirm()
    {
        $track   = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', 0)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new     = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    public static function userDataUpdate($deposit, $isManual = null)
    {
        if ($deposit->status == 0 || $deposit->status == 2) {
            $deposit->status = 1;
            $deposit->save();
            $general = GeneralSetting::first();
            $user = User::find($deposit->user_id);
            $user->deposit_wallet += ($deposit->amount * $general->price_ft);
            if($deposit->method_currency == 'FT') {
                $user->deposit_ft += $deposit->amount;
            } else {
                $user->deposit_ft += $deposit->amount / $general->price_ft;
            }
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $deposit->user_id;
            $transaction->amount       = $deposit->amount;
            $transaction->post_balance = $user->deposit_wallet;
            $transaction->charge       = $deposit->charge;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Deposit Via ' . $deposit->gatewayCurrency()->name;
            $transaction->trx          = $deposit->trx;
            $transaction->wallet_type  = 'deposit_wallet';
            $transaction->remark       = 'deposit';
            $transaction->save();

            if (!$isManual) {
                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $user->id;
                $adminNotification->title     = 'Deposit successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            $general = GeneralSetting::first();

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name'     => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount'   => showAmount($deposit->final_amo),
                'amount'          => showAmount($deposit->amount),
                'charge'          => showAmount($deposit->charge),
                'rate'            => showAmount($deposit->rate),
                'trx'             => $deposit->trx,
                'post_balance'    => showAmount($user->deposit_wallet / $general->price_ft),
            ]);

            
            // if ($general->deposit_commission) {
            //     HyipLab::levelCommission($user, $deposit->amount, 'deposit_commission', $deposit->trx, $general);
            // }

            if ($deposit->plan_id) {
                $plan = Plan::where('status', 1)->findOrFail($deposit->plan_id);
                $hyip = new HyipLab($user, $plan);
                $hyip->invest($deposit->amount, 'deposit_wallet');
            }

        }
    }

    public function manualDepositConfirm()
    {   
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $pageTitle = 'Deposit Confirm';
            $method    = $data->gatewayCurrency();
            $gateway   = $method->method;
            return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {   
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form?->form_data ?? [];
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);
        $manual_deposit_transaction_id = '';
        if($userData){
            foreach ($userData as $key => $value) {
                if(mb_substr($value['name'], 0, 11) == "Transaction"){ // 
                    $manual_deposit_transaction_id = $value['value'];
                }
            }
        }

        if($manual_deposit_transaction_id){
            $unique_deposit_exist  = Deposit::with('gateway')->where('manual_deposit_transaction_id', $manual_deposit_transaction_id)->first();
            if($unique_deposit_exist){
                $notify[] = ['error', 'Something went wrong with the Transaction ID Please enter the valid and latest Transaction ID.'];
                return \Redirect::back()->withNotify($notify);
            }
        }

        $data->detail = $userData;
        $data->manual_deposit_transaction_id = $manual_deposit_transaction_id;
        $data->status = 2; // pending
        $data->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = 'Deposit request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amo),
            'amount'          => showAmount($data->amount),
            'charge'          => showAmount($data->charge),
            'rate'            => showAmount($data->rate),
            'trx'             => $data->trx,
        ]);

        $notify[] = ['success', 'You have deposit request has been taken'];
        return to_route('user.deposit.history')->withNotify($notify);
    }

}
