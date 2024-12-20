<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileUpdate extends Model
{
    use HasFactory;
    protected $table = 'profile_updates';
    protected $fillable = ['user_id','username','email','country_code','mobile','ver_code', 'ver_code_send_at', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
