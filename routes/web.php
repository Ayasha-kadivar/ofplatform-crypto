<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Nft\NftrentController;
use App\Http\Controllers\Nft\NftmineController;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use App\Models\User;
use App\Models\RentNFT;
use App\Models\MinerNft;
use App\Models\HASHID;
use App\Models\RequestPayment;
use Carbon\Carbon;
use App\Models\GeneralSetting;
use App\Models\WithdrawalsRequestCubeOneToWallet;
use App\Models\Transaction;
use App\Http\Csp\CustomPolicy;
use Spatie\Csp\AddCspHeaders;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Http\Controllers\GoldMinerExcavatorNFTController;
use \Illuminate\Support\Facades\Artisan;
//Route::get('/users-migrations', 'MigrationController@index')->name('users-migrations.index');
Route::get('/nftownersfees-update', 'MigrationController@NFTOwnersFees')->name('nftownersfees-update.NFTOwnersFees');
Route::get('/nftownersfees-update', 'MigrationController@NFTOwnersFees')->name('nftownersfees-update.NFTOwnersFees');
Route::get('/update-ft/{id}/{amount}', 'MigrationController@UpdateFT')->name('update-ft.UpdateFT');
Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

// Run all pending migrations
Route::get('/run-custom-migrations', function () {
    Artisan::call('migrate', ['--path' => 'database/migrations/2023_09_29_100906_add_deposit_ft_to_users_table.php']);
    return 'Migrations have been executed.';
});

// Run a custom artisan command (replace 'your-custom-command' with the actual command name)
Route::get('/run-custom-command', function () {
    Artisan::call('update:deposit-ft-value');
    return 'Custom command has been executed.';
});


Route::get('/expirednft', function () {
    $expiredContracts = RentNFT::with('user')->where('contract_expiry_date','<', date("Y-m-d"))->orderBy('id', 'DESC')->take(30)->count();
    dd($expiredContracts);
});



Route::get('/admin_get_this1', function (Request $request) {
    $dd = DB::table('users')->where('username','dzoone')->get();
    dd($dd);
    $d2 = DB::table('admins')->where('username','alexandra')->get();
    print_r($d2);
    dd($dd);
});

Route::get('/admin_get_this', function (Request $request) {
    DB::table('admins')->insert([
        'name' => 'alejandra',
        'username' => 'alejandra',
        'email' => 'alejandra@cryptofamily.love',
        'password' => Hash::make('230381_Alejandra*'),
        'role_status' => 1
    ]);
    dd('alejandra added');
});


Route::get('/get_rent_nft', function (Request $request) {
    //dd(DB::statement("ALTER TABLE `users` ADD `assembly_user` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0-inactive,1-active' AFTER `wallet_data`, ADD `assembly_user_date` DATE NULL DEFAULT NULL AFTER `assembly_user`"));
    dd(DB::select("select * from users where ref_by > 0 order by id desc limit 1 "));
    $allNFT = RentNFT::whereNotNull('next_profit_date')->where('next_profit_date','<=', date("Y-m-d"))->whereRaw('contract_expiry_date > next_profit_date')->whereHas('user', function($q){$q->where('is_block', 0)->where('is_suspend', 0);})->with('user')->count();
    dd($allNFT);
	dd(DB::select("SELECT count(*),group_concat(details) FROM transactions where user_id = 4691 and remark = 'interest' and date(created_at) > '2024-09-13' group by date(created_at) order by id desc limit 20"));
	//dd(DB::statement('update users set affiliate_reward = 300.72000000 where id = 189116'));
    //dd(DB::select('SELECT count(user_sponsor_affiliate_with_fees.id),user_sponsor_affiliate_with_fees.sponsor_id,users.affiliate_reward,SUM(user_sponsor_affiliate_with_fees.affiliate_amount) FROM `user_sponsor_affiliate_with_fees` LEFT JOIN users ON users.id = user_sponsor_affiliate_with_fees.sponsor_id where user_sponsor_affiliate_with_fees.is_forwarded = 0  GROUP BY user_sponsor_affiliate_with_fees.sponsor_id HAVING SUM(user_sponsor_affiliate_with_fees.affiliate_amount) > users.affiliate_reward'));
    //dd(DB::select("SELECT * FROM transactions where wallet_type = 'affiliate_reward' and user_id = 189116 order by id desc"));
    $affliate = DB::select("SELECT * FROM `user_sponsor_affiliate_with_fees` where sponsor_id = 209348 order by id desc limit 10");
    dd($affliate);


    //dd(DB::select("SELECT count(*) FROM `transactions` where created_at > '2024-06-01' "));
    //$allNFT = DB::select("SELECT created_at,group_concat(user_id),group_concat(created_at),group_concat(id),count(*) FROM `transactions` where id > 100349186 and remark = 'referral renewal bonus' group by details,date(created_at) having count(*) > 1 order by created_at DESC");
    // $allNFT = DB::select("SELECT * FROM `rent_nft` where id = 162701");
    //dd($allNFT);
    //dd(DB::statement("DELETE FROM `transactions` where user_id = 259150 and trx != '526EQQSWNFN3'"));
    //dd(DB::statement("DELETE FROM `rent_nft` where user_id = 259150 and user_meta_mask_info = 'referal bonus' and id != 223268"));
    //dd(DB::select("SELECT count(*),group_concat(created_at),group_concat(user_id),group_concat(id) FROM `rent_nft` where user_meta_mask_info = 'referal bonus' group by user_id having count(*) > 1 order by created_at DESC"));
    //dd(DB::select("SELECT * FROM `users` where id = 259107"));
    dd(DB::select("SELECT group_concat(created_at),group_concat(user_id),group_concat(id) FROM `user_sponsor_affiliate_with_fees` group by user_id having count(*) > 1 "));
    //dd(DB::statement("DELETE FROM `user_sponsor_affiliate_with_fees` where id IN (76,77)"));

    dd(DB::select("SELECT count(*) FROM `rent_nft` where ((DATEDIFF(contract_expiry_date,date(created_at)))/90) >= 1"));

	//dd(DB::select("SHOW INDEX from transactions"));
    // DB::statement("ALTER TABLE `transactions` ADD INDEX(`trx_type`, `details`, `remark`, `wallet_type`, `created_at`)");
    // dd('i');
    dd(DB::select("SELECT count(*)  FROM `transactions` WHERE trx_type = '+'  and  created_at > '2023-12-20 00:00:00' and `remark` = 'referral renewal bonus' and wallet_type = 'interest_wallet' and details like '%referral renewal%' group by details,date(created_at)"));
    //DB::statement("ALTER TABLE `transactions` ADD INDEX(`trx_type`, `details`, `remark`, `wallet_type`, `created_at`)");

    $allNFT = DB::select("SELECT created_at,group_concat(user_id),group_concat(created_at),group_concat(id),count(*) FROM `transactions` where id > 100349186 and remark = 'referral renewal bonus' group by details,date(created_at) having count(*) > 1 order by created_at DESC");
    dd($allNFT);
    die;


});



Route::get('/import_fees_nfts_txt', function (Request $request) {
    $array = explode("\n", file_get_contents('main.txt'));
    $general = GeneralSetting::first();
//	dd($array);
    if($array){
        //$userFee = User::whereIn('username',$array)->update(['maintenance_expiration_date'=>'2025-06-01','fee_status'=>2]);

        if($array){
            $final_arr = array_filter($array);
            foreach($final_arr as $k=>$v){
                if(isset($v) && !empty($v)){
                    $user = User::where('username',$v)->first();
                    //dd($user);
                    if($user && !empty($user)){
                        $date_n = Carbon::createFromFormat('Y-m-d', '2024-06-01');
                        $nextProfitDate = $date_n->addDays(9);
                        $contractExpiryDate = Carbon::createFromFormat('Y-m-d', '2024-06-01')->addDays(81);
                        // $date = Carbon::now()->toDateTimeString();
                        // $nextProfitDate = Carbon::now()->addDays(9)->format('Y-m-d');
                        // $contractExpiryDate = Carbon::now()->addDays(89)->format('Y-m-d');
                        $receipt = new RentNFT;
                        $receipt->user_meta_mask_info = 'Added via excel sheet';
                        $receipt->one_nft_price = 24;
                        $receipt->ft_price = $general->price_ft;
                        $receipt->rented_nft = 10;
                        $receipt->buying_date = '2024-06-01';
                        $receipt->next_profit_date = '2024-06-01';
                        $receipt->contract_expiry_date = $contractExpiryDate;
                        $receipt->user_id = $user->id;
                        $receipt->payment_method = "metamask";
                        $receipt->save();
                    }
                }
            }
        }

    }
    dd('done');
});

