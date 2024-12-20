<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Lib\HyipLab;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\Invest;
use App\Models\PromotionTool;
use App\Models\Referral;
use App\Models\SupportTicket;
use App\Models\Transaction;
use League\Csv\Writer;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use App\Models\Withdrawal;
use App\Models\RequestPayment;
use App\Models\WithdrawalsRequestCubeOneToWallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function home()
    {
        $data['pageTitle']         = 'Dashboard';
        $user                      = auth()->user();
        $data['user']              = $user;
        $data['totalInvest']       = Invest::where('user_id', auth()->id())->sum('amount');
        $data['totalWithdraw']     = Withdrawal::where('user_id', $user->id)->whereIn('status', [1])->sum('amount');
        $data['lastWithdraw']      = Withdrawal::where('user_id', $user->id)->whereIn('status', [1])->latest()->first('amount');
        $data['totalDeposit']      = Deposit::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $data['lastDeposit']       = Deposit::where('user_id', $user->id)->where('status', 1)->latest()->first('amount');
        $data['totalTicket']       = SupportTicket::where('user_id', $user->id)->count();
        $data['transactions']      = $data['user']->transactions->sortByDesc('id')->take(8);
        $data['referral_earnings'] = Transaction::where('remark', 'referral_commission')->where('user_id', auth()->id())->sum('amount');

        $data['submittedDeposits']  = Deposit::where('status', '!=', 0)->where('user_id', $user->id)->sum('amount');
        $data['successfulDeposits'] = Deposit::successful()->where('user_id', $user->id)->sum('amount');
        $data['requestedDeposits']  = Deposit::where('user_id', $user->id)->sum('amount');
        $data['initiatedDeposits']  = Deposit::initiated()->where('user_id', $user->id)->sum('amount');
        $data['pendingDeposits']    = Deposit::pending()->where('user_id', $user->id)->sum('amount');
        $data['rejectedDeposits']   = Deposit::rejected()->where('user_id', $user->id)->sum('amount');

        $data['submittedWithdrawals']  = Withdrawal::where('status', '!=', 0)->where('user_id', $user->id)->sum('amount');
        $data['successfulWithdrawals'] = Withdrawal::approved()->where('user_id', $user->id)->sum('amount');
        $data['rejectedWithdrawals']   = Withdrawal::rejected()->where('user_id', $user->id)->sum('amount');
        $data['initiatedWithdrawals']  = Withdrawal::initiated()->where('user_id', $user->id)->sum('amount');
        $data['requestedWithdrawals']  = Withdrawal::where('user_id', $user->id)->sum('amount');
        $data['pendingWithdrawals']    = Withdrawal::pending()->where('user_id', $user->id)->sum('amount');

        $data['invests']               = Invest::where('user_id', $user->id)->sum('amount');
        $data['completedInvests']      = Invest::where('user_id', $user->id)->where('status', 0)->sum('amount');
        $data['runningInvests']        = Invest::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $data['interests']             = Transaction::where('remark', 'interest')->where('user_id', $user->id)->sum('amount');
        $data['depositWalletInvests']  = Invest::where('user_id', $user->id)->where('wallet_type', 'deposit_wallet')->where('status', 1)->sum('amount');
        $data['interestWalletInvests'] = Invest::where('user_id', $user->id)->where('wallet_type', 'interest_wallet')->where('status', 1)->sum('amount');

        $data['isHoliday']      = HyipLab::isHoliDay(now()->toDateTimeString(), gs());
        $data['nextWorkingDay'] = now()->toDateString();
        if ($data['isHoliday']) {
            $data['nextWorkingDay'] = HyipLab::nextWorkingDay(24);
            $data['nextWorkingDay'] = Carbon::parse($data['nextWorkingDay'])->toDateString();
        }

        $data['chartData'] = Transaction::where('remark', 'interest')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('user_id', $user->id)
            ->selectRaw("SUM(amount) as amount, DATE_FORMAT(created_at,'%Y-%m-%d') as date")
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $data['vip_data'] = RequestPayment::where('user_id',$user->id)->where('trx_type','vip_membership')->where('status',0)->orderBy('id', 'desc')->first();

        $data['deposit_data'] = Deposit::successful()->where('user_id', $user->id)->orderBy('id', 'desc')->take(5)->get();
        
        $data['withdrawal_data'] = WithdrawalsRequestCubeOneToWallet::where('user_id',$user->id)->orderBy('id', 'desc')->take(5)->get();



        return view($this->activeTemplate . 'user.dashboard', $data);
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits  = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $general   = gs();
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle = 'Transactions';
        $remarks   = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark', 'wallet_type'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form      = Form::where('act', 'kyc')->first();
        return view($this->activeTemplate . 'user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user      = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form           = Form::where('act', 'kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);

        $userData       = $formProcessor->processFormData($request, $formData);
        $user           = auth()->user();
        $user->kyc_data = $userData;
        $user->kv       = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);

    }

    public function walletForm()
    {
        if (auth()->user()->wallet_address == 1) {
            $notify[] = ['error', 'You are already Wallet verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'Wallet Address Form';
        return view($this->activeTemplate . 'user.wallet.form', compact('pageTitle'));
    }

    public function walletData()
    {
    
        $user      = auth()->user();
        $pageTitle = 'Wallet Data';
        return view($this->activeTemplate . 'user.wallet.info', compact('pageTitle', 'user'));
    }

    public function walletSubmit(Request $request)
    {
        
        if(empty($request->wallet_address)){
            $notify[] = ['error', 'Wallet Address is required.'];
            return to_route('user.wallet.form')->withNotify($notify);    
        }
        if(empty($request->otp)){
            $notify[] = ['error', 'OTP is required.'];
            return to_route('user.wallet.form')->withNotify($notify);    
        }
        if(Session::get('wallet_address_otp') == $request->otp){
            $user                 = auth()->user();
            $is_duplicate = User::where('wallet_data',$request->wallet_address)
            ->where('id','!=',$user->id)
            ->first();
            if($is_duplicate){
                $notify[] = ['error', 'Wallet Address has already been taken please add valid one.'];
                return to_route('user.wallet.form')->withNotify($notify);
            }
            Session::forget('wallet_address_otp');
            $user->wallet_data    = $request->wallet_address;
            $user->wallet_address = 1;
            $user->save();
        }else{
            $notify[] = ['error', 'Invalid OTP entered.'];
            return to_route('user.wallet.form')->withNotify($notify);    
        }        

        $notify[] = ['success', 'Wallet Address data submitted successfully.'];
        return to_route('user.home')->withNotify($notify);

    }

    public function otpsend(Request $request){
        $otp = rand(100000, 999999);
        Session::put('wallet_address_otp', $otp);
        // send the OTP to the user via email
        $user = User::findOrFail(auth()->user()->id);
        notify($user, 'DEFAULT', [
            'subject' => 'OTP Verification for wallet Address',
            'message' => 'Your OTP for wallet update is <div><br></div><div style="text-align: center;"><b><font size="6">'.$otp.'</font></b></div>',
        ], ['email'],false);
        return 'otp_sent';
    }

    public function attachmentDownload($fileHash)
    {
        $filePath  = decrypt($fileHash);
        /*$extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = gs();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);*/

        if(file_exists(public_path('assets/' . $filePath))){
            return \Redirect::to('assets/' . $filePath);
        }

        $filePath = $filePath ? str_replace('assets/','',$filePath) : $filePath;
        $disk = \Storage::disk('gcs');
        $exists = $disk->exists($filePath);
        if($exists){
            return \Redirect::to($disk->url($filePath));
        }else{
            $notify[] = ['error', 'Attachment Does\'t Exists!'];
            return back()->withNotify($notify);
        }
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'User Data';
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $request->validate([
            // 'firstname' => 'required|alpha',
            // 'lastname'  => 'required|alpha',
            'state'    => ['regex:/^[\pL\pN\s()-]+$/u'],
            'zip'      => 'nullable|min:4|max:15|alpha_num',
            'city'  => ['regex:/^[\pL\pN\s()-]+$/u'],
            'address'  => 'required|regex:/^[\pL\pN.,:\/\-_()\[\]{}@#&!$%^*+=?<>\s]+$/u',
        ], [
            // 'firstname.required' => 'The first name field is required.',
            // 'lastname.required' => 'The last name field is required.',
            'state.regex'         => 'The state field must only contain letters, numbers, spaces, parentheses, and hyphens.',
            'zip.min'             => 'The zip code must be at least 5 characters.',
            'zip.max'             => 'The zip code must not exceed 15 characters.',
            'address.required' => 'The address field is required.',
            'address.regex' => 'The address field must only contain letters, numbers, dots, commas, colons, forward slashes, hyphens, and underscores.'
            ,
        ]);
        // $user->firstname = $request->firstname;
        // $user->lastname  = $request->lastname;
        $user->address   = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city,
        ];

        $user->profile_complete = 1;
        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function referrals()
    {
        
        $pageTitle = 'Referrals';
        $user      = auth()->user();
        $active      = User::where('ref_by',auth()->user()->id)->where('is_block',0)->count();
        $deactivated      = User::where('ref_by',auth()->user()->id)->where('is_block',1)->count();
        $all      = User::where('ref_by',auth()->user()->id)->count();
        $maxLevel  = Referral::max('level');
        return view($this->activeTemplate . 'user.referrals', compact('pageTitle', 'user', 'maxLevel','active','deactivated','all'));
    }
    private function generateCsvRows(User $user, Writer $csv, $referralPath, $mainUser = true)
{
    if ($mainUser) {
        $referralPath = 'Referal to Register';
    }
    
    $referralPath .= ',' . $user->username;

    foreach ($user->allReferrals as $under) {
        $row = [
            $under->fullname,
            $under->username,
            $under->email,
            $under->mobile,
            $mainUser ? $referralPath : $user->username,
            $under->is_block ? 'Deactivated' : 'Active',
        ];
        $csv->insertOne($row);
    
        // if ($under->allReferrals->count() > 0) {
        //     $this->generateCsvRows($under, $csv, $referralPath, false);
        // }
    }
}

    
    
    public function exportReferralTreeToCSV(Request $request)
{
    $user = User::findOrFail(auth()->id());

    $csv = Writer::createFromString('');
    $csv->insertOne(['Fullname', 'Username', 'Email', 'Mobile', 'Referral Path', 'Status']);

    $loggedInUser = auth()->user();
    $this->generateCsvRows($user, $csv, '', $loggedInUser);

    $csvContent = $csv->getContent();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="referral_tree.csv"',
    ];

    return response()->make($csvContent, 200, $headers);
}

    
    
    

    public function promotionalBanners()
    {
        $general = gs();
        $promotionCount = PromotionTool::count();
        if (!$general->promotional_tool || !$promotionCount) {
            abort(404);
        }
        $pageTitle    = 'Promotional Banners';
        $banners      = PromotionTool::orderBy('id', 'desc')->get();
        $emptyMessage = 'No banner found';
        return view($this->activeTemplate . 'user.promo_tools', compact('pageTitle', 'banners', 'emptyMessage'));
    }

    public function transferBalance()
    {
        $general = gs();
        if (!$general->b_transfer) {
            abort(404);
        }
        $pageTitle = 'Balance Transfer';
        $user      = auth()->user();
        return view($this->activeTemplate . 'user.balance_transfer', compact('pageTitle', 'user'));
    }

    public function transferBalanceSubmit(Request $request)
    {
        $general = gs();
        if (!$general->b_transfer) {
            abort(404);
        }
        $request->validate([
            'username' => 'required',
            'amount'   => 'required|numeric|gt:0',
            'wallet'   => 'required|in:deposit_wallet,interest_wallet',
        ]);

        $user = auth()->user();
        if ($user->username == $request->username) {
            $notify[] = ['error', 'You cannot transfer balance to your own account'];
            return back()->withNotify($notify);
        }

        $receiver = User::where('username', $request->username)->first();
        if (!$receiver) {
            $notify[] = ['error', 'Oops! Receiver not found'];
            return back()->withNotify($notify);
        }

        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify);
            }
        }

        $general     = gs();
        $charge      = $general->f_charge + ($request->amount * $general->p_charge) / 100;
        $afterCharge = $request->amount + $charge;
        $wallet      = $request->wallet;

        if($wallet == 'deposit_wallet') {
            if ($user->deposit_ft < $afterCharge) {
                return getResponse('insufficient_balance', 'error', ['You have no sufficient balance to this wallet']);
            }
            $walletAmount = $user->deposit_ft;
            $user->deposit_ft -= $afterCharge;
        } else {
            if ($user->$wallet < $afterCharge) {
                return getResponse('insufficient_balance', 'error', ['You have no sufficient balance to this wallet']);
            }
            $walletAmount = $user->$wallet;
        }

        $user->$wallet -= $afterCharge;
        $user->save();

        $trx1                      = getTrx();
        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = getAmount($afterCharge);
        $transaction->charge       = $charge;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx1;
        $transaction->wallet_type  = $wallet;
        $transaction->remark       = 'balance_transfer';
        $transaction->details      = 'Balance transfer to ' . $receiver->username;
        $transaction->post_balance = getAmount($walletAmount);
        $transaction->save();

        $general = GeneralSetting::first();
        $price_ft = $general->price_ft;

        $receiver->deposit_ft += $request->amount;
        $receiver->deposit_wallet += $request->amount * $price_ft;
        $receiver->save();

        $trx2                      = getTrx();
        $transaction               = new Transaction();
        $transaction->user_id      = $receiver->id;
        $transaction->amount       = getAmount($request->amount);
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx2;
        $transaction->wallet_type  = 'deposit_wallet';
        $transaction->remark       = 'balance_received';
        $transaction->details      = 'Balance received from ' . $user->username;
        $transaction->post_balance = getAmount($user->deposit_ft);
        $transaction->save();

        notify($user, 'BALANCE_TRANSFER', [
            'amount'        => showAmount($request->amount),
            'charge'        => showAmount($charge),
            'wallet_type'   => keyToTitle($wallet),
            'post_balance'  => showAmount($user->$wallet),
            'user_fullname' => $receiver->fullname,
            'username'      => $receiver->username,
            'trx'           => $trx1,
        ]);

        notify($receiver, 'BALANCE_RECEIVE', [
            'wallet_type'  => 'Deposit wallet',
            'amount'       => showAmount($request->amount),
            'post_balance' => showAmount($receiver->deposit_ft),
            'sender'       => $user->username,
            'trx'          => $trx2,
        ]);

        $notify[] = ['success', 'Balance transferred successfully'];
        return back()->withNotify($notify);
    }

    public function findUser(Request $request)
    {
        $user    = User::where('username', $request->username)->first();
        $message = null;
        if (!$user) {
            $message = 'User not found';
        }
        if (@$user->username == auth()->user()->username) {
            $message = 'You cannot send money to your own account';
        }
        return response(['message' => $message]);
    }

}
