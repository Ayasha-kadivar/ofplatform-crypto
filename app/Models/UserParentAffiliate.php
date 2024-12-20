<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class UserParentAffiliate extends Model
{
    use Searchable;
    protected $table = 'user_sponsor_affiliate_with_fees';
    protected $fillable = ['user_id', 'sponsor_id','affiliate_amount'];

    public function referrer()
    {
        return $this->belongsTo(User::class,'sponsor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
