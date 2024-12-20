<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDepositFT extends Command {
    protected $signature = 'update:deposit_ft';
    protected $description = 'Update deposit_ft for all users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Calculate the new deposit_ft values in bulk
        DB::table('users')->update([
            'deposit_ft' => DB::raw('deposit_wallet / 1')
        ]);

        $this->info('Deposit_ft updated for all.');
    }
}