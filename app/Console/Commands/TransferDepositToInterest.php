<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferDepositToInterest extends Command
{
    protected $signature = 'transfer:deposit-to-interest';
    protected $description = 'Transfer deposit_ft to interest_wallet and set deposit_ft to 0 for all users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {
        // Perform a bulk update to transfer deposit_ft to interest_wallet and set deposit_ft to 0
        DB::table('users')->update([
            'interest_wallet' => DB::raw('interest_wallet + deposit_ft'),
            'deposit_ft' => 0,
            'deposit_wallet' => 0,
        ]);

        $this->info('Deposit has been transferred to interest and deposit_ft has been set to 0 for all users.');
    }

}