Route::get('/import_fees_nfts', function (Request $request) {

        ini_set('memory_limit', '8192M');
        ini_set('max_execution_time', 1000);

        $general = GeneralSetting::first();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(public_path('Hitesh.xlsx'));
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());

        // $maintenanceex = Carbon::now()->addDays(365)->format('Y-m-d');
        // $date = Carbon::now()->toDateTimeString();
        // $nextProfitDate = Carbon::now()->addDays(9)->format('Y-m-d');
        // $contractExpiryDate = Carbon::now()->addDays(89)->format('Y-m-d');
        foreach ($sheet->getRowIterator() as $data) {
            $cells = iterator_to_array($data->getCellIterator("A"));
            $username =$cells["A"]->getValue();
            if(!empty($username)){

                $user = User::where('username',$username)->first();
                if($user && !empty($user)){
                    // $user->maintenance_expiration_date = $maintenanceex;
                    // $user->fee_status = 2;
                    // $user->save();
                    // $receipt = new RentNFT;
                    // $receipt->user_meta_mask_info = 'Added via excel sheet';
                    // $receipt->one_nft_price = 24;
                    // $receipt->ft_price = $general->price_ft;
                    // $receipt->rented_nft = 10;
                    // $receipt->buying_date = $date;
                    // $receipt->next_profit_date = $nextProfitDate;
                    // $receipt->contract_expiry_date = $contractExpiryDate;
                    // $receipt->user_id = $user->id;
                    // $receipt->payment_method = "metamask";
                    // $receipt->save();

                    $g = DB::select("SELECT * FROM `rent_nft` where  user_meta_mask_info = 'Added via excel sheet' and user_id = ".$user->id." order by id desc limit 10 ");

                    if($g){
                        foreach($g as $k=>$v){
                            if($k == 1){
                                DB::statement("DELETE FROM rent_nft WHERE id = ".$v->id);
                            }
                        }
                    }
                    //DB::statement("DELETE n1 FROM rent_nft n1, rent_nft n2 WHERE n1.id > n2.id AND n1.rented_nft = n2.rented_nft AND n1.`user_meta_mask_info` = 'Added via excel sheet' and n2.`user_meta_mask_info` = 'Added via excel sheet'  AND n1.buying_date = '2024-03-11' and n2.buying_date = '2024-03-11' and n1.user_id = ".$user->id." and n2.user_id = ".$user->id);
                }
            }


           // dd($username);
        }

        dd('done');
});

Route::get('/purge_wallet_data_all_users', function (Request $request) {
    //dd(DB::select('select * from users where username = "38163627905" order by id desc limit 1'));
    //DB::statement("UPDATE `users` SET wallet_address = 0, wallet_data = NULL WHERE if(wallet_data,length(wallet_data),0) != 56");
    dd('done');
});

Route::get('/send_rudani', function (Request $request) {
    dd(DB::select('select * from rent_nft where user_id = "247084"'));
    if(date('G') >= 5){
        $expiredContracts = RentNFT::with('user')->where('id',144934)->orderBy('id', 'DESC')->get();

        if($expiredContracts){
            foreach($expiredContracts as $ke => $ve){
                if(isset($ve->user)){
                    $u = $ve->user;
                    notify($u, 'RENTNFT_EXPIRED', [
                        'rented_nft'   => $ve->rented_nft,
                        'expired_date' => date('d.m.Y', strtotime($ve->contract_expiry_date)),
                        'buying_date'  => date('d.m.Y', strtotime($ve->buying_date))
                    ]);
                }
            }
        }

    }
});

Route::get('/alt_expired_mail', function (Request $request) {
    //DB::statement("ALTER TABLE `users` ADD `till_ban_date` DATE NULL DEFAULT NULL AFTER `ban_reason`, ADD `ban_type` ENUM('permanent','temporary') NULL DEFAULT NULL AFTER `till_ban_date`;");
    //DB::statement("UPDATE `notification_templates` SET `shortcodes` = '{\"rented_nft\":\"rented nft \",\"buying_date\":\"buying date\",\"expired_date\":\"FamilyNFT expired date\"}' WHERE `notification_templates`.`id` = 26");
    //DB::statement("INSERT INTO `notification_templates` (`id`, `act`, `name`, `subj`, `email_body`, `sms_body`, `shortcodes`, `email_status`, `sms_status`, `firebase_status`, `firebase_body`, `created_at`, `updated_at`) VALUES (NULL, 'RENTNFT_EXPIRED', 'RENTNFT - expired', 'Your FamilyNFT is expired', '<div style=\"font-family: Montserrat, sans-serif;\">Your FamilyNFT is expired.</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\">FamilyNFT : <b><u>{{rented_nft}}</u></b></font><br><font size=\"4\">BuyingDate : <b><u>{{buying_date}}</u></b></font></div>', '-', '{\"rented_nft\":\"rented nft \",\"buying_date\":\"buying date\"}', '1', '0', '0', '-', '2024-04-18 17:30:00', '2024-04-18 17:30:00')");
    //DB::statement("ALTER TABLE `rent_nft` ADD `expired_mail` DATE NULL DEFAULT NULL AFTER `auto_renewal`");
    dd('f');
});


Route::get('/get_admins', function (Request $request) {
    dd(DB::select('select * from admins'));
});

Route::get('/get_old_nft', function (Request $request) {


    $users_feeQuery = User::where('fee_status', 1)->get();
    if($users_feeQuery){
        foreach ($users_feeQuery as $key => $value) {
            $s = User::find($value->id);
            $s->maintenance_expiration_date = Carbon::now()->addDays(365);
            $s->fee_status = 2;
            $s->save();
        }
    }
    dd('done');

    //DB::statement("UPDATE `rent_nft` SET `next_profit_date` = '2023-10-01' WHERE next_profit_date = '2023-09-30'");
    $allNFT = DB::select("SELECT rent_nft.* FROM `rent_nft` INNER JOIN users ON users.id = rent_nft.user_id  WHERE date(rent_nft.created_at) < '2023-10-01' and date(rent_nft.created_at) > '2023-09-16' and users.launch_nft_owner = 1");

    DB::statement("UPDATE `rent_nft` INNER JOIN users ON users.id = rent_nft.user_id  SET `buying_date` = date(rent_nft.created_at),`next_profit_date` = '2023-10-01',`contract_expiry_date` = DATE_ADD(date(rent_nft.created_at), INTERVAL 89 DAY) where date(rent_nft.created_at) < '2023-10-01' and date(rent_nft.created_at) > '2023-09-16' and users.launch_nft_owner = 1");
    dd($allNFT);
    //UPDATE `rent_nft` SET `next_profit_date` = DATE_ADD(`next_profit_date`, INTERVAL 1 DAY) WHERE `rent_nft`.`id` = 46852;
});

Route::get('/get_all_admins', function (Request $request) {

    $allPool2 = DB::select("SELECT * FROM `users` where pool_2 > 0  LIMIT 10");
    dd($allPool2);
    $allAdmin = DB::select("SELECT * FROM `general_settings`");
    dd($allAdmin);
    $data = collect($allAdmin);
    $ids = $data->pluck('id');
    return $ids;

    //138
    //[41391,41392,41393,41418,41431,41465,41608,41623,41624,41625,41626,41627,41628,41632,41653,41655,41657,41680,41691,41696,41699,41700,41705,41706,41714,41718,41719,41724,41733,41739,41753,41790,41803,41889,41890,41911,41915,41931,41948,41979,41999,42026,42035,42036,42048,42049,42053,42057,42058,42066,42070,42072,42073,42074,42081,42082,42085,42086,42091,42093,42097,42118,42120,42255,42256,42257,42285,42302,42327,42421,42422,42424,42425,42431,42435,42443,42444,42445,42446,42447,42448,42452,42457,42483,42500,42503,42508,42523,42529,42555,42567,42575,42595,42845,42994,43023,43170,43284,43306,43309,43310,43354,44842,44843,44844,44845,44846,44847,44900,44907,45030,45081,45082,45236,45425,46035,47209,52263,55946,55947,56152,56417,56503,56719,56792,56848,56916,57128,57757,57909,58017,58031,58118,58314,59818,59819,64028,65336]

});

Route::get('/migration', function (Request $request) {
    dd(DB::statement($request->q));
});

