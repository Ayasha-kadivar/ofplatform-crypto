<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Transaction;
use App\Models\User;
use App\Models\OldUsers;
use App\Models\UserLogin;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use App\Models\RentNFT;
use App\Models\UserParentAffiliate;
use App\Models\GeneralSetting;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
        $this->middleware('registration.status')->except('registrationNotAllowed');
    }

    public function registerSuccess(){
        $pageTitle = "Old Registeration Received";
        return view($this->activeTemplate . 'user.auth.oldregistersuccess', compact('pageTitle'));
    }

    public function showRegistrationForm(Request $request)
    {
        $pageTitle = "Register";
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
    
        // Set the 'reference' variable as a cookie with a 24-hour expiration time
        $response = response(view($this->activeTemplate . 'user.auth.register', compact('pageTitle', 'mobileCode', 'countries')));
        if(isset($request->reference)){
            $response->cookie('reference', $request->reference, 7200); // 7200 minutes = 5 days
        }
        return $response;
    }

    public function showOldRegistrationForm()
    {
        $pageTitle = "Register";
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'user.auth.oldregister', compact('pageTitle','mobileCode','countries'));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $general = gs();
        $passwordValidation = Password::min(6);
        if ($general->secure_password) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        
        $messages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'email.max' => 'The email address must not exceed 50 characters.',
            'email.regex' => 'Please provide a valid email address.',
            'mobile.required' => 'The mobile field is required.',
            'mobile.regex' => 'Please enter a valid mobile number.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'username.required' => 'The username field is required.',
            'username.regex' => 'The username filed must contain only letters and numbers.',
            'username.unique' => 'This username is already taken.',
            'username.min' => 'The username must be at least 6 characters.',
            'username.max' => 'The username must not exceed 25 characters.',
            'captcha.required' => 'Please enter the captcha code.',
            'mobile_code.required' => 'Please select your mobile code.',
            'mobile_code.in' => 'Please select a valid mobile code.',
            'country_code.required' => 'Please select your country code.',
            'country_code.in' => 'Please select a valid country code.',
            'country.required' => 'Please select your country.',
            'country.in' => 'Please select a valid country.',
            'agree.required' => 'You must agree to the terms and conditions.'
        ];

        $validate = Validator::make($data, [
            'email' => 'required|string|email|unique:users|max:50|regex:/^[a-z0-9\.\-\_]+@[a-z0-9\.\-\_]+\.[a-z]+$/i',
            'mobile' => 'required|numeric',
            'password' => ['required','confirmed',$passwordValidation],
            'username' => 'required|unique:users|min:6|max:25|regex:/^[a-zA-Z0-9]{6,25}$/',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree,
            'firstname' => 'required|string|max:25',
            'lastname' => 'required|string|max:25'

        ],$messages);
        return $validate;

    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if(preg_match("/[^a-zA-Z0-9]/", trim($request->username))){
            $notify[] = ['info', 'Username can contain only alphanumeric and numeric.'];
            $notify[] = ['error', 'No special character or space or diacritic characters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        if($request->mobile && str_starts_with($request->mobile, '0')){
            $notify[] = ['error', 'Enter the mobile number without start with prefix like, 0'];
            return back()->withNotify($notify)->withInput();
        }

        $exist = User::where('mobile',$request->mobile_code.$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'Mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        $exist_email = User::where('email',$request->email)->first();
        if ($exist_email) {
            $notify[] = ['error', 'Email address already exists'];
            return back()->withNotify($notify)->withInput();
        }

        $requestData = $request->all();
        $refererralUser=0;
        //Get the ID of the refered user
        if($request->reference){
            $refererralUser = $request->reference;
        }else if($request->cookie('reference')){
            $refererralUser = $request->cookie('reference');
        }
        if($refererralUser){
            $refUserDetail = User::where('username',$refererralUser)->first();
            if (!$refUserDetail) {
                $notify[] = ['error', 'The referral code you entered does not exist.'];
                return back()->withNotify($notify)->withInput();
            }
            $requestData['ref_by'] = intval($refUserDetail->id);  
        }else{
            $requestData['ref_by'] = 0; 
        }  
        event(new Registered($user = $this->create($requestData)));

        $apiUrl = env('ACTIVE_CAMPAIGN_API_URL');
        $apiKey = env('ACTIVE_CAMPAIGN_API_KEY');



        // Register with Wordpress Database
        try{
            $WPdbConnected = (bool)DB::connection('wordpress')->getPDO();
        } catch (\Exception $e) {
            $WPdbConnected = false;
        }

        if($WPdbConnected){
            $emailExists = DB::connection('wordpress')
            ->table('users')
            ->where('user_email', $request->email)
            ->count();

            if ($emailExists == 0 || $emailExists == '' || empty($emailExists)) {
                // Insert the user data into the WordPress users table
                DB::connection('wordpress')->table('users')->insert([
                    'user_login' => $request->username, // You may use a different field as the login
                    'user_pass' => '',//md5($request->password), // Password should be hashed according to WordPress's hashing method
                    'user_email' => $request->email,
                    'display_name' => $request->username,
                    'user_registered' => now(),
                    // Add other fields as needed
                ]);
            }
        }
        // End Register With Wordpress Datasposaspobase
        
        $client = new Client();

        try {
            $response = $client->post("$apiUrl/contacts", [
                RequestOptions::JSON => [
                    'contact' => [
                        'email' => $request->email,
                        'firstName' => $request->firstname,
                        'lastName' => $request->lastname,
                        'phone' => $request->mobile_code . $request->mobile,
                        'tags' => 'CryptoFamilyUser Sign Ups',
                    ]
                ],
                RequestOptions::HEADERS => [
                    'Api-Token' => $apiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            // dd( $responseData['contact']['id'] );
            $contactId = $responseData['contact']['id'];

            // Make the second API request to add the contact to the list
            $response = $client->post("$apiUrl/contactLists", [
                RequestOptions::JSON => [
                    'contactList' => [
                        'contact' => $contactId,
                        'list' => 6,
                        'status' => 1,
                    ]
                ],
                RequestOptions::HEADERS => [
                    'Api-Token' => $apiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            // dd($responseData);
            // Handle the response as needed
        } catch (GuzzleException $e) {
            // Handle any errors thrown by Guzzle
        }


        $this->guard()->login($user);
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function old_registration_validator(array $data)
    {
        $general = gs();
        $passwordValidation = Password::min(6);
        if ($general->secure_password) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        $validate = Validator::make($data, [
            'email' => 'required|string|email|unique:old_users',
            'mobile' => 'required|regex:/^([0-9]*)$/',
            'password' => ['required','confirmed',$passwordValidation],
            'username' => 'required|unique:old_users|min:6',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree
        ]);
        return $validate;

    }

    public function old_register(Request $request)
    {

        $this->old_registration_validator($request->all())->validate();

        $request->session()->regenerateToken();

        if(preg_match("/[^a-z0-9_]/", trim($request->username))){
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }


        $exist = OldUsers::where('mobile',$request->mobile_code.$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        $address   = [
            'country' => $request->country,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zipcode,
            'city'    => $request->city,
        ];   

        $old_users = new OldUsers;
        $old_users->firstname = $request->firstname;
        $old_users->lastname = $request->lastname;
        $old_users->username = $request->username;
        $old_users->email = $request->email;
        $old_users->country_code = $request->country_code;
        $old_users->mobile = $request->mobile;
        $old_users->password = Hash::make($request->password);
        $old_users->address = json_encode($address);
        $old_users->balance = $request->balance;
        $old_users->sponsor_country_code = $request->sponsor_country_code;
        $old_users->sponsor_phone = $request->sponsor_phone;
        $old_users->purchased_packages = $request->purchased_packages1.'||'.$request->purchased_packages2;
        $old_users->date = $request->date;
        $old_users->number_of_packages = $request->number_of_packages;
        $old_users->maintenance_fee_paid = $request->maintenance_fee_paid;
        $old_users->google_form = $request->google_form;
        $old_users->has_hash_id = $request->has_hash_id;
        $old_users->hash_id = $request->hash_id;
        $old_users->save();     
        //Redirect to the message page    
        return redirect()->route('user.register-success');
        
        echo 'User Registered successfully';exit;
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $general = gs();

        //User Create
        $user = new User();
        $user->firstname = trim($data['firstname']);
        $user->lastname = trim($data['lastname']);
        $user->email = strtolower(trim($data['email']));
        $user->password = Hash::make($data['password']);
        $user->username = trim($data['username']);
        $user->ref_by = $data['ref_by'] ? $data['ref_by'] : 0;
        $user->country_code = $data['country_code'];
        $user->mobile = $data['mobile_code'].$data['mobile'];
        $user->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $user->status = 1;
        $user->kv = $general->kv ? 0 : 1;
        $user->ev = $general->ev ? 0 : 1;
        $user->sv = $general->sv ? 0 : 1;
        $user->ts = 0;
        $user->tv = 1;
        $user->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail',$user->id);
        $adminNotification->save();


        //Login Log Create
        $ip = getRealIP();
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->exists();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->exists();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->exists();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function checkOldUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = OldUsers::where('email',$request->email)->exists();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = OldUsers::where('mobile',$request->mobile)->exists();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = OldUsers::where('username',$request->username)->exists();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function registered(Request $request, $user)
    {
        $general = gs();
        $price_ft = GeneralSetting::first();
        if($general->signup_bonus_control == 1){
            $userWallet = $user;
            $userWallet->deposit_wallet += $general->signup_bonus_amount * $price_ft->price_ft;
            $userWallet->deposit_ft += $general->signup_bonus_amount;
            $userWallet->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $general->signup_bonus_amount;
            $transaction->charge       = 0;
            $transaction->post_balance = $userWallet->deposit_ft;
            $transaction->trx_type     = '+';
            $transaction->trx          =  getTrx();
            $transaction->wallet_type  = 'deposit_wallet';
            $transaction->remark       = 'registration_bonus';
            $transaction->details      = 'You have got registration bonus';
            $transaction->save();
        }

        // Code shifted to register controller

        // $parentUser = User::find($user->ref_by);
        // // dd($parentUser['pool_2']);

        // if(!$parentUser) {
        //     return to_route('user.home');
        // }

        // if($parentUser['pool_2'] == NUll || $parentUser['pool_2'] < 25){
        //     if($parentUser){
        //         notify($parentUser, 'REFERRAL_JOIN', [
        //             'ref_username' => $user->username
        //         ]);
        //     }
    
        //     return to_route('user.home');
        // }
        // $calSponsorOnePercent = ($parentUser['pool_2']-24)/100;
        // $initialPool2Balance = $parentUser['pool_2'];

        // /* Start calculation of referrals (Temp area we will shift this code where necessary) */   

        // //Charge $24 to the sponsor
        // User::where('id', $parentUser['id'])->update([
        //     'pool_2' => (($parentUser['pool_2']-25)-$calSponsorOnePercent),
        //     'interest_wallet' => ($parentUser['interest_wallet']+($calSponsorOnePercent+1)),
        // ]);

        // /* Start Log Transactions */

        // //Remove 24$ from sponser pool2
        // $trx = getTrx();

        // $transaction               = new Transaction();
        // $transaction->user_id      = $parentUser['id'];
        // $transaction->amount       = 24;
        // $transaction->charge       = 0;
        // $transaction->post_balance = $initialPool2Balance;
        // $transaction->trx_type     = '-';
        // $transaction->trx          = $trx;
        // $transaction->remark       = 'referral';
        // $transaction->wallet_type  = 'pool_2';
        // $transaction->details      = showAmount(24) . ' ' . $general->cur_text . ' referral deducted from Pool2';
        // $transaction->save(); 

        // //Added 1$ from sponser pool2 to Pool1
        // $transaction               = new Transaction();
        // $transaction->user_id      = $parentUser['id'];
        // $transaction->amount       = 1;
        // $transaction->charge       = 0;
        // $transaction->post_balance = $initialPool2Balance-24;
        // $transaction->trx_type     = '+';
        // $transaction->trx          = $trx;
        // $transaction->remark       = 'referral bonus';
        // $transaction->wallet_type  = 'pool_1';
        // $transaction->details      = showAmount(1) . ' ' . $general->cur_text . ' referral bonus transferred from Pool2 to Pool1';
        // $transaction->save();      
        
        // //Added 1% amount of pool2 transferred to Pool1 from sponsor account
        // $transaction               = new Transaction();
        // $transaction->user_id      = $parentUser['id'];
        // $transaction->amount       = 1;
        // $transaction->charge       = 0;
        // $transaction->post_balance = ($initialPool2Balance-23)/100;
        // $transaction->trx_type     = '+';
        // $transaction->trx          = $trx;
        // $transaction->remark       = 'referral bonus';
        // $transaction->wallet_type  = 'pool_2';
        // $transaction->details      = showAmount(($initialPool2Balance-23)/100) . ' ' . $general->cur_text . ' referral bonus transferred from Pool2 to Pool1';
        // $transaction->save();  
        
        // /* End Log Transactions */

        // //Check if the sponsor has 100 referrals
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
        // /* End calculation of referrals */
        
        // // Assign 1 NFT Contract
        // $date = Carbon::now()->toDateTimeString();
        // $nextProfitDate = Carbon::now()->addDays(9)->format('Y-m-d');
        // $contractExpiryDate = Carbon::now()->addDays(89)->format('Y-m-d');
        // $receipt = new RentNFT;
        // $receipt->user_meta_mask_info = 'referal bonus';
        // $receipt->one_nft_price = 24;
        // $receipt->ft_price = GeneralSetting::first()->price_ft;
        // $receipt->rented_nft = 1;
        // $receipt->buying_date = $date;
        // $receipt->next_profit_date = $nextProfitDate;
        // $receipt->contract_expiry_date = $contractExpiryDate;
        // $receipt->user_id = $user->id;
        // $receipt->save();        
        
        // if($parentUser){
        //     notify($parentUser, 'REFERRAL_JOIN', [
        //         'ref_username' => $user->username
        //     ]);
        // }

        return to_route('user.home');
    }

    public function oldregister(Request $request)
    {
       
        $pageTitle = 'Old User';
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'user.auth.oldregister', compact('pageTitle','mobileCode','countries'));
    }

} 
