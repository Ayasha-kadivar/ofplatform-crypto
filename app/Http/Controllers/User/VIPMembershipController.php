<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\HyipLab;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\RequestPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\Http;

class VIPMembershipController extends Controller
{

    public function vipmembership()
    {
        $pageTitle = 'VIP Membership';
        $userId = auth()->user()->id;
        $user_pending_req = RequestPayment::where('user_id',$userId)->where('status',0)->count();
        return view($this->activeTemplate . 'user.vipmembership.form', compact('pageTitle','user_pending_req'));
    }

    public function vipInsert(Request $request)
    {
        $rules = [
            'membership_amount' => 'required|integer|min:20|max:200|in:20,200'
        ];
        if($request->input('maintenance_fees_type')==0){
            $rules['hash_id'] = 'required|alpha_num|size:66|regex:/^[a-fA-F0-9xX ]+$/';
        }else{
            $rules['hash_id'] = 'required|alpha_num|size:64|regex:/^[a-fA-F0-9 ]+$/';
        }
        $request->validate($rules);
        $userId = auth()->user()->id;
        $feeHash = $request->hash_id;
        $fees_type = $request->maintenance_fees_type;
        $user = User::find($userId);

        if($user->maintenance_expiration_date < date("Y-m-d")){
            $notify[] = ['error', 'Please pay maintenance fees first.'];
            //return redirect()->back()->withNotify($notify);
            return to_route('user.maintenance-fee')->withNotify($notify);
        }

        if(strlen($feeHash) != 64 && strlen($feeHash) != 66){
            $notify[] = ['error', 'Transaction HASH ID must contain 64 or 66 alphanumeric characters! Please check again and resubmit compliant HASH ID!'];
            return redirect()->back()->withNotify($notify);
        }

        // Check uniqueness in the deposits table
        $depositExists = checkHashPayment($feeHash);
        if (!$depositExists) {
            $notify[] = ['error', 'Transaction HASH ID already exist in our database! Open support ticket or send e-mail to issues@ourfamily.support !'];
            return redirect()->back()->withNotify($notify);
        }

        $rp = new RequestPayment;
        $rp->user_id = $userId;
        $rp->amount = $request->membership_amount;
        $rp->trx_type = 'vip_membership';
        $rp->hash_id = $feeHash;
        $rp->fees_type = $fees_type;
        $rp->save();

        // if($user->vip_user_date != '' && $user->vip_user_date > date('Y-m-d') ){
        //     $ma = ($request->membership_amount / 20); // per month 20$
        //     $date_c = Carbon::createFromFormat('Y-m-d', $user->vip_user_date);
        //     $dateofex = $date_c->addDays(365);
        //     if($ma != 10){
        //         $date_c = Carbon::createFromFormat('Y-m-d', $user->vip_user_date);
        //         $dateofex = $date_c->addDays(30);
        //     }
        // }else{
        //     $ma = ($request->membership_amount / 20); // per month 20$
        //     $dateofex = date('Y-m-d', strtotime("+365 days"));
        //     if($ma != 10){
        //         $dateofex = date('Y-m-d', strtotime("+30 days"));
        //     }
        // }
         
        $arr=[
            "user_id"=>$userId,
            "reflect_user_id" => $userId,
            "amount"=>$request->membership_amount,
            "trx_type"=>'vip_membership',
            "hash_id"=>$feeHash,
            "remark"=>'user manually renewal membership',
            "created_at"=>Carbon::now()
        ];
                                        
        $t_hashid = \DB::table('payment_transaction_hash_id')->insertGetId($arr);
        
        // $user->vip_user = 1;
        // $user->vip_fee_hash = $t_hashid;
        // $user->vip_membership_amount = $request->membership_amount;
        // $next_due_date = $dateofex;
        // $user->vip_user_date = $next_due_date;
        // $user->save();
        $notify[] = ['success', ' HASH ID successfuly sent, Kindly wait for Admin approval!'];
        return redirect()->back()->withNotify($notify);
    }

}