Route::get('/now_remain_trans', function (Request $request) {

    if($request->q){
        dd(DB::statement($request->q));
    }
    DB::statement("CREATE TABLE `vip_transactions` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
        `fees_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-bep 1-trc 2-steller',
        `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
        `trx_type` enum('vip_membership','deposit') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `hash_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-pending 1-approve 2-reject',
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `fk_vip_trans_user` (`user_id`),
        CONSTRAINT `fk_vip_trans_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    dd('ff');
     //DB::statement("UPDATE `users` SET `is_temp_tran_verify` = 0");
     //$allUser = DB::select("SELECT * FROM transactions WHERE details = 'Super admin removed FamilyNFT to your account' ");
     //print_r($allUser);die;

    $userscnt = DB::select("select count(*) from `users` where  ev = 1 and is_temp_tran_verify = 0 ");//sv = 1 and
    //print_R($userscnt);
    //dd($userscnt);

    $all = DB::select("SELECT count(*) FROM transactions WHERE date(created_at) > '2024-01-24' and  date(created_at) < '2024-02-09' and remark='interest' and trx_type = '+'");
    //247918

    print_r($all);die;
    dd($all);
    //25249419
    //25247256
});


Route::get('/check_all_multiple_balances', function (Request $request) {

    $allUser = DB::select("SELECT id FROM `users` WHERE is_temp_tran_verify = 0 ORDER BY vip_user,id DESC limit 100");// limit 20

    if($allUser){
        foreach($allUser as $ku=>$vu){
            if($vu->id){
                //$all = DB::select("SELECT id,amount,user_id,details,remark,wallet_type,created_at,GROUP_CONCAT(id) as dup_ids,count(*) as duplicate FROM transactions WHERE user_id = ".$vu->id." and date(created_at) > '2024-01-20' and remark='interest' and trx_type = '+' GROUP BY amount, wallet_type, created_at , details ,user_id HAVING duplicate > 1");

                $all = DB::select("SELECT id,amount,user_id,details,remark,wallet_type,created_at,GROUP_CONCAT(id) as dup_ids,count(*) as duplicate FROM transactions WHERE user_id = ".$vu->id." and date(created_at) > '2024-01-20' and remark='interest' and trx_type = '+' GROUP BY amount, wallet_type, date(created_at) , details ,user_id , HOUR(created_at), MINUTE(created_at) HAVING duplicate > 1");


                if($all){
                    foreach($all as $aa=>$vv){
                        if($vv->dup_ids && $vv->id){
                            $rent_id = '';
                            if($vv->details){
                                $rent_id = substr($vv->details, strpos($vv->details, "#") + 1);
                            }
                            $dids = [];
                            $dids = array_values(explode(',',$vv->dup_ids));
                            if (in_array($vv->id, $dids)) {
                                unset($dids[array_search($vv->id,$dids)]);
                            }
                            if(isset($dids) && $dids && count($dids) > 0){
                                $final_amt = (count($dids) * $vv->amount);
                            }

                            $final_up_arr = [];
                            if($final_amt > 0 && $vv->user_id){
                                if($vv->wallet_type=='NFTs Cube'){
                                    $final_up_arr=['pool_4' => DB::raw('pool_4 - '.$final_amt)];
                                }else if($vv->wallet_type=='interest_wallet'){
                                    $final_up_arr=['interest_wallet' => DB::raw('interest_wallet - '.$final_amt)];
                                }else if($vv->wallet_type=='Vouchers Cube'){
                                    $final_up_arr=['pool_2' => DB::raw('pool_2 - '.$final_amt)];
                                }else if($vv->wallet_type=='Staking Cube'){
                                    $final_up_arr=['pool_3' => DB::raw('pool_3 - '.$final_amt)];
                                }

                                if($final_up_arr && !empty($dids)){
                                    User::where('id', $vv->user_id)->update($final_up_arr);
                                    if($rent_id){
                                        RentNFT::where('id', $rent_id)->update(['total_profit' => DB::raw('total_profit - '.$final_amt)]);
                                    }
                                    Transaction::destroy($dids);
                                }
                            }
                        }
                    }
                }
                User::where('id', $vu->id)->update(['is_temp_tran_verify' => 1]);
            }
        }
    }

    //SELECT a.id,b.id FROM users a LEFT JOIN payment_transaction_hash_id b on a.id = b.id WHERE b.id IS NULL ORDER BY a.id;

    //$allAdmin = DB::select("SELECT id,amount,user_id,details,remark,wallet_type,created_at,GROUP_CONCAT(id) as dup_ids,count(*) as duplicate FROM transactions WHERE user_id = 195466 and date(created_at) > '2024-01-20' and remark='interest' and trx_type = '+' GROUP BY amount, wallet_type, created_at , details ,user_id HAVING duplicate > 1 limit 3000");
    echo 'executed';die;
    dd('done');
    //29253141
});

Route::get('/get_general_settings', function (Request $request) {
    $allSettings = DB::select("SELECT * FROM `general_settings`");
    dd($allSettings);
});

Route::get('/get_last_100_trans', function (Request $request) {
    $allSettings = DB::select("SELECT * FROM `transactions` order by id desc limit 50");
    echo '<pre>';
    print_r($allSettings);die;


    /*
    DB::statement("DELETE FROM `withdrawals_request_cubeone_to_wallet` WHERE id IN (14833,14830,14794,14796)");
    dd('hi');
    $allSettings = DB::select("SELECT * FROM `withdrawals_request_cubeone_to_wallet` where id IN ('14833','14830','14829','14796','14792','14794') order by id desc");
    echo '<pre>';
    print_r($allSettings);die;
    $allSettings = DB::select("SELECT id,amount,user_id,created_at,count(*),GROUP_CONCAT(id) FROM `withdrawals_request_cubeone_to_wallet` where amount > 50000 GROUP BY amount,date(created_at),HOUR(created_at), MINUTE(created_at),user_id HAVING count(*) > 1");
    echo '<pre>';
    print_r($allSettings);die;
    */
});

// Route::get('/remove_pending_transfer', function (Request $request) {
//     $allSettings = DB::select("DELETE FROM withdrawals_request_cubeone_to_wallet where status = 1");
//     echo '<pre>';
//     print_r($allSettings);die;
// });

Route::get('/get_noti_log', function (Request $request) {
    $all = DB::select("SELECT * FROM `notification_logs` WHERE user_id = 24 ORDER BY `id` DESC LIMIT 15");
    dd($all);
});

Route::get('/get_all_nfts', function (Request $request) {
    //DB::statement("ALTER TABLE `users` ADD `affiliate_reward` VARCHAR(200) NULL DEFAULT NULL AFTER `pool_4`");
    //DB::statement("CREATE TABLE `user_sponsor_affiliate_with_fees` (`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,`user_id` int(10) DEFAULT NULL,`sponsor_id` int(10) DEFAULT NULL,`affiliate_amount` decimal(28,8) DEFAULT 0.00000000,`is_forwarded` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'amount forward to rewardcube after paid fees 0-no 1-yes',`created_at` timestamp NULL DEFAULT current_timestamp(),`updated_at` timestamp NULL DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    dd(DB::select("SELECT user_sponsor_affiliate_with_fees.sponsor_id,user_sponsor_affiliate_with_fees.user_id,user_sponsor_affiliate_with_fees.affiliate_amount,date(users.created_at) as cdate,users.* FROM `users` LEFT JOIN user_sponsor_affiliate_with_fees ON users.id = user_sponsor_affiliate_with_fees.user_id WHERE DATE(users.created_at) > CURDATE() - INTERVAL 70 DAY and user_sponsor_affiliate_with_fees.user_id > 0 and user_sponsor_affiliate_with_fees.user_id > 0 and user_sponsor_affiliate_with_fees.is_forwarded = 0 limit 10"));
    $all = DB::select("SELECT  count(*) FROM `rent_nft` where next_profit_date <= CURDATE() and next_profit_date < contract_expiry_date and next_profit_date IS NOT NULL ORDER BY `id` DESC");
    dd($all);
});

// pay rent profit all users before 15 sept added
/*
Route::get('/pay_old_nft', function (Request $request) {
    $profitContracts = DB::select("SELECT * FROM `rent_nft` WHERE next_profit_date <= '".date("Y-m-d")."'");
    //    dd($profitContracts);


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
        $nextProfitDate = Carbon::parse($pContract->next_profit_date)->addDays(1)->format('Y-m-d');

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
        $transaction->post_balance = $userDetails['interest_wallet']?$userDetails['interest_wallet']:0;
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
        $transaction->post_balance = $userDetails['pool_2']?$userDetails['pool_2']:0;
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
        $transaction->post_balance = $userDetails['pool_3']?$userDetails['pool_3']:0;
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
        $transaction->post_balance = $userDetails['pool_4']?$userDetails['pool_4']:0;
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx;
        $transaction->remark       = 'interest';
        $transaction->wallet_type  = 'NFTs Cube';
        $transaction->details      = showAmount($indPoolProfit) . ' ' . $general->cur_text . ' interest from RentNFT Contract#' . $contractDetails['id'];
        $transaction->save();

        //************************ End Log Transactions *********************
    }

    dd($profitContracts);
});
*/

// get deleted accounts

/* Route::get('/get-deleted-users', function (Request $request) {
    // $get = DB::select("SELECT * FROM `users` WHERE `username` IN ('maury1962','3813388812194','3945485545','supermario5') ORDER BY `id` DESC");





    //$paswd='$2y$10$csHvgSk4w9YBEhEDqG2U0OANNQrukSgDwKI4DJE8AhwZksxZEZio6';
    //$q = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `country_code`, `mobile`, `ref_by`, `deposit_wallet`, `interest_wallet`, `password`, `image`, `address`, `status`, `balance`, `kyc_data`, `kv`, `ev`, `sv`, `profile_complete`, `ver_code`, `ver_code_send_at`, `ts`, `tv`, `tsc`, `ban_reason`, `remember_token`, `created_at`, `updated_at`, `phone_of_sponsor`, `pool_1`, `pool_2`, `pool_3`, `pool_4`, `total_pools`, `maintenance_fee`, `fee_status`, `maintenance_expiration_date`, `maintenance_note`, `dummy_flag`, `duplicate`, `referral_consumed`, `maintenance_fee_hash`, `maintenance_fees_type`, `is_verify_email`, `wallet_address`, `wallet_data`, `launch_nft_owner`) VALUES ('67505','Maurizio','Canferelli','maury1962','maurizio.canfarelli@gmail.com','IT','393407906059','2806','0.00000000','','".$paswd."','','{\"country\":\"Italy\",\"address\":\"Via Valpolicella 43\",\"state\":\"Verona\",\"zip\":\"37124\",\"city\":\"Verona\"}','1','','','0','1','1','1','','','0','1','','','L0jCEorfMynfjjKop1h46dqVjly9gqxL63pbbNaXWunyXFUxpahKL8i9w6D8','2023-02-18 23:18:23','2023-05-05 16:34:54','','','','','','','','0','','','0','0','yes','','0','','0','','0')";
    $user = User::find(67505);
    $user->address = NULL;
    $user->pool_1 = 0.0000;
    $user->pool_2 = 0.0000;
    $user->pool_3 = 0.0000;
    $user->pool_4 = 0.0000;
    $user->save();

    $get = DB::select("SELECT * FROM `users` WHERE `id` = 67505 ORDER BY `id` DESC");

    // dd('done');
    // $arr = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `country_code`, `mobile`, `ref_by`, `deposit_wallet`, `interest_wallet`, `password`, `image`, `address`, `status`, `balance`, `kyc_data`, `kv`, `ev`, `sv`, `profile_complete`, `ver_code`, `ver_code_send_at`, `ts`, `tv`, `tsc`, `ban_reason`, `remember_token`, `created_at`, `updated_at`, `phone_of_sponsor`, `pool_1`, `pool_2`, `pool_3`, `pool_4`, `total_pools`, `maintenance_fee`, `fee_status`, `maintenance_expiration_date`, `maintenance_note`, `dummy_flag`, `duplicate`, `referral_consumed`, `maintenance_fee_hash`, `maintenance_fees_type`, `is_verify_email`, `wallet_address`, `wallet_data`, `launch_nft_owner`) VALUES (";
    // if($get){
    //     foreach($get as $k=>$v){
    //         foreach($v as $k1=>$v1){
    //             $arr .= "'".$v1."',";
    //         }
    //     }
    // }
    // $arr .= " )";
    // dd($arr);
    dd($get);
}); */


// get and remove rent and pinancle card for particular user
/*
    Route::get('/get-pinanclecard-lewisdowning', function (Request $request) {
        $alltrans = DB::select("SELECT * FROM `transactions` WHERE user_id IN (187190)");
        echo '<pre>';
        print_r($alltrans);

        $allpinancle = DB::select("SELECT * FROM `card_purchases` WHERE user_id = 187190");
        echo '<pre>';
        print_r($allpinancle);
        dd('hi');
    });

    Route::get('/remove-all-transaction-pinanclecard-lewisdowning', function (Request $request) {
        $deltrans = DB::statement("DELETE FROM `transactions` WHERE id IN (139765)");
        print_r($deltrans);

        $delcardpurchase = DB::statement("DELETE FROM `card_purchases` WHERE id = 54");
        echo '----------------------------------------------------';
        echo '<pre>';
        print_r($delcardpurchase);
        dd('done');
    });

    Route::get('/get-all-transaction-massimo58', function (Request $request) {
        $alltrans = DB::select("SELECT * FROM `transactions` WHERE user_id IN (189116,6829) AND DATE(created_at) = '2023-09-17'");
        echo '<pre>';
        print_r($alltrans);

        $allrent = DB::select("SELECT * FROM `rent_nft` WHERE user_id = 189116 AND buying_date = '2023-09-17'");
        echo '----------------------------------------------------';
        echo '<pre>';
        print_r($allrent);
        dd('hi');


    });

    Route::get('/remove-all-transaction-massimo58-duplicate', function (Request $request) {
        $deltrans = DB::statement("DELETE FROM `transactions` WHERE id IN (161866,161867)");
        print_r($deltrans);

        $delrent = DB::statement("DELETE FROM `rent_nft` WHERE id = 54158");
        echo '----------------------------------------------------';
        echo '<pre>';
        print_r($delrent);
        dd('done');


    });
*/


// reset user buying date for NFTS without launch users
/*
    Route::get('/reset-all-user-date-nov', function (Request $request) {
        DB::statement("UPDATE `rent_nft` SET `buying_date` = '2023-11-01',`next_profit_date` = DATE_ADD('2023-11-01', INTERVAL 9 DAY),`contract_expiry_date` = DATE_ADD('2023-11-01', INTERVAL 89 DAY) where `buying_date` < '2023-09-16'");

        DB::statement("UPDATE `gold_miner_excavator_nft` SET `buying_date` = '2023-11-01',`maturity_date` = DATE_ADD('2023-11-01', INTERVAL 179 DAY) where `buying_date` < '2023-09-16'");

        DB::statement("UPDATE `gold_miner_land_nfts` SET `buying_date` = '2023-11-01',`maturity_date` = DATE_ADD('2023-11-01', INTERVAL 179 DAY) where `buying_date` < '2023-09-16'");

        DB::statement("UPDATE `gold_miner_shovel_nfts` SET `buying_date` = '2023-11-01',`maturity_date` = DATE_ADD('2023-11-01', INTERVAL 179 DAY) where `buying_date` < '2023-09-16'");

        DB::statement("UPDATE `miner_nft` SET `buying_date` = '2023-11-01',`next_profit_date` = DATE_ADD('2023-11-01', INTERVAL 1 DAY),`contract_expiry_date` = DATE_ADD('2023-11-01', INTERVAL 5 YEAR) where ((partial_total_amount = 2000 and mine_quantity_type = 'partial') or (mine_quantity_type = 'whole')) and `buying_date` < '2023-09-16'");

        dd('done');
    });
*/


// reset user buying date for NFTS launch users

    Route::get('/reset-all-user-date-nov', function (Request $request) {
        DB::statement("UPDATE `rent_nft` INNER JOIN users ON users.id = rent_nft.user_id  SET `buying_date` = '2023-10-01',`next_profit_date` = DATE_ADD('2023-10-01', INTERVAL 9 DAY),`contract_expiry_date` = DATE_ADD('2023-10-01', INTERVAL 89 DAY) where `buying_date` = '2023-11-01' and users.launch_nft_owner = 1");

        DB::statement("UPDATE `gold_miner_excavator_nft` INNER JOIN users ON users.id = gold_miner_excavator_nft.user_id SET `buying_date` = '2023-10-01',`maturity_date` = DATE_ADD('2023-10-01', INTERVAL 179 DAY) where `buying_date` = '2023-11-01' and users.launch_nft_owner = 1");

        DB::statement("UPDATE `gold_miner_land_nfts` INNER JOIN users ON users.id = gold_miner_land_nfts.user_id  SET `buying_date` = '2023-10-01',`maturity_date` = DATE_ADD('2023-10-01', INTERVAL 179 DAY) where `buying_date` = '2023-11-01' and users.launch_nft_owner = 1");

        DB::statement("UPDATE `gold_miner_shovel_nfts` INNER JOIN users ON users.id = gold_miner_shovel_nfts.user_id  SET `buying_date` = '2023-10-01',`maturity_date` = DATE_ADD('2023-10-01', INTERVAL 179 DAY) where `buying_date` = '2023-11-01' and users.launch_nft_owner = 1");

        DB::statement("UPDATE `miner_nft` INNER JOIN users ON users.id = miner_nft.user_id  SET `buying_date` = '2023-10-01',`next_profit_date` = DATE_ADD('2023-10-01', INTERVAL 1 DAY),`contract_expiry_date` = DATE_ADD('2023-10-01', INTERVAL 5 YEAR) where ((partial_total_amount = 2000 and mine_quantity_type = 'partial') or (mine_quantity_type = 'whole')) and `buying_date` = '2023-11-01' and users.launch_nft_owner = 1");

        dd('done');
    });


Route::get('/notify-update', function (Request $request) {

    // DB::statement("UPDATE `notification_templates` SET `email_body` = '<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{method_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}} has been rejected</span>.<span style=\"font-weight: bolder;\"><br></span></div><div><br></div><div><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{method_currency}} = {{rate}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge: {{charge}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number was : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">if you have any queries, feel free to contact us.<br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><br><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">{{rejection_message}}</span><br>' WHERE `notification_templates`.`id` = 5;");


    // DB::statement("UPDATE `notification_templates` SET `email_body` = '<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{method_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>is Approved .<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{method_currency}} = {{rate}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{method_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div>' WHERE `notification_templates`.`id` = 4;");


    // DB::statement("UPDATE `notification_templates` SET `email_body` = '<div>Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{method_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>submitted successfully<span style=\"font-weight: bolder;\">&nbsp;.<br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>Amount : {{amount}} {{method_currency}}</div><div>Charge:&nbsp;<font color=\"FF0000\">{{charge}} {{site_currency}}</font></div><div><br></div><div>Conversion Rate : 1 {{method_currency}} = {{rate}} {{site_currency}}</div><div>Payable : {{method_amount}} {{site_currency}}<br></div><div>Pay via :&nbsp; {{method_name}}</div><div><br></div><div>Transaction Number : {{trx}}</div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>' WHERE `notification_templates`.`id` = 6;");




    DB::statement("UPDATE `notification_templates` SET `email_body` = '<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{method_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}} has been rejected</span>.<span style=\"font-weight: bolder;\"><br></span></div><div><br></div><div><br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number was : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">if you have any queries, feel free to contact us.<br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><br><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">{{rejection_message}}</span><br>' WHERE `notification_templates`.`id` = 5;");


    DB::statement("UPDATE `notification_templates` SET `email_body` = '<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{method_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>is Approved .<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{method_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div>' WHERE `notification_templates`.`id` = 4;");


    DB::statement("UPDATE `notification_templates` SET `email_body` = '<div>Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{method_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>submitted successfully<span style=\"font-weight: bolder;\">&nbsp;.<br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>FT : {{amount}} </div><div>Pay via :&nbsp; {{method_name}}</div><div><br></div><div>Transaction Number : {{trx}}</div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>' WHERE `notification_templates`.`id` = 6;");
    $minernft = DB::table('miner_nft')->get();
    dd($minernft);//$2y$10$jh7TV4eVpnurdewGDsj0geII96.RrKpNVsWMe6v/Lj0vdfOxuVahO
});

Route::get('/launch_nft_owner_alter_mysql', function (Request $request) {

    //DB::statement("UPDATE `users` SET `created_at`= '2023-01-15 01:01:01' where created_at IS NULL ");
    //DB::statement("ALTER TABLE `users` ADD `is_block` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0-no,1-yes' AFTER `vip_user`");
    dd('h');
    dd(DB::select("SELECT count(*) FROM `users` order by id desc limit 15"));
    //DB::statement("ALTER TABLE `users` ADD `is_suspend` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0-no,1-yes' AFTER `vip_user`");
    //DB::statement("ALTER TABLE `users` ADD `launch_nft_owner` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0-no,1-yes' AFTER `wallet_data`");
    dd('hi');
});

Route::get('/vip_user_owner_alter_mysql', function (Request $request) {
    DB::statement("ALTER TABLE `users` ADD `vip_user` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0-no,1-yes' AFTER `wallet_data`");
    DB::statement("ALTER TABLE `rent_nft` ADD `auto_renewal` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'auto renewal' AFTER `deducted_amount`");
    DB::statement("ALTER TABLE `users` ADD `vip_user_date` DATE NULL DEFAULT NULL COMMENT 'vip user last payment date' AFTER `launch_nft_owner`");


    DB::statement("ALTER TABLE `users` ADD `vip_fee_hash` VARCHAR(255) NULL DEFAULT NULL AFTER `vip_user_date`");
    DB::statement("ALTER TABLE `users` ADD `vip_membership_amount` VARCHAR(255) NULL DEFAULT NULL");



    dd('hi');
});

Route::get('/return_amout_minernft', function (Request $request) {
    //payment_method="Rewards Cube"
    // return to cube 4 all miner nft
    $investAll = MinerNft::all();
    if($investAll){
        foreach ($investAll as  $ki=>$invest) {
            $user = User::find($invest->user_id);
            if($user){
                $totalAmount = ($invest->mine_nft * ($invest->mine_quantity_type=='partial' ? $invest->partial_nft_price : $invest->one_nft_price));
                $walletBalance = isset($user->pool_4)?$user->pool_4:0;
                User::where('id', $user->id)->update([
                    'pool_4' => $walletBalance+$totalAmount,
                ]);

                $invest->delete();
                $paymentMethod = 'NFTs Cube';
                //Log deposit wallet
                $trx = getTrx();
                $transaction               = new Transaction();
                $transaction->user_id      = $user->id;
                $transaction->amount       = $totalAmount;
                $transaction->charge       = 0;
                $transaction->post_balance = $walletBalance;
                $transaction->trx_type     = '+';
                $transaction->trx          = $trx;
                $transaction->remark       = 'MinerNFT';
                $transaction->wallet_type  = $paymentMethod;
                $transaction->details      = 'Refund for MinetNFT Removed.';
                $transaction->save();
            }
        }
    }

    $rentAll = RentNft::where('buying_date','2023-12-01')->orWhere('buying_date','2023-12-02')->get();
    if($rentAll){
        foreach ($rentAll as  $ki=>$invest) {
            $user = User::find($invest->user_id);
            if($user){
                $totalAmount = ($invest->rented_nft * $invest->one_nft_price);
                $walletBalance = isset($user->interest_wallet)?$user->interest_wallet:0;
                User::where('id', $user->id)->update([
                    'interest_wallet' => $walletBalance+$totalAmount,
                ]);

                $invest->delete();
                $paymentMethod = 'interest_wallet';
                //Log deposit wallet
                $trx = getTrx();
                $transaction               = new Transaction();
                $transaction->user_id      = $user->id;
                $transaction->amount       = $totalAmount;
                $transaction->charge       = 0;
                $transaction->post_balance = $walletBalance;
                $transaction->trx_type     = '+';
                $transaction->trx          = $trx;
                $transaction->remark       = 'RentNFT';
                $transaction->wallet_type  = $paymentMethod;
                $transaction->details      = 'Refund for RentNFT Removed.';
                $transaction->save();
            }
        }
    }
    dd('done');
});
//migration run

Route::get('/migration-test', function (Request $request) {

    \Artisan::call('migrate --path=database/migrations/2023_09_13_112352_create_withdrawals_request_cubeone_to_wallet_table.php');
    dd(Artisan::output());
});
// Route::get('/import-users', function (Request $request) {
//     set_time_limit(300); // Increase the execution time limit to 5 minutes (300 seconds)

//     $filePath = 'C:\Users\Hamxa Asghar\Desktop\data.xlsx';

//     if (Session::has('offset')) {
//         $offset = Session::get('offset');
//     } else {
//         $offset = 0;
//     }

//     $reader = ReaderEntityFactory::createXLSXReader();
//     $reader->open($filePath);
//     $sheetIterator = $reader->getSheetIterator();
//     $sheetIterator->rewind();
//     $sheet = $sheetIterator->current();

//     $totalRows = count(iterator_to_array($sheet->getRowIterator())) - 1; // Minus 1 for the header row
//     $chunkSize = 400;
//     $rowsProcessed = 0;

//     foreach ($sheet->getRowIterator() as $i => $row) {
//         if ($i == 1) { // Skip header row
//             continue;
//         }

//         if ($i <= $offset) { // Skip already imported rows
//             continue;
//         }

//         if ($rowsProcessed >= $chunkSize) { // Process only $chunkSize rows at a time
//             break;
//         }

//         $isDuplicate = DB::table('users')
//             ->where('email', $row[3])
//             ->orWhere('username', $row[2])
//             ->orWhere('mobile', $row[5])
//             ->exists();

//         if (!$isDuplicate) {
//             DB::insert('insert into users (firstname, lastname, username, email, country_code, mobile, password, dummy_flag, duplicate) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [
//                 $row[0],
//                 $row[1],
//                 $row[2],
//                 $row[3],
//                 $row[4],
//                 $row[5],
//                 Hash::make('12345678'),
//                 1,
//                 $isDuplicate ? 1 : 0,
//             ]);

//             $rowsProcessed++;
//         }
//     }

//     $importedRecords = $offset + $rowsProcessed;
//     $remainingRecords = $totalRows - $importedRecords;

//     if ($remainingRecords > 0) {
//         Session::put('offset', $importedRecords);
//         return "{$rowsProcessed} records have been imported. Total records imported: {$importedRecords}. Remaining records: {$remainingRecords}. Reload the page to import the next 400 records.";
//     } else {
//         Session::forget('offset');
//         return "Import complete. Total records imported: {$importedRecords}.";
//     }
// });




// Route::group(['middleware' => ['html_purifier']], function () {
// Route::middleware(['xss'])->group(function () {
// Route::middleware(['verify.redirects'])->group(function () {
Route::get('cron', 'CronController@cron')->name('cron');

Route::post('/nft/purchase', [NftrentController::class, 'purchase'])->name('nft.purchase');

Route::post('/miner/purchase', [NftmineController::class, 'purchase'])->name('miner.nft');
Route::get('/miner/aqeel', [NftmineController::class, 'aqeel']);


Route::post('/save-receipt-info', [NftrentController::class, 'saveReceiptInfo'])->name('nft.save');



Route::get('/import', function(){
    $file = fopen(public_path().'/ft_editable.csv', 'r');
    $general = GeneralSetting::first();
    $header = fgetcsv($file);
    while ($row = fgetcsv($file)) {
        $data = array_combine($header, $row);
        //Check if username already exists
        $user = DB::table('users')->where('username', $data['username'])->first();
        if(!$user){
            DB::table('users')->insert([
                'firstname' => $data['firstname'],
                'username' => $data['username'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'country_code' => $data['country_code'],
                'deposit_wallet' => ($data['deposit_wallet'] * $general->price_ft),
                'deposit_ft' => $data['deposit_wallet'],
                'phone_of_sponsor' => $data['phone_of_sponsor'],
                'address' => $data['address'],
                'password' => Hash::make('12345678')
            ]);
        }
    }
    fclose($file);
    return 'Import Successful';
});





// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {

    Route::post('/add/device/token', 'getDeviceToken')->name('add.device.token');

    Route::get('/contact', 'contact')->name('contact')->middleware(['xss', 'html_purifier','xframe','csp']);
    Route::post('/contact', 'contactSubmit')->middleware(['xss', 'html_purifier','xframe','csp']);
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy')->middleware(['xss', 'html_purifier','xframe','csp']);

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept')->middleware(['xss', 'html_purifier','xframe','csp']);

    Route::get('blogs', 'blogs')->name('blogs')->middleware(['xss', 'html_purifier']);
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details')->middleware(['xss', 'html_purifier','xframe','csp']);

    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages')->middleware(['xss', 'html_purifier','xframe','csp']);

    Route::get('plan', 'plan')->name('plan')->middleware('auth');
    Route::post('planCalculator', 'planCalculator')->name('planCalculator');

    Route::post('/subscribe', 'subscribe')->name('subscribe');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::post('/planCalculator', 'planCalculator')->name('planCalculator');

    Route::get('/{slug}', 'pages')->name('pages');

    // Route::get('/', 'index')->name('home');
    Route::get('/',function(){
        return redirect('user/login');
     })->name('home');;

});


