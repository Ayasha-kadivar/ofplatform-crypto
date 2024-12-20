<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HASHID;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HASHidController extends Controller
{
    public function index()
    {
        $pageTitle   = 'HASHID List';

        $hashids = \DB::table('payment_transaction_hash_id')->leftJoin('users',function($join){
            $join->on('payment_transaction_hash_id.user_id', '=', 'users.id')
            ->where('payment_transaction_hash_id.added_by','=','user');
        })->leftJoin('admins',function($join){
            $join->on('payment_transaction_hash_id.user_id', '=', 'admins.id')
            ->where('payment_transaction_hash_id.added_by','=','admin');
        })->leftJoin('users as reflect',function($join){
            $join->on('payment_transaction_hash_id.reflect_user_id', '=', 'reflect.id');
        })->orderBy('payment_transaction_hash_id.id', 'desc');
        
        $request = request();
        //dd($request->all());
        if($request->search || $request->date){
            if($request->search){
                $hashids = $hashids->where('users.username', $request->search)->orWhere('payment_transaction_hash_id.hash_id', $request->search)->orWhere('users.email', $request->search)->orWhere('admins.username',$request->search)->orWhere('admins.email', $request->search)->orWhere('reflect.username',$request->search)->orWhere('reflect.email', $request->search);
            }
            if($request->date){
                $newarr=explode('-',$request->date);
                $sd = $ed = '';
                if(count($newarr) > 0){
                    $sd = isset($newarr[0])?trim($newarr[0]):'';
                    $ed = isset($newarr[1])?trim($newarr[1]):'';
                }
                if($sd || $ed){
                    if($sd){
                        $date = \DateTime::createFromFormat('m/d/Y', $sd);
                        $sd = $date->format('Y-m-d');
                    }

                    if($ed){
                        $date = \DateTime::createFromFormat('m/d/Y', $ed);
                        $ed = $date->format('Y-m-d');
                    }
                }
                if($sd && $ed){
                    $hashids = $hashids->whereBetween('payment_transaction_hash_id.created_at', [$sd, $ed]);
                }else if($sd){
                    $hashids = $hashids->whereDate('payment_transaction_hash_id.created_at', $sd);
                }
            }
            
        }
        //$hashids = $hashids->select('payment_transaction_hash_id.*','users.username','users.email','admins.username as a_username','admins.email as a_email','reflect.username as r_username','reflect.email as r_email')->paginate(getPaginate());

        $hashids = $hashids->selectRaw('payment_transaction_hash_id.*,users.username,users.email,admins.username as a_username,admins.email as a_email,reflect.username as r_username,reflect.email as r_email')->paginate(getPaginate());

        return view('admin.hashid.index', compact('pageTitle', 'hashids'));
    }

    public function create()
    {
        $pageTitle = 'New Hashid Create';
        return view('admin.hashid.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validation = [
            'trx_type' => 'required',
            'user_id'  => 'required',
            'amount'   => 'required|integer|min:10|max:200|in:10,20,200',
            'hash_id'  => [
                function ($attribute, $value, $fail) use ($request) {
                    // Validate the pattern (66 alphanumeric characters)
                    if (!preg_match('/^[a-zA-Z0-9]{64,66}$/', $value)) {
                        $fail('HASH ID not valid!');
                    }
                },
            ],
            'remark'   => 'required',
        ];

        $request->validate($validation);
        
        $is_duplicate = 0;
        if(isset($request->is_duplicate) && $request->is_duplicate == 'on'){
            $p_t_h_id = \DB::table('payment_transaction_hash_id')
            ->where('hash_id', $request->hash_id)
            ->exists();
            
            if($p_t_h_id){
                $is_duplicate = 1;
            }
        }else{
            $check_fee_hash_id_exists = checkHashPayment($request->hash_id);
            if(!$check_fee_hash_id_exists){
                $notify[] = ['error', 'HASH ID you have submitted is already in use!'];
                return redirect()->back()->withNotify($notify);
            }
        }

        $check_user = User::where('id',$request->user_id)->first();
        if($request->trx_type == 'vip_membership'){
            // if($check_user->vip_user == 1){
            //     $notify[] = ['error', 'It is already VIP user till '.$check_user->vip_user_date.'.'];
            //     return redirect()->back()->withNotify($notify);
            // }

            // if($check_user->fee_status == 0){
            //     $notify[] = ['error', 'Please pay maintenance fees first.'];
            //     return redirect()->back()->withNotify($notify);
            // }

            if($request->amount != '20' && $request->amount != '200'){
                $notify[] = ['error', 'VIP fees required 20$(per month) OR 200$(per year) amount.'];
                return redirect()->back()->withNotify($notify);
            }


            if($check_user->vip_user_date > date("Y-m-d")){
                if($request->amount == 20){
                    $Edate = Carbon::parse($check_user->vip_user_date)->addDays(30)->format('Y-m-d');
                }else{
                    $Edate = Carbon::parse($check_user->vip_user_date)->addDays(365)->format('Y-m-d');
                }
            }else{
                if($request->amount == 20){
                    $Edate = Carbon::now()->addDays(30)->format('Y-m-d');
                }else{
                    $Edate = Carbon::now()->addDays(365)->format('Y-m-d');
                }
            }
            
            // $Edate = Carbon::now()->addDays(30);

            // if($request->amount == 200){
            //     $Edate = Carbon::now()->addDays(365);
            // }
            $next_eee = isset($request->next_e)?$request->next_e:$Edate;
            $check_user->vip_user_date=$next_eee;
            $check_user->vip_fee_hash=$request->hash_id;
            $check_user->vip_membership_amount=$request->amount;
            $check_user->vip_user=1;
            $check_user->save();

        }else{
            // if($check_user->fee_status == 2){
            //     $notify[] = ['error', 'Maintenance fees already paid valid till '.$check_user->maintenance_expiration_date.'.'];
            //     return redirect()->back()->withNotify($notify);
            // }
            // if($check_user->fee_status == 1){
            //     $notify[] = ['error', 'Maintenance fees verification pending.'];
            //     return redirect()->back()->withNotify($notify);
            // }
            if($request->amount != '10'){
                $notify[] = ['error', 'Maintenance fees required 10$ amount.'];
                return redirect()->back()->withNotify($notify);
            }
            
            if($check_user->maintenance_expiration_date  > date("Y-m-d")){
                $Edate = Carbon::parse($check_user->maintenance_expiration_date)->addDays(365)->format('Y-m-d');
            }else{
                $Edate = Carbon::now()->addDays(365)->format('Y-m-d');
            }                
            $next_eee = isset($request->next_e)?$request->next_e:$Edate;
            
            // $Edate = Carbon::now()->addDays(365);
            $check_user->maintenance_note=$request->remark;
            $check_user->maintenance_expiration_date=$next_eee;
            $check_user->maintenance_fee_hash=$request->hash_id;
            $check_user->maintenance_fees_type=0;
            $check_user->fee_status=2;
            $check_user->save();
        }

        $hashid                  = new HASHID();
        $hashid->user_id         = auth('admin')->user()->id;
        $hashid->reflect_user_id = $request->user_id;
        $hashid->added_by        = 'admin';
        $hashid->amount          = $request->amount;
        $hashid->trx_type        = $request->trx_type;
        $hashid->hash_id         = $request->hash_id;
        $hashid->remark          = $request->remark;
        $hashid->save();

        $notify[] = ['success', 'HASH ID added successfully'];
        return to_route('admin.hashid.index')->withNotify($notify);
    }
  
}
