<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash; // import the Hash facade
use Illuminate\Support\Facades\DB;

class ImportUserData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filePath = public_path('data1.csv');;
        $lastProcessedRowFile = public_path('last_processed_row.txt');

        // Read the last processed row from the file
        $lastProcessedRow = file_exists($lastProcessedRowFile) ? intval(file_get_contents($lastProcessedRowFile)) : 0;

        $file = fopen($filePath, 'r');
        $counter = 0;
        $isHeaderRow = true; // Set a flag to skip the header row

        while (($data = fgetcsv($file)) !== false) {
            if ($isHeaderRow) {
                $isHeaderRow = false;
                continue;
            }

            if ($counter < $lastProcessedRow) {
                $counter++;
                continue;
            }

            $chunkSize = 500;
            $rows = [];

            for ($i = 0; $i < $chunkSize && $data; $i++) {
                $rows[] = [
                    'firstname' => $data[0],
                    'lastname' => $data[1],
                    'username' => $data[2],
                    'email' => $data[3],
                    'country_code' => $data[4],
                    'mobile' => $data[5],
                    'password' => Hash::make('12345678'),
                    'dummy_flag' => 1,
                    'ev' => 1, 
                    'sv' => 1,
                    'profile_complete' => 1,
                ];

                $counter++;
                $data = fgetcsv($file);
            }

            // Insert the chunk of data and check if it was successful
            if (DB::table('users')->insert($rows)) {
                echo "Inserted rows {$counter} - " . ($counter + count($rows) - 1) . "<br>";
                // Save the last processed row in the file
                file_put_contents($lastProcessedRowFile, $counter);
            } else {
                echo "Failed to insert rows {$counter} - " . ($counter + count($rows) - 1) . "<br>";
                // Log the error or handle it as needed
            }
        }

        fclose($file);
    }
}