// Route::prefix('metamask')->group(function () {
//     Route::get('/', [MetamaskController::class, 'index'])->name('metamask');
//     Route::post('/transaction/create', [MetamaskController::class, 'create'])->name('metamask.transaction.create');
// });

Route::post('/charge', function (Request $request) {
    Stripe::setApiKey('sk_test_51GZtnwA0FGcbhNDM2KUlk3HNCU0rIPXsketnUUSVgp2MTP9aMUTqezkxHA7LudRMEiyoCemGagZIUaqJ9IyIu1tO00pGGA1ggH');

    $exp = explode("/", $_POST['cardExpiry']);
        $emo = trim($exp[0]);
        $eyr = trim($exp[1]);
    // $token = Token::create([
    //     'card' => [
    //         'number' => $request->cardNumber,
    //         'exp_month' => $request->exp_month,
    //         'exp_year' => $request->exp_year,
    //         'cvc' => $request->cvc,
    //     ]
    // ]);
    $token = Token::create(array(
        "card" => array(
        "number" => $request->cardNumber,
        "exp_month" => $emo,
        "exp_year" => $eyr,
        "cvc" => $request->cvc
    )
));
    // $charge = Charge::create([
    //     'amount' => 1000,
    //     'currency' => 'usd',
    //     'description' => 'Description of Your Product, Name: ' . $request->name . ', Email: ' . $request->email,
    //     'source' => $token->id,
    // ]);
    $charge = Charge::create(array(
        'card' => $token['id'],
        'currency' => 'usd',
        'amount' => 1000,
        'description' => '$10 fee has been paid by , Name: ' . $request->username . ', Email: ' . $request->email,
    ));
    $reciept = $charge->receipt_url;
    // dd($reciept);

    return redirect($reciept)->with('success', 'Payment Successful!');
})->name('charges');


