<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $chunkSize;

    public function __construct($chunkSize)
    {
        $this->chunkSize = $chunkSize;
    }

  public function model(array $row)
    {
        return new User([
            'firstname' => $row['first_name'],
            'lastname' => $row['last_name'],
            'username' => $row['username'],
            'email' => $row['email_address'],
            'country_code' => $row['country_code'],
            'mobile' => $row['number'],
            'duplicate' => 0,
            'dummy_flag' => 1,
        ]);
    }

    public function batchSize(): int
    {
        return $this->chunkSize;
    }

    public function chunkSize(): int
    {
        return $this->chunkSize;
    }
}
