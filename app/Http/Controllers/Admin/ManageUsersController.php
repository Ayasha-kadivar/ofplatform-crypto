<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\NotificationLog;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\OldUsers;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\RentNFT;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use App\Models\RequestPayment;



class ManageUsersController extends Controller
{

    public function userNew()
    {
        $pageTitle = 'New Users';
        $users     = $this->usernewData();
        return view('admin.users.usernew', compact('pageTitle', 'users'));
    }

    protected function usernewData()
    {
        $users = User::whereNotNull('created_at')->paginate(getPaginate());
        return $users;
    }
    public function userOld()
    {
        $pageTitle = 'Old Users';
        $users = $this->olduserData();
        // $users = User::whereNull('created_at')->get();
        // dd($users);
        return view('admin.users.userold', compact('pageTitle','users'));
    }
    public function maintenenceFee()
    {
        $pageTitle = 'Fee Pending';
        $users_fee     = $this->maintenenceData();
        //dd($users_fee->toArray());
        return view('admin.users.maintenance-fee', compact('pageTitle', 'users_fee'));
    }

    public function vipPending()
    {
        $pageTitle = 'VIP Pending';
        $users_fee     = $this->vipData();
        return view('admin.users.vip-fee', compact('pageTitle', 'users_fee'));
    }

    protected function vipData()
    {
        $request = request();
        $users_feeQuery = RequestPayment::with('user')->where('trx_type','vip_membership')->where('status', 0);
        // Filter by deposit_hash
        if ($request->deposit_hash) {
            $users_feeQuery = $users_feeQuery->where('hash_id', $request->deposit_hash);
        }
        // Apply searchable fields
        if (isset($request->search) && !empty($request->search)) {
            $users_feeQuery->whereHas('user', function($q) use($request){
                $q->Where('username', $request->search);
                $q->orWhere('email', $request->search);
            });
        }
        return $users_feeQuery->paginate(getPaginate());
    }

    protected function maintenenceData()
    {
        // $users_fee = User::where('fee_status', 1)->paginate(getPaginate());
        // return $users_fee;
        $request = request();
        $users_feeQuery = User::where('fee_status', 1);
        // Filter by deposit_hash
        if ($request->deposit_hash) {
            $users_feeQuery = $users_feeQuery->where('maintenance_fee_hash', $request->deposit_hash);
        }
        // Apply searchable fields
        if (isset($request->search) && !empty($request->search)) {
            $users_feeQuery->searchable(['username', 'email']);
        }
        return $users_feeQuery->paginate(getPaginate());
    }
    public function feeDetails($id)
    {
        $pageTitle = 'Fee Details';
        $user      = User::findOrFail($id);
        return view('admin.users.fee_detail', compact('pageTitle', 'user'));
    }

    public function vipDetails($id)
    {
        $pageTitle = 'VIP Details';
        $user      = RequestPayment::where('id',$id)->where('trx_type','vip_membership')->with(['user'])->first();
        if(!$user){
            $notify[] = ['error', 'Record does not exist!'];
            return redirect()->back()->withNotify($notify);
        }
        return view('admin.users.vip_detail', compact('pageTitle', 'user'));
    }
    
    public function unferifiedFee()
    {
        $pageTitle = 'Fee Unverified';
        $users_fee     = $this->unverifiedfeeData();
        return view('admin.users.fee-unverified', compact('pageTitle', 'users_fee'));
    }