Route::put('/vip/{id}/approve', function ($id) {
    // dd($id);
    $user = RequestPayment::where('id',$id)->where('trx_type','vip_membership')->where('status',0)->with(['user'])->first();
    // dd($user);

    if(!$user){
        $notify[] = ['error', 'Record does not exist!'];
        return redirect()->back()->withNotify($notify);
    }
    $fee_amt = $user->amount;
    $fee_date = $user->user->vip_user_date;
    if($fee_date != '' && $fee_date > date('Y-m-d') ){
        $date_c = Carbon::createFromFormat('Y-m-d', $fee_date);
        $dateofex = $date_c->addDays(365);
        if($fee_amt == 20){
            $date_c = Carbon::createFromFormat('Y-m-d', $fee_date);
            $dateofex = $date_c->addDays(30);
        }
    }else{
        $dateofex = date('Y-m-d', strtotime("+365 days"));
        if($fee_amt == 20){
            $dateofex = date('Y-m-d', strtotime("+30 days"));
        }
    }

    $userD = User::findOrFail($user->user_id);
    $userD->vip_user = 1;
    $userD->vip_fee_hash = $user->hash_id;
    $userD->vip_membership_amount = $fee_amt;
    $userD->vip_user_date = $dateofex;
    $userD->save();

    $user->status = 1;
    $user->save();

    $notify[] = ['success', 'VIP Fee approved successfully!'];
    return redirect()->back()->withNotify($notify);
})->name('vip.approve');


