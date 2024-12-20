<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metamask_transaction extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'txHash',
        'amount',
        'status',
    ];

    /**
     * Get ALl Pending Transactions
     *
     * @return mixed
     */
    public function pendingTransactions()
    {
        return $this->where('status', 1)->where('created_at', '<', Carbon::NOW()->subMinutes(20))->get();
    }
}
