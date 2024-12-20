<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPurchase extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'card_purchases';
    protected $fillable = [
        'user_id',
        'card_price',
        'card_name',
        'price_ft',
        'buying_date',
        'payment_method',
    ];
}