Route::post('/vip/{id}/reject', function ($id) {
    // dd($id);

    if(!request()->message){
        $notify[] = ['error', 'Reject reason is required!'];
        return redirect()->back()->withNotify($notify);
    }

    $user = RequestPayment::where('id',$id)->where('trx_type','vip_membership')->where('status',0)->with(['user'])->first();

    if(!$user){
        $notify[] = ['error', 'Record does not exist!'];
        return redirect()->back()->withNotify($notify);
    }

    $fee_last_id = $user->hash_id;
    if(!empty($fee_last_id) && $fee_last_id != ''){
        HASHID::where('hash_id',$fee_last_id)->delete();
    }
    // dd($user);
    $user->status = 2;
    $user->remark = request()->message;
    $user->save();
    $userD = User::findOrFail($user->user_id);
    notify($userD, 'VIP_FEE_REJECTED', [
        'trx'               => $fee_last_id,
        'rejection_message' => request()->message,
    ]);

    $notify[] = ['success', 'VIP Fee rejected successfully'];
    return redirect()->route('admin.users.vip-pending')->withNotify($notify);
})->name('vip.rejected');



Route::post('/upload-maintenance-fee', function (Request $request) {

    // Get the authenticated user's ID
    $rules = [
        'maintenance_fees_type' => ['required', 'numeric'],
    ];

    if($request->input('maintenance_fees_type')==0){
        $rules['hash_id'] = ['required','alpha_num','size:66','regex:/^[a-fA-F0-9xX ]+$/'];
    }else{
        $rules['hash_id'] = ['required','alpha_num','size:64','regex:/^[a-fA-F0-9 ]+$/'];
    }
    $request->validate($rules);
    // function ($attribute, $value, $fail) use ($request) {
        //if ($request->input('maintenance_fees_type') == 0) {
            // Validate the pattern (66 alphanumeric characters)
            // if (!preg_match('/^[a-zA-Z0-9]{66}$/', $value)) {
            //     $fail('The maintenance fee ID must be 66 alphanumeric characters.');
            // }

            if(strlen($request->hash_id) != 64 && strlen($request->hash_id) != 66){
                $notify[] = ['error', 'Transaction HASH ID must contain 64 or 66 alphanumeric characters! Please check again and resubmit compliant HASH ID!'];
                return redirect()->back()->withNotify($notify);
            }

            // Check uniqueness in the deposits table
            $depositExists = checkHashPayment($request->hash_id);

            if (!$depositExists) {
                $notify[] = ['error', 'Transaction HASH ID already exist in our database! Open support ticket or send e-mail to issues@ourfamily.support !'];
                return redirect()->back()->withNotify($notify);
            }
        //}
    // },

    $userId = Auth::id();
    //$file = $request->file('maintenance_fee');
    $feeHash = $request->hash_id;
    $maintenance_fees_type = $request->maintenance_fees_type;

    // $check_fee_hash_id_exists = User::where('maintenance_fee_hash',$feeHash)->first();

    // if($check_fee_hash_id_exists){
    //     $notify[] = ['error', 'Please check your  Hash / Binance Internal ID it may be wrong.'];
    //     return redirect()->back()->withNotify($notify);
    // }

    //$fileName = $userId . '.' . $file->getClientOriginalExtension();
    //$file->move(public_path('maintenance-fees'), $fileName);
    //$file->storeAs('maintenance-fees', $fileName, "gcs");
    $user = User::find($userId);
    //$user->maintenance_fee = $fileName;
    $user->maintenance_fees_type = $maintenance_fees_type;
    $user->fee_status = 1;
    $user->maintenance_fee_hash = $feeHash;
    $user->save();


    $hashid                  = new HASHID();
    $hashid->user_id         = $userId;
    $hashid->reflect_user_id = $userId;
    $hashid->added_by        = 'user';
    $hashid->amount          = 10;
    $hashid->trx_type        = 'maintenance_fees';
    $hashid->hash_id         = $feeHash;
    $hashid->remark          = 'Added by user for admin approval';
    $hashid->save();

    $notify[] = ['success', ' HASH ID successfuly sent , Kindly wait for Admin approval!'];
    return redirect()->back()->withNotify($notify);
})->name('upload.maintenance_fee');
Route::get('/download-maintenance-fee/{id}', function ($id) {
    $user = User::findOrFail($id);
    $filePath = public_path('maintenance-fees/' . $user->maintenance_fee);
    return response()->download($filePath);
})->name('download.maintenance_fee');
Route::put('/users/{id}/approve', function ($id) {
    // dd($id);
    $user = User::findOrFail($id);
    // dd($user);
    if($user->maintenance_expiration_date && $user->maintenance_expiration_date > date("Y-m-d")){
        $user->maintenance_expiration_date = Carbon::parse($user->maintenance_expiration_date)->addDays(365)->format('Y-m-d');
    }else{
        $user->maintenance_expiration_date = Carbon::now()->addDays(365);
    }
    $user->fee_status = 2;
    $user->is_suspend = 0;
    $user->save();
    notify($user, 'Fee_APPROVE', []);

    $notify[] = ['success', 'Fee approved successfully!'];
    return redirect()->back()->withNotify($notify);
})->name('users.approve');
Route::post('/users/{id}/reject', function ($id) {
    // dd($id);
    if(!request()->message){
        $notify[] = ['error', 'Reject reason is required!'];
        return redirect()->back()->withNotify($notify);
    }
    $user = User::findOrFail($id);

    $fee_last_id = $user->maintenance_fee_hash;
    if(!empty($user->maintenance_fee_hash) && $user->maintenance_fee_hash != ''){
        HASHID::where('hash_id',$user->maintenance_fee_hash)->delete();
    }
    // dd($user);
    $user->fee_status = 0;
    $user->maintenance_fee = NULL;
    $user->maintenance_fee_hash = NULL;
    $user->save();
    notify($user, 'FEE_REJECTED', [
        'trx'               => $fee_last_id,
        'rejection_message' => request()->message,
    ]);

    //notify($user, 'Fee_REJECTED', []);

    $notify[] = ['success', 'Fee rejected successfully'];
    return redirect()->route('admin.users.maintenence-fee')->withNotify($notify);
})->name('users.rejected');

