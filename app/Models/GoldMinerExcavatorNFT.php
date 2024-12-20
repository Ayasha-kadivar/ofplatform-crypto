<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldMinerExcavatorNFT extends Model
{
    use HasFactory;
    
    protected $table = 'gold_miner_excavator_nft';
     protected $fillable = [
        'user_id', 'quantity', 'payment_method', 
        'gold_market_price', 'ft_price', 
        'gold_amount','discount','buying_date','maturity_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}