    protected function unverifiedfeeData()
    {
        $users_fee = User::where('fee_status', 0)->paginate(getPaginate());
        return $users_fee;
    }
    public function allUsers()
    {
        $pageTitle = 'All Users';
        $users     = $this->userData('');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function allOldUsers()
    {
        $pageTitle = 'All Old Registartions';
        $users     = $this->userOldRegistration();
        return view('admin.users.old-registration-list', compact('pageTitle', 'users'));
    }    

    public function activeUsers()
    {
        $pageTitle = 'Active Users';
        $users     = $this->userData('active');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function deactiveUsers()
    {
        $pageTitle = 'Deactivated Users';
        $users     = $this->userData('deactivated');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Banned Users';
        $users     = $this->userData('banned');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $users     = $this->userData('emailUnverified');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycUnverifiedUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users     = $this->userData('kycUnverified');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycVerifiedUsers()
    {
        $pageTitle = 'KYC Verified Users';
        $users     = $this->userData('kycVerified');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycPendingUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users     = $this->userData('kycPending');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $users     = $this->userData('emailVerified');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function mobileUnverifiedUsers()
    {
        $pageTitle = 'Mobile Unverified Users';
        $users     = $this->userData('mobileUnverified');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function mobileVerifiedUsers()
    {
        $pageTitle = 'Mobile Verified Users';
        $users     = $this->userData('mobileVerified');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function usersWithBalance()
    {
        $pageTitle = 'Users with Balance';
        $users     = $this->userData('withBalance');
        $request = request();
        if($request->ajax()){
            return $users;
        }
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    protected function userData($scope = null)
    {
        // if ($scope) {
        //     $users = User::$scope();
        // } else {
        //     $users = User::query();
        // }

        // return $users->searchable(['username', 'email','mobile'])->orderBy('id', 'desc')->paginate(getPaginate());
        // Define the base query based on the $scope parameter
        $request = request();
        if ($scope) {
            $users = User::$scope();
        } else {
            $users = User::query();
        }
        $orderBy = 'deposit_ft';
        $sort = $request->input('sort', 'smallest'); // Default to 'smallest' if not provided
        $requestedAmount = $request->input('balance');


        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $country = $request->input('country');
        $city = $request->input('city');
        $zip = $request->input('zip');
        $sponsor = $request->input('sponsor');
        $wallet_address = $request->input('wallet_address');
        $hash_id = $request->input('hash_id');
        $no_of_rented_nft = $request->input('no_of_rented_nft');
        $username = $request->input('username');

        if($name){
            $users->whereRaw('firstname like ?', '%'.$name.'%');
        }if($surname){
            $users->whereRaw('lastname like ?', '%'.$surname.'%');
        }if($email){
            $users->whereRaw('email like ?', '%'.$email.'%');
        }if($phone){
            $users->whereRaw('mobile like ?', '%'.$phone.'%');
        }if($country){
            $users->whereRaw('address like ?', '%'.$country.'%');
        }if($city){
            $users->whereRaw('address like ?', '%'.$city.'%');
        }if($zip){
            $users->whereRaw('address like ?', '%'.$zip.'%');
        }if($wallet_address){
            $users->whereRaw('wallet_data like ?', '%'.$wallet_address.'%');
        }if($hash_id){
            $users->whereRaw('vip_fee_hash like ?', '%'.$hash_id.'%');
            // $users->whereRaw(
            // function($query) {
            // return $query
            //         ->whereRaw('maintenance_fee_hash like ?', '%'.$hash_id.'%')
            //         ->orWhereRaw('vip_fee_hash like ?', '%'.$hash_id.'%');
            // });
        }if($username){
            $users->whereRaw('username like ?', '%'.$username.'%');
        }
         if($sponsor){

            $users->whereHas('referrer', function ($query) use($sponsor){
                $query->where('username', 'like', '%'.$sponsor.'%');
            });
            // $users->join('users as ua', function($join) use($sponsor){
            //     $join->on('users.ref_by', '=', 'ua.id')
            //     ->whereRaw('ua.username like ?', '%'.$sponsor.'%');
            // });
         }
         $users->with('allNFTsCount');
         if($no_of_rented_nft){
            //$users->whereRaw('firstname like ? ', ['%'.$no_of_rented_nft.'%']);
            //$users->has('sumOFNFT', '=', $no_of_rented_nft);
            $users->whereHas("allNFTsCount",function ($query) use($no_of_rented_nft){
                $query->havingRaw($no_of_rented_nft.' = sum(rented_nft)');
                $query->selectRaw('sum(rented_nft) as total_rented');
            });
         }

        $range = 100000; // Define your desired range here
        
        if (isset($request->amount_type) && !empty($request->amount_type)) {
            // Check if the amount_type is 'total'
            if ($request->input('amount_type') === 'total') {
                // Calculate the total amount as the sum of deposit_wallet, interest_wallet, pool_2, pool_3, and pool_4
                $users->selectRaw('*, (deposit_ft + interest_wallet + pool_2 + pool_3 + pool_4) as total_amount');
                //$users->whereRaw('(deposit_ft + interest_wallet + pool_2 + pool_3 + pool_4) BETWEEN ? AND ?', [$requestedAmount - $range, $requestedAmount + $range]);
                $users->whereRaw('(deposit_ft + interest_wallet + pool_2 + pool_3 + pool_4) > ? ',$requestedAmount);
                $orderBy = "total_amount";
            } elseif ($request->input('amount_type') === 'deposit') {
                // Apply the condition for 'deposit' amount_type
                $users->selectRaw('*, deposit_ft as total_amount');
                //$users->whereRaw('deposit_ft BETWEEN ? AND ?', [$requestedAmount - $range, $requestedAmount + $range]);
                $users->whereRaw('deposit_ft > ? ',$requestedAmount);
                $orderBy = "deposit_ft";
            } elseif ($request->input('amount_type') === 'cubeone') {
                // Apply the condition for 'cubeone' amount_type
                $users->selectRaw('*, interest_wallet as total_amount');
                //$users->whereRaw('interest_wallet BETWEEN ? AND ?', [$requestedAmount - $range, $requestedAmount + $range]);
                $users->whereRaw('interest_wallet > ?',$requestedAmount);
                $orderBy = "interest_wallet";
            } elseif ($request->input('amount_type') === 'cubetwo') {
                // Apply the condition for 'cubeone' amount_type
                $users->selectRaw('*, pool_2 as total_amount');
                //$users->whereRaw('pool_2 BETWEEN ? AND ?', [$requestedAmount - $range, $requestedAmount + $range]);
                $users->whereRaw('pool_2 > ?',$requestedAmount);
                $orderBy = "pool_2";
            } elseif ($request->input('amount_type') === 'cubethree') {
                // Apply the condition for 'cubeone' amount_type
                $users->selectRaw('*, pool_3 as total_amount');
                //$users->whereRaw('pool_3 BETWEEN ? AND ?', [$requestedAmount - $range, $requestedAmount + $range]);
                $users->whereRaw('pool_3 > ?', $requestedAmount);
                $orderBy = "pool_3";
            } elseif ($request->input('amount_type') === 'cubefour') {
                // Apply the condition for 'cubeone' amount_type
                $users->selectRaw('*, pool_4 as total_amount');
                //$users->whereRaw('pool_4 BETWEEN ? AND ?', [$requestedAmount - $range, $requestedAmount + $range]);
                $users->whereRaw('pool_4 > ?', $requestedAmount);
                $orderBy = "pool_4";
            }
        }

        // Apply searchable fields
        // if(isset($request->search) && !empty($request->search)) {
        //     $users->searchable(['username', 'email', 'mobile']);
        // }

        // Sort the results based on the sort parameter
        if ($sort === 'smallest') {
            //$users = $users->orderBy($orderBy, 'asc');
        } elseif ($sort === 'largest') {
            //$users = $users->orderBy($orderBy, 'desc');
        }
        $users = $users->orderBy('id', 'desc');
        if($request->ajax()){
            ini_set("memory_limit", "-1");
            set_time_limit(0);
            $res = $users->get();
            
            $newArr = [];
            //$users->chunk(500, function($results) {
                if($res){
                    foreach ($res as $key => $value) {
                        $newArr[$key]['Full Name'] = $value->fullname;
                        $newArr[$key]['Username'] = $value->username;
                        $newArr[$key]['Email'] = $value->email;
                        $newArr[$key]['Phone'] = $value->mobile;
                        $newArr[$key]['Country'] = $value->country_code;
                        $newArr[$key]['Total Balances'] = ($value->deposit_ft+$value->pool_2+$value->pool_3+$value->pool_4+$value->interest_wallet);
                        $newArr[$key]['Cube 1 Balance'] = isset($value->interest_wallet)?$value->interest_wallet:0;
                        $newArr[$key]['Cube 2 Balance'] = isset($value->pool_2)?$value->pool_2:0;
                        $newArr[$key]['Cube 3 Balance'] = isset($value->pool_3)?$value->pool_3:0;
                        $newArr[$key]['Cube 4 Balance'] = isset($value->pool_4)?$value->pool_4:0;
                        $newArr[$key]['Deposit Balance'] = isset($value->deposit_ft)?$value->deposit_ft:0;
                        $newArr[$key]['Total FamilyNFTs'] = $value->allNFTsCount->sum('rented_nft')?$value->allNFTsCount->sum('rented_nft'):0;
                    }
                }
           // });   

            if($request->input('is_csv')==1){
                return \Response::json(['data' => $newArr], 200);
            }else{            
                $excelData = '';
                if($newArr){
                    $excelData = implode("\t", array_keys($newArr[0])) . "\n"; 

                    foreach ($newArr as $row) {
                        $excelData .= implode("\t", array_values($row)) . "\n"; 
                    }
                }
                // Headers for download 
                header("Content-Type: application/vnd.ms-excel"); 
                //application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
                header("Content-Disposition: attachment; filename=\"users.xls\""); 
                // Render excel data 
                return \Response::json(['data' => "data:application/vnd.ms-excel;base64,".base64_encode($excelData)], 200);
            }
            //echo $results = $users->toSql(); exit;
            
            // $query = str_replace(array('?'), array('\'%s\''), $users->toSql());
            // $query = vsprintf($query, $users->getBindings());
            // dump($query);
        }
        // Paginate the results
            // $query = str_replace(array('?'), array('\'%s\''), $users->toSql());
            // $query = vsprintf($query, $users->getBindings());
            // dump($query);
        //echo $results = $users->toSql(); exit;
        return $results = $users->paginate(getPaginate());
    }

    protected function userOldRegistration()
    {

        $users = OldUsers::query();
        return $users->orderBy('id', 'desc')->paginate(getPaginate());
    }
    protected function olduserData()
    {

        $users = User::whereNull('created_at')->paginate(getPaginate());
        return $users;
    }

    protected function searchByEmailUserName()
    {
        $search =request()->q;
        if($search){
            $users = User::select(\DB::raw("CONCAT(maintenance_expiration_date,'==',vip_user_date) AS dates"),\DB::raw("CONCAT(email,' (',username,')') AS email"),'id','vip_user_date','maintenance_expiration_date')->where('email','like','%'.$search.'%')->orWhere('username','like','%'.$search.'%')->get();
            return json_encode($users);
        }else{
            return json_encode([]);
        }
    }

    
    protected function searchByDetails()
    {
        $search =request()->id;
        $amount =request()->amount;
        $t_type =request()->t_type;
        if($search){
            $users = User::select(\DB::raw('DATE_FORMAT(maintenance_expiration_date, "%Y-%m-%d") as maintenance_expiration_date'),'id','vip_user_date')->where('id',$search)->first();
            if(!$amount){
                return json_encode($users);
            }
            if($amount != 20 && $amount != 200 && $amount != 10){
                return json_encode([]);
            }
            
            if($t_type == 'vip_membership' && ($amount == 20 || $amount == 200)){

                if($users->vip_user_date > date("Y-m-d")){
                    if($amount == 20){
                        $nextProfitDate = Carbon::parse($users->vip_user_date)->addDays(30)->format('Y-m-d');
                    }else{
                        $nextProfitDate = Carbon::parse($users->vip_user_date)->addDays(365)->format('Y-m-d');
                    }
                }else{
                    if($amount == 20){
                        $nextProfitDate = Carbon::now()->addDays(30)->format('Y-m-d');
                    }else{
                        $nextProfitDate = Carbon::now()->addDays(365)->format('Y-m-d');
                    }
                }
                
                $users->next_expiration_date = $nextProfitDate;
            }else{
                $nextProfitDate = '';
                if($amount == 10 && $t_type =='maintenance_fees'){
                    if($users->maintenance_expiration_date  > date("Y-m-d")){
                        $nextProfitDate = Carbon::parse($users->maintenance_expiration_date)->addDays(365)->format('Y-m-d');
                    }else{
                        $nextProfitDate = Carbon::now()->addDays(365)->format('Y-m-d');
                    }                
                    $users->next_expiration_date = $nextProfitDate;
                }
            }
            return json_encode($users);
        }else{
            return json_encode([]);
        }
    }


    public function updateSponsor(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if($user && $request->sponsorSearch){

            $userF = User::findOrFail($request->sponsorSearch);
            if(!$userF){
                $notify[] = ['error', 'Invalid sponsor selected.'];
                return back()->withNotify($notify);
            }
            $user->ref_by = $userF->id;
            $user->save();            
            $notify[] = ['success', 'User sponsor updated successfully'];
            return back()->withNotify($notify);
        }else{
            $notify[] = ['error', 'Something went to worng / User not found'];
            return back()->withNotify($notify);
        }
    }

    public function detail($id)
    {
        $user = User::with('referrer')->findOrFail($id);
        $pageTitle = 'User Detail - ' . $user->username;

        $referrerEmail = $user->referrer->email ?? null; // Get the email of the user who referred the current user
        $referrerUserName = $user->referrer->username ?? null;

        $totalDeposit     = Deposit::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $totalWithdrawals = Withdrawal::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $totalTransaction = Transaction::where('user_id', $user->id)->count();
        $pendingTicket    = SupportTicket::where('user_id', $user->id)->whereIN('status', [0, 2])->count();
        $countries        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.detail', compact('pageTitle', 'user', 'totalDeposit', 'totalWithdrawals', 'totalTransaction', 'pendingTicket', 'countries', 'referrerEmail','referrerUserName'));
    }

    public function oldUserDetail($id)
    {
        $user      = OldUsers::findOrFail($id);
        $pageTitle = 'User Detail - ' . $user->username;

        // $oldBalance     = $user->balance;
        // $totalWithdrawals = Withdrawal::where('user_id', $user->id)->where('status', 1)->sum('amount');
        // $totalTransaction = Transaction::where('user_id', $user->id)->count();
        // $pendingTicket    = SupportTicket::where('user_id', $user->id)->whereIN('status', [0, 2])->count();
        $countries        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.old_registration_detail', compact('pageTitle', 'user', 'countries'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Delete associated records in rent_nft table
        DB::table('rent_nft')->where('user_id', $user->id)->delete();

        // Delete the user
        $user->delete();

        $notify[] = ['success', 'User deleted successfully'];
        return to_route('admin.users.all')->withNotify($notify);
    }

    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $user      = User::findOrFail($id);
        return view('admin.users.kyc_detail', compact('pageTitle', 'user'));
    }

    public function kycApprove($id)
    {
        $user     = User::findOrFail($id);
        $user->kv = 1;
        // $user->fee_status = 2;
        // $user->maintenance_fee = 'UsingKYCManual';
        // $user->maintenance_expiration_date = Carbon::now()->addDays(365);
        $user->save();

        notify($user, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function kycReject($id)
    {
        $user = User::findOrFail($id);
        foreach ($user->kyc_data as $kycData) {
            if ($kycData->type == 'file') {
                $disk = \Storage::disk('gcs');
                $filepath = getFilePath('verify') . '/' . $kycData->value;
                $exists = $disk->exists($filepath);
                if($exists){
                    $disk->delete($filepath);
                }
                //fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $user->kv       = 0;
        $user->kyc_data = null;
        $user->save();

        notify($user, 'KYC_REJECT', []);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $user         = User::findOrFail($id);
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $general = GeneralSetting::first();
        $countryArray = (array) $countryData;
        $countries    = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        // $country     = $countryData->$countryCode->country;
        // $dialCode    = $countryData->$countryCode->dial_code;
        $country = $request->country ? $countryData->$countryCode->country : '';
        $dialCode = $request->country ? $countryData->$countryCode->dial_code : '';
        
        if($request->mobile){
            $is_mobile = User::where('mobile',$dialCode . $request->mobile)
            ->where('id','!=',$user->id)
            ->first();

            if($is_mobile){
                $notify[] = ['error', $user->username . ' Mobile number has already been taken.'];
                return back()->withNotify($notify);
            }
        }       

        
        if($request->is_block){
            $is_block = 1;
        }else{
            $is_block = 0;
        }

        if($is_block == 0 && $user->is_block==1){
            if(isset($request->maintenance_expiration_date) && !empty($request->maintenance_expiration_date) && $request->maintenance_expiration_date < date('Y-m-d')){
                $notify[] = ['error', 'If you want to unblock please change expiry greater than today.'];
                return back()->withNotify($notify);
            }
        }
        
        if((auth('admin')->user()->id != 29 && auth('admin')->user()->id != 19 && auth('admin')->user()->role_status != 0)){
            $request->validate([
                'email'     => 'required|email|string|max:40|unique:users,email,' . $user->id,
                'mobile'    => 'required|string|max:40|unique:users,mobile,' . $user->id,
                'country'   => 'in:' . $countries,
            ]);

            $user->mobile       = $dialCode . $request->mobile;
            $user->country_code = $countryCode;
            $user->email        = $request->email;
            $user->ts = $request->ts ? 1 : 0;

            $user->address      = [
                'address' => $user->address->address,
                'city'    => $user->address->city,
                'state'   => $user->address->state,
                'zip'     => $user->address->zip,
                'country' => @$country,
            ];
        }else{
        
            $general = gs();
            //$passwordValidation = Password::min(6);
            $passwordValidation = Password::min(6)->mixedCase()->numbers()->symbols()->uncompromised();
            // if ($general->secure_password) {
            //     $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
            // }

            

            $request->validate([
                'firstname' => 'required|string|max:40',
                'lastname'  => 'required|string|max:40',
                'username'     => 'required|min:6|max:25|regex:/^[a-zA-Z0-9._-]{6,25}$/|unique:users,username,' . $user->id,
                'email'     => 'required|email|string|max:40|unique:users,email,' . $user->id,
                'mobile'    => 'required|string|max:40|unique:users,mobile,' . $user->id,
                'country'   => 'in:' . $countries,
                // 'country'   => $countryCode ?? '',
                'maintenance_expiration_date' => 'required_if:fee_status,on',
                'maintenance_note' => 'max:145',
                'wallet_data' => 'nullable|string|max:200|unique:users,wallet_data,' . $user->id,
            ]);

            //alpha_num|
            //['alpha_num'=> 'Stellar wallet address must alphanumeric characters.']

            if($request->wallet_data && !empty($request->wallet_data)){
                if(strlen($request->wallet_data) != 56){
                    $notify[] = ['error', 'Stellar wallet address must contain 56 alphanumeric characters, Please check again and resubmit a compliant  Stellar wallet address!'];
                    return redirect()->back()->withNotify($notify);
                }
            }

            if($request->password){
                $request->validate([
                    'password' => ['required',$passwordValidation],
                ]);

                $user->password  = Hash::make($request->password);
            }
    

            $user->mobile       = $dialCode . $request->mobile;
            $user->email        = $request->email;
            $user->ts = $request->ts ? 1 : 0;
            $user->country_code = $countryCode;
            $user->firstname    = $request->firstname;
            $user->lastname     = $request->lastname;
            $user->username        = $request->username;
            if($request->wallet_data && !empty($request->wallet_data)){
                $user->wallet_data    = $request->wallet_data;
                $user->wallet_address = 1;
            }else{
                $user->wallet_data    = NULL;
                $user->wallet_address = 0;
            }
            $user->address      = [
                'address' => $request->address,
                'city'    => $request->city,
                'state'   => $request->state,
                'zip'     => $request->zip,
                'country' => @$country,
            ];
            $user->ev = $request->ev ? 1 : 0;
            $user->sv = $request->sv ? 1 : 0;
            if (!$request->kv) {
                $user->kv = 0;
                if ($user->kyc_data) {
                    foreach ($user->kyc_data as $kycData) {
                        if ($kycData->type == 'file') {
                            $disk = \Storage::disk('gcs');
                            $filepath = getFilePath('verify') . '/' . $kycData->value;
                            $exists = $disk->exists($filepath);
                            if($exists){
                                $disk->delete($filepath);
                            }
                            //fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                        }
                    }
                }
                $user->kyc_data = null;
            } else {
                $user->kv = 1;
                $user->fee_status = 2;
                $user->maintenance_fee = 'UsingKYCManual';
                $user->maintenance_expiration_date = Carbon::now()->addDays(365);
            }

            if(isset($request->fee_status) && !empty($request->fee_status)) {
                $user->fee_status = 2;
                $user->maintenance_expiration_date = $request->maintenance_expiration_date;
                $user->maintenance_note = isset($request->maintenance_note)?$request->maintenance_note:'';
            } else {
                $user->fee_status = 0;
            }

            if($is_block == 0 && $user->is_block==1 && isset($request->maintenance_expiration_date) && !empty($request->maintenance_expiration_date)){
                if($request->maintenance_expiration_date > date('Y-m-d')){
                    $user->is_block = 0;
                    $user->is_suspend = 0;
                }
            }

            if($is_block == 1 && $user->is_block==0 && isset($request->maintenance_expiration_date) && !empty($request->maintenance_expiration_date) && $request->maintenance_expiration_date < date('Y-m-d')){
                $user->is_block = 1;
                $user->is_suspend = 1;
            }

            if($user->created_at < Carbon::now()->subDays(60)->toDateTimeString() && !$request->fee_status){
                $user->is_block = $is_block;
                //$user->maintenance_expiration_date = NULL;    
            }
            
            // if($user->is_block != $is_block && !$request->fee_status){
                
            // }

            if (auth('admin')->check() && (auth('admin')->user()->role_status == 0 || auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 )){
                if($request->launch_nft_owner=='on'){
                    $user->launch_nft_owner = 1;
                }else{
                    $user->launch_nft_owner = 0;
                }

                
                if($request->vip_user=='on'){
                    
                 
                    $date_of_user = date('Y-m-d', strtotime("+30 days"));
                    $user->vip_user = 1;
                    $user->vip_user_date = ($request->vip_user_date)?$request->vip_user_date:$date_of_user;
                }else{
                    $user->vip_user = 0;
                    $user->vip_user_date = NULL;
                }


                if($request->assembly_user=='on'){
                    $date_of_assembly = date('Y-m-d', strtotime("+30 days"));
                    $user->assembly_user = 1;
                    $user->assembly_user_date = ($request->assembly_user_date)?$request->assembly_user_date:$date_of_assembly;
                }else{
                    $user->assembly_user = 0;
                    $user->assembly_user_date = NULL;
                }
            }
        }

        $user->save();

        $notify[] = ['success', 'User details updated successfully'];
        return back()->withNotify($notify);
    }

    public function oldPorfileUpdate(Request $request, $id)
    {
        $user         = OldUsers::findOrFail($id);
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array) $countryData;
        $countries    = implode(',', array_keys($countryArray));

        $countryCode = $request->country_code;
        $country     = $countryData->$countryCode->country;
        $dialCode    = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'email'     => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile'    => 'required|string|max:40|unique:users,mobile,' . $user->id,
            'country_code'   => 'required|in:' . $countries,
        ]);
        $address   = [
            'country' => $request->country_code,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zipcode,
            'city'    => $request->city,
        ]; 

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->country_code = $request->country_code;
        $user->mobile = $dialCode . $request->mobile;
        $user->address = json_encode($address);
        $user->balance = $request->balance;
        $user->sponsor_country_code = $request->sponsor_country_code;
        $user->sponsor_phone = $request->sponsor_phone;
        $user->purchased_packages = $request->purchased_packages1.'||'.$request->purchased_packages2;
        $user->date = $request->date;
        $user->number_of_packages = $request->number_of_packages;
        $user->maintenance_fee_paid = $request->maintenance_fee_paid;
        $user->google_form = $request->google_form;
        $user->has_hash_id = $request->has_hash_id;
        $user->hash_id = $request->hash_id;

        if($request->profile_status){
            $userStatus = 'Approved';
        }else{
            $userStatus = 'Awaited';
        }
        $user->status = $userStatus;
        $user->save();

        $notify[] = ['success', 'User details updated successfully'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount'      => 'required|numeric|gt:0',
            'act'         => 'required|in:add,sub',
            'wallet_type' => 'required|in:deposit_wallet,interest_wallet,pool_3,pool_4,pool_2',
            'remark'      => 'required|string|max:255',
        ]);

        $user    = User::findOrFail($id);
        $amount  = $request->amount;
        $wallet  = $request->wallet_type;
        $general = gs();
        $trx     = getTrx();

        $transaction = new Transaction();
        $general = GeneralSetting::first();
        $price_ft = $general->price_ft;

        if ($request->act == 'add') {
            if($wallet == 'deposit_wallet') {
                $user->deposit_ft += $amount;
                $user->$wallet += ($amount * $price_ft);
            } else {
                $user->$wallet += $amount;
            }
            $transaction->trx_type = '+';
            $transaction->remark   = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', $general->cur_sym . $amount . ' added successfully'];

        } else {
            if($wallet == 'deposit_wallet') {
                if ($amount > $user->deposit_ft) {
                    $notify[] = ['error', $user->username . ' doesn\'t have sufficient balance.'];
                    return back()->withNotify($notify);
                } else {
                    $user->deposit_ft -= $amount;
                    $user->$wallet -= ($amount / $price_ft);
                }
            } else {
                if ($amount > $user->$wallet) {
                    $notify[] = ['error', $user->username . ' doesn\'t have sufficient balance.'];
                    return back()->withNotify($notify);
                } else {
                    $user->$wallet -= $amount;
                }
            }
            //$user->$wallet -= $amount;
            $transaction->trx_type = '-';
            $transaction->remark   = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[]       = ['success', $general->cur_sym . $amount . ' subtracted successfully'];
        }

        $user->save();

        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $user->$wallet;
        $transaction->charge       = 0;
        $transaction->trx          = $trx;
        $transaction->details      = $request->remark;
        $transaction->wallet_type  = $wallet;
        $transaction->save();

        notify($user, $notifyTemplate, [
            'trx'          => $trx,
            'amount'       => showAmount($amount),
            'remark'       => $request->remark,
            'post_balance' => showAmount($user->$wallet),
        ]);

        return back()->withNotify($notify);
    }

    public function login($id)
    {
        /* $user = User::find($id);
        if($user->is_block == 1){
            $notify[] = ['error', 'Your account has been deactivated as Maintenance fee has not been paid on time. Kindly reach to issue@ourfamily.support for more information!'];
            return back()->withNotify($notify);
        } */
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status == 1) {
            $validatearr = [
                'reason' => 'required|string|max:255',
            ];
            if($request->type_ban == 'temporary'){
                $validatearr['till_ban_date'] = 'required|date|date_format:Y-m-d|after:today';
            }
            $request->validate($validatearr);
            $user->status        = 0;
            $user->ban_reason    = $request->reason;
            $user->ban_type      = $request->type_ban;
            $user->till_ban_date = $request->till_ban_date;
            $notify[]            = ['success', 'User banned successfully'];
        } else {
            $user->status        = 1;
            $user->ban_reason    = null;
            $user->ban_type      = null;
            $user->till_ban_date = null;
            $notify[]         = ['success', 'User unbanned successfully'];
        }
        $user->save();
        return back()->withNotify($notify);
    }

    public function showNotificationSingleForm($id)
    {
        $user    = User::findOrFail($id);
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.users.detail', $user->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $user->username;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }
        $users     = User::active()->count();
        $pageTitle = 'Notification to Verified Users';
        return view('admin.users.notification_all', compact('pageTitle', 'users'));
    }

    public function sendNotificationAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $user = User::active()->skip($request->skip)->first();

        if (!$user) {
            return response()->json([
                'error'=>'User not found',
                'total_sent'=>0,
            ]);
        }

        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'success'    => 'message sent',
            'total_sent' => $request->skip + 1,
        ]);
    }

    public function notificationLog($id)
    {
        $user      = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $user->username;
        $logs      = NotificationLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'user'));
    }
    
    
    public function removeFamilyNFT(Request $request, $id)
    {
        $request->validate([
            'nfts'      => 'required|integer|min:1',
        ]);

        $user    = User::findOrFail($id);
        $parentUser = [];
        if(isset($user->ref_by) && !empty($user->ref_by)){
            $parentUser = User::find($user->ref_by);
        }
        if(!$user || $user->status == 0){
            $notify[] = ['warning', $user->username.' is banned or not found on data.'];
            return back()->withNotify($notify);
        }
        
        $general = GeneralSetting::first();
        $paymentMethod = 'Deposit Wallet';
        $reward_amt = $request->nfts;
        
        $getOneNft = RentNFT::where('user_id',$user->id)->where('contract_expiry_date', '>', date("Y-m-d"))->where('rented_nft','>=',$reward_amt)->orderBy('created_at','DESC')->first();
        
        if(!$getOneNft){
            $notify[] = ['warning', $user->username.' account not exist with this Qty for rentNFT.'];
            return back()->withNotify($notify);
        }

        $nfts = $getOneNft->rented_nft;
        $removeordeduct = 'deduct';
        if($nfts == $reward_amt){
            $removeordeduct = 'remove';
        }

        if($removeordeduct == 'remove'){
            $getOneNft->delete();
        }else{
            RentNFT::where('id', $getOneNft->id)->update([
                'rented_nft' => ($getOneNft->rented_nft-$reward_amt),
            ]);
        }
        
        //REWARD $1 to the sponsor
        if($parentUser && !empty($parentUser)){
            User::where('id', $parentUser['id'])->update([
                'interest_wallet' => ($parentUser['interest_wallet']-($reward_amt)),
            ]);
        }

        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $reward_amt;
        $transaction->charge       = 0;
        $transaction->post_balance = 0;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx;
        $transaction->remark       = 'FamilyNFT';
        $transaction->wallet_type  = 'reward_cubes';
        $transaction->details      = 'Super admin removed FamilyNFT to your account';
        $transaction->save();


        if($parentUser && !empty($parentUser)){

            //Added 1$ to sponser  // paid by company 
            $transaction               = new Transaction();
            $transaction->user_id      = $parentUser['id'];
            $transaction->amount       = $reward_amt;
            $transaction->charge       = 0;
            $transaction->post_balance = ($parentUser['interest_wallet']);
            $transaction->trx_type     = '-';
            $transaction->trx          = getTrx();
            $transaction->remark       = 'referral bonus';
            $transaction->wallet_type  = 'interest_wallet';
            $transaction->details      = showAmount($reward_amt) . ' ' . $general->cur_text . ' referral bonus deducted ';
            $transaction->save();    
        }


        $notify[] = ['success', 'FamilyNFT removed successfully to '.$user->username.' Account.'];    
        return back()->withNotify($notify);
    }

     public function showRemoveFamilyNFTForm($id)
{
    $pageTitle = 'Remove FamilyNFT';

    // Retrieve the user and their NFTs
    $user = User::findOrFail($id);
    $familyNFTs = RentNFT::where('user_id', $id)->get();

    // Calculate plans
    $activePlan = $familyNFTs->filter(fn($nft) => $nft->contract_expiry_date > date("Y-m-d"))->sum('rented_nft');
    $expiredPlan = $familyNFTs->filter(fn($nft) => $nft->contract_expiry_date <= date("Y-m-d"))->sum('rented_nft');

    // Total NFTs and rented NFT sum
    $total = $familyNFTs->sum('rented_nft');
    $totalFamilyNFTs = $familyNFTs->count();

    // Return the view with data
    return view('admin.users.remove_nft', compact('user', 'familyNFTs', 'totalFamilyNFTs', 'activePlan', 'expiredPlan', 'total', 'pageTitle'));
}

    public function updateAutoRenewal(Request $request)
    {
        $user = auth()->user();

        // Update the Auto Renewal flag for a specific NFT
        RentNFT::where('user_id', $user->id)
               ->where('id', $request->input('nftid'))
               ->update(['auto_renewal' => $request->input('auto_renewal')]);

        return response()->json(['status' => true, 'message' => 'Auto renewal updated successfully.']);
    }

    public function updateManualRenewal(Request $request)
    {

        // Find the specific RentNFT by ID
        $vitem = RentNFT::find($request->input('mnftid'));
        if ($vitem) {
            $general = gs();  // Global settings or rates
            $nft_amount = $vitem->rented_nft * 12; // Example: 12$ for renewal
            $nft_amount = ($nft_amount / $general->price_ft);

            // Check if the user has sufficient balance
            $user = User::find($vitem->user_id);
            if ($nft_amount <= $user->deposit_ft) {
                // Deduct the amount from the user's deposit
                $user->deposit_ft -= $nft_amount;
                $user->save();

                // Update the NFT contract expiry date
                $vitem->contract_expiry_date = date('Y-m-d', strtotime('+90 days')); // 90 days renewal
                $vitem->save();

                // Log the renewal transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $nft_amount,
                    'charge' => 0,
                    'trx_type' => '-',
                    'trx' => getTrx(),
                    'wallet_type' => 'Deposit Wallet',
                    'details' => 'Manual Renewal of FamilyNFT'
                ]);

                return response()->json(['status' => true, 'message' => 'Manual renewal successful.']);
            } else {
                return response()->json(['status' => false, 'message' => 'Insufficient Deposit Balance!']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'NFT not found!']);
        }
    }

    public function deleteNFTs(Request $request)
{
    $nftId = $request->input('nftId');
    $quantity = $request->input('quantity');

    $nft = RentNft::findOrFail($nftId);

    if ($quantity < 1 || $quantity > $nft->rented_nft) {
        return response()->json(['error' => 'Invalid quantity'], 400);
    }

    // Calculate the remaining NFTs
    $remaining = $nft->rented_nft - $quantity;

    if ($remaining <= 0) {
        // If all NFTs are deleted, remove the record
        $nft->delete();
        return response()->json(['success' => true, 'message' => 'All NFTs deleted, record removed.']);
    } else {
        // Otherwise, update the count
        $nft->rented_nft = $remaining;
        $nft->save();
        return response()->json(['success' => true, 'message' => 'NFTs deleted successfully.']);
    }
}


    public function addFamilyNFT(Request $request, $id)
    {
        $request->validate([
            'amount'      => 'required|integer|gt:0',
        ]);

        $user    = User::findOrFail($id);
        $parentUser = [];
        if(isset($user->ref_by) && !empty($user->ref_by)){
            $parentUser = User::find($user->ref_by);
        }
        $date = Carbon::now()->toDateTimeString();
        $nextProfitDate = Carbon::now()->addDays(9)->format('Y-m-d');
        $contractExpiryDate = Carbon::now()->addDays(89)->format('Y-m-d');
        if(!$user || $user->status == 0){
            $notify[] = ['warning', $user->username.' is banned or not found on data.'];
        }else{
            $notify[] = ['success', 'FamilyNFT added successfully to '.$user->username.' Account.'];    
        }

        $sumRentedNFT = RentNFT::where('user_id', $user->id)->sum('rented_nft');
        if(($request->amount+$sumRentedNFT)>6000){
            $notify[] = ['error', 'You can not rent more than 6000 NFTs'];
            return back()->withNotify($notify);        
        }
        
        $nft_amount=  $request->amount * 24;

        // if ($nft_amount > $user->deposit_wallet) {
        //     $notify[] = ['error', 'You do not have sufficient balance for deposit.'];
        //     return back()->withNotify($notify);
        // }

        $general = GeneralSetting::first();
        $user_nft = new RentNFT;
        $user_nft->one_nft_price = "24";
        $user_nft->ft_price = $general->price_ft;
        $user_nft->rented_nft = $request->amount;
        
        
        //$user->deposit_wallet= ($user->deposit_wallet-$nft_amount);
        $paymentMethod = 'Deposit Wallet';
            
        //$user->save();
        $user_nft->buying_date = $date;
        $user_nft->next_profit_date = $nextProfitDate;
        $user_nft->contract_expiry_date = $contractExpiryDate;
        $user_nft->user_id = $user->id ;
        //$user_nft->deducted_amount = $nft_amount ;
        //$user_nft->payment_method = $paymentMethod;
        $user_nft->save();


        $reward_amt = $request->amount;
        
        //REWARD $1 to the sponsor
        if($parentUser && !empty($parentUser)){
            User::where('id', $parentUser['id'])->update([
                'interest_wallet' => ($parentUser['interest_wallet']+($reward_amt)),
            ]);
        }
        


        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $request->amount;
        $transaction->charge       = 0;
        $transaction->post_balance = 0;
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx;
        $transaction->remark       = 'FamilyNFT';
        $transaction->wallet_type  = 'reward_cubes';
        $transaction->details      = 'Super admin adds FamilyNFT to account';
        $transaction->save();


        if($parentUser && !empty($parentUser)){

            //Added 1$ to sponser  // paid by company 
            $transaction               = new Transaction();
            $transaction->user_id      = $parentUser['id'];
            $transaction->amount       = $reward_amt;
            $transaction->charge       = 0;
            $transaction->post_balance = ($parentUser['interest_wallet']);
            $transaction->trx_type     = '+';
            $transaction->trx          = getTrx();
            $transaction->remark       = 'referral bonus';
            $transaction->wallet_type  = 'interest_wallet';
            $transaction->details      = showAmount($reward_amt) . ' ' . $general->cur_text . ' referral bonus transferred ';
            $transaction->save();    
        }


        return back()->withNotify($notify);
    }

    public function datacheckUsers(Request $request){
        $from = $request->from?$request->from:1;
        $to = $request->to?$request->to:60000;

        $old = ini_set('memory_limit', '8192M');
        ini_set('max_execution_time', 1000);
        $filename = public_path('users.csv');
        
        \Log::info('User Ref Import started');

        $file = fopen($filename, "r");
        $filerow  = $exist = 0;
        $all_data_checking = array();
        $all_data = array();
        while ( ($data = fgetcsv($file, 0, ",")) !==FALSE) {
            if($filerow >= $from && $filerow <= $to && isset($data[5]) && $data[5] != 'NULL' && $data[20] && $data[20] != 'NULL'){
                //if($data[20] != 'NULL'){
                    $all_data[$data[0]]=['ref_by' => $data[20],'email' => $data[5],'mobile' => str_replace('+','',$data[3])];
                    $exist++;
                //}
                //\Log::info($data[0].'-'.$data[20].'-'.$data[5]);
            }
            $all_data_checking[$data[0]]=['ref_by' => $data[20],'email' => $data[5],'mobile' => str_replace('+','',$data[3])];
            $filerow ++;
        }

        //dd($exist);

        // $our_user_custom = [];
        // $Our_users = User::whereNotNull('email')->select('id','ref_by','email')->get()->toArray();
        // foreach ($Our_users as $kou=>$vou){
        //     $our_user_custom[$vou['email']] = $vou;
        // }
        //dd($our_user_custom['hiteshr.knp@gmail.com']);

        if($all_data){
            foreach ($all_data as $kk=>$vv){
                if($vv['email']){
                    if($vv['ref_by'] != 'NULL'){
                        $single =  User::where('email',$vv['email'])->where('mobile',$vv['mobile'])->select('id','ref_by','email')->first();
                        if($single && !empty($single)){
                            $recid= $single['id'];
                            //if($single['ref_by'] == 0 || $single['ref_by'] == NULL || is_null($single['ref_by'])){
                                if(isset($all_data_checking[$vv['ref_by']]) && isset($all_data_checking[$vv['ref_by']]['email'])){
                                    // $single_ref = isset($our_user_custom[$all_data_checking[$vv['ref_by']]['email']])?$our_user_custom[$all_data_checking[$vv['ref_by']]['email']]:[];
                                    $single_ref = User::where('email',$all_data_checking[$vv['ref_by']]['email'])->select('id','ref_by','email')->first();
                                    if($single_ref && !empty($single_ref)){
                                        $ref_id= $single_ref['id'];
                                        if($single['ref_by'] != $ref_id){
                                            User::where('id', $recid)->update([
                                                'ref_by' => $ref_id,
                                            ]);
                                            \Log::info($recid.'-'.$ref_id);
                                        }
                                        
                                    }

                                }
                            //}
                        }
                    }
                }
            }
        }

        \Log::info('User Ref Import ended');
  
        echo 'done';
        
    }



    public function launchNFTOwner1(Request $request){
        $from = $request->from?$request->from:1;
        $to = $request->to?$request->to:60000;
        $old = ini_set('memory_limit', '8192M');
        ini_set('max_execution_time', 1000);

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        //$spreadsheet = $reader->load(public_path('LaunchNFTALL.xlsx'));
        $spreadsheet = $reader->load(public_path('latestLaunchNFT.xlsx'));
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        
        \Log::info('Launch NFT 1st Import started');

        $our_user_custom = [];
        $Our_users = User::whereNotNull('email')->select('id','ref_by','email')->where('status','1')->get()->toArray();
        foreach ($Our_users as $kou=>$vou){
            $our_user_custom[strtolower($vou['email'])] = $vou;
        }

        $filerow  = $exist = $total_exist_email = 0;
        $all_data_checking = array();
        $all_data = array();
        DB::beginTransaction();
        foreach ($sheet->getRowIterator() as $data) {
            $cells = iterator_to_array($data->getCellIterator("A", "B"));
            $email = $cells["B"]->getValue();
            if($filerow >= $from && $filerow <= $to && isset($email) && $email != 'NULL'){
                $email = strtolower(trim($email));
                $all_data[$email]=['email' => $email];
                $exist++;
                
                if(isset($email) && array_key_exists($email,$our_user_custom)){
                    $total_exist_email ++;
                    $exist_user_id = $our_user_custom[$email]['id'];
                    User::where('id', $exist_user_id)->where('launch_nft_owner', 0)->update([
                        'launch_nft_owner' => 1,
                    ]);
                }
            }
            
            $all_data_checking[$email]=['email' => $email];
            $filerow ++;
        }
        DB::commit();

        
        \Log::info('Launch NFT 1st Import ended');
        
        echo 'done';
        dd($total_exist_email);
        dd($all_data);
        
    }
}