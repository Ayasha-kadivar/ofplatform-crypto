<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use App\Models\User;
use Carbon\Carbon;

class MigrationController extends Controller
{
    public function index()
    {
        $csvFile = public_path('users.csv');
        $handle = fopen($csvFile, 'r');

        if ($handle === false) {
            return 'Failed to open the CSV file.';
        }

        $bulkInsertValues = [];
        $count = 0;


        $emailExists = DB::connection('wordpress')
                    ->table('users')
                    ->pluck('user_email');  
                    $emailExists = $emailExists ? $emailExists->toArray() : [];
                    $flag = true;
            while (($data = fgetcsv($handle,0, ",")) !== FALSE) {
                if($flag) { $flag = false; continue; }
            
            
                if(isset($data[4]) && !empty($data[4])) {
                    $username = $data[3];
                    $email = $data[4];
                    
                    if (!in_array($email,$emailExists) && !array_key_exists($email,$bulkInsertValues)) {
                        $bulkInsertValues[$email] = [
                            'user_login' => $username,
                            'user_pass' => '',
                            'user_email' => $email,
                            'display_name' => $username,
                        ]; 
                    }
                }            
                // Insert the user data into the WordPress users table
                
            }
            
            if($bulkInsertValues){
                $chunk = array_chunk($bulkInsertValues,500);
                if($chunk){
                    foreach ($chunk as $key => $value) {
                        DB::connection('wordpress')->table('users')->insert($value);
                    }
                }
            }


        fclose($handle);

        return 'Bulk data insert complete.';
    }

    public function NFTOwnersFees() {
        $currentDateTime = Carbon::now()->addDays(730);
        $updateData = [
            'maintenance_fee' => 'Manual',
            'maintenance_expiration_date' => $currentDateTime,
            'fee_status' => 2
        ];
        $response = User::where('launch_nft_owner', '1')
        ->where(function ($query) {
            $query->whereNull('maintenance_fee')->orWhere('maintenance_fee', '');
        })
        ->update($updateData);
        echo $response;
    }

    public function KYCVarifiedMaintanceFeesPaid() {
        $currentDateTime = Carbon::now()->addDays(365);
        $updateData = [
            'maintenance_fee' => 'Manual',
            'maintenance_expiration_date' => $currentDateTime,
            'fee_status' => 2
        ];
        $response = User::where('launch_nft_owner', '0')
        ->where('kv', 1)
        ->whereNull('maintenance_expiration_date')
        ->where(function ($query) {
            $query->whereNull('maintenance_fee')->orWhere('maintenance_fee', '');
        })
        ->update($updateData);
        echo $response;
    }

    public function UpdateFT($id, $amount) {
        $user = User::where('id', $id)->first();
        $user->deposit_ft = $amount;
        $user->save();
    }

}
