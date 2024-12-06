<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class metal_price extends Model
{
    use HasApiTokens;
    protected $fillable = [
        'gold_price',
        'silver_price',
    ];
}
