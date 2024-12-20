<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldMinerLandNFT extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'gold_miner_land_nfts';
     protected $fillable = [
        'user_id', 'quantity', 'payment_method', 
        'gold_market_price', 'ft_price', 
        'gold_amount','discount','buying_date','maturity_date'
    ];
}