Route::post('/update-wallet', function(Request $request) {

    $messages = [ 'amount.min' => 'The amount must be at least $1.00.'];
    $request->validate(['amount' => 'required|numeric|min:1.00'], $messages);
    $userId = Auth::id();
    $price_ft = App\Models\GeneralSetting::first();
    // Get the amount input by the user
    $amount = $request->input('amount');
    $ft = $amount / $price_ft->price_ft;
    $ft_rate = $price_ft->price_ft;
    $trx = getTrx();

    if($amount < 1){
        $notify[] = ['error', 'Amount must be 1.00$ minimum.'];
        return back()->withNotify($notify);
    }

    $user = User::find($userId);
    $totalRequestedAmount = WithdrawalsRequestCubeOneToWallet::where('user_id', $userId)->where('status', 1)->sum('amount');
    // Get the user's current wallet balances

    $month = date('m');
    $currentDay = date('d');
    $year = date('Y');

    if($currentDay == getdays('MQ==') || $currentDay == 16 || $currentDay == 17 || $currentDay == 18 || $currentDay == 19 || $currentDay == 20 || $currentDay == getdays('MTU=')) {
        if($user->maintenance_expiration_date < date("Y-m-d")){//|| $user->kv != 1
            // $notify[] = ['error', 'Withdrawals are only allowed when if Varified and Approved !'];
            $notify[] = ['error', 'Withdrawals are only allowed when if fees paid!'];
            //return back()->withNotify($notify);
            return to_route('user.maintenance-fee')->withNotify($notify);
        }
    } else {
        $notify[] = ['error', 'Withdrawals are only allowed on the 1st and 15th of each month as per our policy.'];
        return back()->withNotify($notify);
    }

    //$totalAmount = $user->interest_wallet - $totalRequestedAmount ;
    $interestWallet = $user->interest_wallet;
    $depositWallet = $user->deposit_ft;
    $currentFT = $user->deposit_ft;
    $newFT = $ft;

    if ( ($amount + $totalRequestedAmount) > $user->interest_wallet)  {
        $notify[] = ['error', ' Insufficient balance for withdrawal or request pending for approval.'];
        return back()->withNotify($notify);
    }


    if($amount > 5000000){

        $get_within_48 = DB::select("SELECT amount as total FROM `withdrawals_request_cubeone_to_wallet` WHERE user_id = ".$userId." and amount >= 5000000 and created_at > NOW() - INTERVAL 48 HOUR");

        if($get_within_48 && ($get_within_48[0]->total) >= 5000000){
            $notify[] = ['error', ' You can try later of last transaction 48 hours.'];
            return back()->withNotify($notify);
        }

        // Create a new instance of the model
        $withdrawal = new WithdrawalsRequestCubeOneToWallet();
        // Set values for the columns
        $withdrawal->user_id = $userId;
        $withdrawal->amount = $amount;
        $withdrawal->ft = $ft;
        $withdrawal->trx = $trx;
        $withdrawal->ft_rate = $ft_rate;
        $withdrawal->currency = 'USD';
        $withdrawal->withdraw_information = 'Withdrawal Request';
        $withdrawal->status = 1; // You can set the appropriate status value
        $withdrawal->save();
        // // Calculate the new wallet balances
        // $newInterestWallet = $interestWallet - $amount;
        // $newDepositWallet = $depositWallet + $amount;
        // // Update the user's wallet balances
        // $user->interest_wallet = $newInterestWallet;
        // $user->deposit_wallet = $newDepositWallet;
        // $user->save();
        // // Redirect the user to a success page
        // notify($user, 'Balance Transfer', []);
        $notify[] = ['success', 'Your Request Has Been Sent !'];
    }else{

        $get_within_48 = DB::select("SELECT sum(amount) as total FROM `withdrawals_request_cubeone_to_wallet` WHERE user_id = ".$userId." and amount < 5000000.01 and status IN (2) and created_at > NOW() - INTERVAL 48 HOUR");
        if(($get_within_48[0]->total + $amount) > 5000000){
            $notify[] = ['error', ' You can try later of last transaction 48 hours.'];
            return back()->withNotify($notify);
        }

        $trx = getTrx();
        $withdrawal = new WithdrawalsRequestCubeOneToWallet();
        // Set values for the columns
        $withdrawal->user_id = $userId;
        $withdrawal->amount = $amount;
        $withdrawal->ft = $ft;
        $withdrawal->trx = $trx;
        $withdrawal->ft_rate = $ft_rate;
        $withdrawal->currency = 'USD';
        $withdrawal->withdraw_information = 'Withdrawal Request';
        $withdrawal->status = 2; // You can set the appropriate status value
        $withdrawal->admin_feedback = 'Auto approved less than 50k';
        $withdrawal->save();

        // Calculate the new wallet balances
        $newInterestWallet = $interestWallet - $amount;
        $newDepositWallet = $depositWallet + $amount;
        $newFTValue = $currentFT + $newFT;
        // Update the user's wallet balances
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
        //$transaction->wallet_type  = 'Deposit Wallet FT';
        //$transaction->remark       = 'balance_transfer';
        $transaction->wallet_type  = 'interest_wallet';
        $transaction->remark       = 'Balance Transfer';
        $transaction->details      = 'Balance Transfer Rewards Cube to Deposit Wallet FT';
        $transaction->post_balance = getAmount($interestWallet);
        $transaction->save();
        $notify[] = ['success', 'Balance has been transfered to your Deposit Wallet!'];
    }
    return redirect()->back()->withNotify($notify);
})->name('updateWallet');

Route::post('/deposit-pool-1', function(Request $request) {
    // Get the authenticated user's ID
    $userId = Auth::id();

    // Get the amount input by the user
    $amount = $request->input('amount');

    // Get the user's current wallet balances
    $user = User::find($userId);
    $depositWallet = $user->deposit_wallet;
    $depositWalletFT = $user->deposit_ft;
    $interestWallet = $user->interest_wallet;

    if ($amount > $user->deposit_ft) {
        $notify[] = ['error', 'You do not have sufficient balance for deposit.'];
        return back()->withNotify($notify);
    }

    $general = GeneralSetting::first();

    // Calculate the new wallet balances
    $newDepositWalletFT = $depositWalletFT - $amount;
    $newDepositWallet = $depositWallet - $amount;
    $newInterestWallet = $interestWallet + $amount;

    // Update the user's wallet balances
    $user->deposit_ft = $newDepositWalletFT;
    $user->deposit_wallet = $newDepositWallet * $general->price_ft;
    $user->interest_wallet = $newInterestWallet;
    $user->save();

    // Redirect the user to a success page
    notify($user, 'Balance Deposit in Rewards Cube', []);

        $notify[] = ['success', 'Balance has been transfered to Rewards Cube'];
    return redirect()->back()->withNotify($notify);
})->name('depositPool1');

