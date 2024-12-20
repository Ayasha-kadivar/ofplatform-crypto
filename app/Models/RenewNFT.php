<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class RenewNFT extends Model
{
    use Searchable;
    protected $table = 'renew_nft';
    protected $fillable = ['user_id', 'rent_id','renew_date','amount','wallet_type'];

}
