<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinerNft extends Model
{
    use HasFactory;
    protected $table = 'miner_nft';
    protected $fillable = ['user_meta_mask_info','one_nft_price','ft_price','mine_nft','buying_date','user_id', 'payment_method', 'mine_quantity_type'];
}
