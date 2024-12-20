<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ImportUserData;

class ImportDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ImportUserData::dispatch();
        $this->info('Data import started!');
    }
}
