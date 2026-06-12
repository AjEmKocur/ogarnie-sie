<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'long_description',
        'price_from',
        'is_active',
        'sort_order',
    ];
}