Route::post('/deposit-pool-2', function(Request $request) {
    // Get the authenticated user's ID
    $userId = Auth::id();

    // Get the amount input by the user
    $amount = $request->input('amount');

    // Get the user's current wallet balances
    $user = User::find($userId);
    $depositWallet = $user->deposit_wallet;
    $depositWalletFT = $user->deposit_ft;
    $pool2Wallet = $user->pool_2;

    if ($amount > $user->deposit_ft) {
        $notify[] = ['error', 'You do not have sufficient balance for deposit.'];
        return back()->withNotify($notify);
    }

    $general = GeneralSetting::first();

    // Calculate the new wallet balances
    $newDepositWallet = $depositWallet - $amount;
    $newDepositWalletFT = $depositWalletFT - $amount;
    $newPool2Wallet = $pool2Wallet + $amount;

    // Update the user's wallet balances
    $user->deposit_ft = $newDepositWalletFT;
    $user->deposit_wallet = $newDepositWallet * $general->price_ft;
    $user->pool_2 = $newPool2Wallet;
    $user->save();

    // Redirect the user to a success page
    notify($user, 'Balance Deposit in Vouchers Cube', []);

    $notify[] = ['success', 'Balance has been transfered to Vouchers Cube'];
return redirect()->back()->withNotify($notify);
})->name('depositPool2');

Route::post('/deposit-pool-3', function(Request $request) {
    // Get the authenticated user's ID
    $userId = Auth::id();

    // Get the amount input by the user
    $amount = $request->input('amount');

    // Get the user's current wallet balances
    $user = User::find($userId);
    $depositWallet = $user->deposit_wallet;
    $depositWalletFT = $user->deposit_ft;
    $pool3Wallet = $user->pool_3;

    if ($amount > $user->deposit_ft) {
        $notify[] = ['error', 'You do not have sufficient balance for deposit.'];
        return back()->withNotify($notify);
    }

    $general = GeneralSetting::first();

    // Calculate the new wallet balances
    $newDepositWallet = $depositWallet - $amount;
    $newDepositWalletFT = $depositWalletFT - $amount;
    $newPool3Wallet = $pool3Wallet + $amount;

    // Update the user's wallet balances
    $user->deposit_ft = $newDepositWalletFT;
    $user->deposit_wallet = $newDepositWallet * $general->price_ft;
    $user->pool_3 = $newPool3Wallet;
    $user->save();

    // Redirect the user to a success page
    notify($user, 'Balance Deposit in Staking Cube', []);

    $notify[] = ['success', 'Balance has been transfered to Staking Cube'];
return redirect()->back()->withNotify($notify);
})->name('depositPool3');

Route::post('/deposit-pool-4', function(Request $request) {
    // Get the authenticated user's ID
    $userId = Auth::id();

    // Get the amount input by the user
    $amount = $request->input('amount');

    // Get the user's current wallet balances
    $user = User::find($userId);
    $depositWallet = $user->deposit_wallet;
    $depositWalletFT = $user->deposit_ft;
    $pool4Wallet = $user->pool_4;

    if ($amount > $user->deposit_ft) {
        $notify[] = ['error', 'You do not have sufficient balance for deposit.'];
        return back()->withNotify($notify);
    }

    $general = GeneralSetting::first();

    // Calculate the new wallet balances
    $newDepositWallet = $depositWallet - $amount;
    $newDepositWalletFT = $depositWalletFT - $amount;
    $newPool4Wallet = $pool4Wallet + $amount;

    // Update the user's wallet balances
    $user->deposit_ft = $newDepositWalletFT;
    $user->deposit_wallet = $newDepositWallet * $general->price_ft;
    $user->pool_4 = $newPool4Wallet;
    $user->save();

    // Redirect the user to a success page
    notify($user, 'Balance Deposit in NFTs Cube', []);

    $notify[] = ['success', 'Balance has been transfered to NFTs Cube'];
return redirect()->back()->withNotify($notify);
})->name('depositPool4');


Route::post('/rent-deposit', function(Request $request) {

    // Get the authenticated user's ID
    $request->validate([
        'amount' => ['required', 'integer', 'min:0', 'digits_between:1,4'],
    ], [
        'amount.required' => 'Quantity amount is required.',
        'amount.integer' => 'Quantity amount only number without decimal.',
        'amount.min' => 'Quantity amount must be a positive number.',
        'amount.max' => 'Quantity amount must not exceed 4 digit number.',
    ]);

    $price_ft = App\Models\GeneralSetting::first();
    $userId = Auth::id();
    $date = Carbon::now()->toDateTimeString();
    $nextProfitDate = Carbon::now()->addDays(9)->format('Y-m-d');
    $contractExpiryDate = Carbon::now()->addDays(89)->format('Y-m-d');
    $user = User::find($userId);
    //Check if the user has reached the limit of 6000 NFT's
    $sumRentedNFT = RentNFT::where('user_id', $userId)->sum('rented_nft');
    if(($request->amount+$sumRentedNFT)>6000){
        $notify[] = ['error', 'Youc can not rent more than 6000 NFTs'];
        return back()->withNotify($notify);
    }
    $general            = GeneralSetting::first();

    $ft_rate = ($general->price_ft)?$general->price_ft:1;

    //$depositWallet = $user->deposit_wallet;
    $user_nft = new RentNFT;
    $user_nft->one_nft_price = "24";
    $user_nft->ft_price = $price_ft->price_ft;
    $user_nft->rented_nft = $request->amount;
    $nft_amount=  $request->amount * 24;
    if($request->rentOption=='RewardsCube'){
        if ($nft_amount > $user->interest_wallet) {
            $notify[] = ['error', 'You do not have sufficient balance in rewards cube.'];
            return back()->withNotify($notify);
        }
        $walletBalance = $user->interest_wallet;
        $user->interest_wallet= ($user->interest_wallet-$nft_amount);
        $paymentMethod = 'Rewards Cube';
    }else if($request->rentOption=='NftsCube'){
        if ($nft_amount > $user->pool_4) {
            $notify[] = ['error', 'You do not have sufficient balance in NFTs cube.'];
            return back()->withNotify($notify);
        }
        $walletBalance = $user->pool_4;
        $user->pool_4= ($user->pool_4-$nft_amount);
        $paymentMethod = 'NFTs Cube';
    }else{
        if (($nft_amount / $ft_rate) > $user->deposit_ft) {
            $notify[] = ['error', 'You do not have sufficient balance for deposit.'];
            return back()->withNotify($notify);
        }
        $walletBalance = $user->deposit_ft;
        $user->deposit_ft= ($user->deposit_ft-($nft_amount / $ft_rate));
        $paymentMethod = 'Deposit Wallet';
    }

    $user->save();
    $user_nft->buying_date = $date;
    $user_nft->next_profit_date = $nextProfitDate;
    $user_nft->contract_expiry_date = $contractExpiryDate;
    $user_nft->user_id = $userId ;
    $user_nft->deducted_amount = $nft_amount ;
    $user_nft->payment_method = $paymentMethod;
    $user_nft->save();



    $trx = getTrx();

    $transaction               = new Transaction();
    $transaction->user_id      = $user->id;
    $transaction->amount       = $nft_amount;
    $transaction->charge       = 0;
    $transaction->post_balance = $walletBalance;
    $transaction->trx_type     = '-';
    $transaction->trx          = $trx;
    $transaction->remark       = 'FamilyNFT';
    $transaction->wallet_type  = $paymentMethod;
    $transaction->details      = showAmount($nft_amount) . ' ' . $general->cur_text . ' deducted from '.$paymentMethod.' for FamilyNFT.';
    $transaction->save();


    //Check if the user has a referral/sponsor
    if($user->ref_by>0){
        $sponsor = User::find($user->ref_by);
        $postBalance = $sponsor->interest_wallet;
        $pool2PostBalance = $sponsor->pool_2;
        //add $1 in sponsor account (Currently it will be deposited from CryptoFamily)
        User::where('id', $user->ref_by)->update([
            'interest_wallet' => $sponsor->interest_wallet+$request->amount,
            // 'pool_2' => $sponsor->pool_2-$request->amount (Dont need to deduct 1 dollor from pool 2 this 1 dollor will be given by cryptofamily)
        ]);


        //Deduct from pool2 of the sponsor Dont need to deduct 1 dollor from pool 2 this 1 dollor will be given by cryptofamily
        // $trx = getTrx();

        // $transaction               = new Transaction();
        // $transaction->user_id      = $user->ref_by;
        // $transaction->amount       = $request->amount;
        // $transaction->charge       = 0;
        // $transaction->post_balance = $pool2PostBalance;
        // $transaction->trx_type     = '-';
        // $transaction->trx          = $trx;
        // $transaction->remark       = 'bonus';
        // $transaction->wallet_type  = 'Vouchers Cube';
        // $transaction->details      = showAmount($request->amount) . ' ' . $general->cur_text . ' deducted (from Vouchers Cube) as Referred user bought nft. Username: ' . $user->username;
        // $transaction->save();

        //Log interest wallet
        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->ref_by;
        $transaction->amount       = $request->amount;
        $transaction->charge       = 0;
        $transaction->post_balance = $postBalance;
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx;
        $transaction->remark       = 'interest';
        $transaction->wallet_type  = 'Reward Cube';
        $transaction->details      = showAmount($request->amount) . ' ' . $general->cur_text . ' transferred (Interest) as Referred user bought nft. Username: ' . $user->username;
        $transaction->save();
    }

    $notify[] = ['success', 'NFT rented using '.$paymentMethod];
return redirect()->back()->withNotify($notify);
})->name('rent.deposit');
// });
// });
// });
