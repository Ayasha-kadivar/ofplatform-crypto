<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestPayment extends Model
{
    use HasFactory;
    protected $table = 'vip_transactions';
    protected $fillable = ['user_id','amount','trx_type','hash_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
