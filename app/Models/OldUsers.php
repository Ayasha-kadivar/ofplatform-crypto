<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldUsers extends Model
{
    use HasFactory;
    protected $table = 'old_users';
    protected $fillable = ['firstname','lastname','username','email','country_code','mobile','password','address'];
}
