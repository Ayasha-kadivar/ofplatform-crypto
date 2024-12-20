<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDepositFTValue extends Command
{
    protected $signature = 'update:deposit-ft-value';
    protected $description = 'Update deposit_ft from deposit_wallet for users with deposit_ft = 0 and deposit_wallet <= 0';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Update users with deposit_ft = 0 and deposit_wallet <= 0
        DB::table('users')->where('deposit_ft', 0)
            ->update([
                'deposit_ft' => DB::raw('deposit_wallet / 1'),
            ]);

        $this->info('Deposit_ft updated for eligible users.');
    }
}
