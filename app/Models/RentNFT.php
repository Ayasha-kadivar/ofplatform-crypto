<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentNFT extends Model
{
    use HasFactory;
    protected $table = 'rent_nft';
    protected $fillable = ['user_meta_mask_info','one_nft_price','ft_price','rented_nft','buying_date','user_id', 'payment_method', 'deducted_amount','next_profit_date','contract_expiry_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function renewal()
    {
        return $this->hasMany(RenewNFT::class,'rent_id','id');
    }

    public function lastRenewal()
    {
    return $this->hasOne(RenewNFT::class,'rent_id','id')->latest();
    }
}
