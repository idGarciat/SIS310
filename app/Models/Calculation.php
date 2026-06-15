<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'input_data',
        'result_data'
    ];

    protected $casts = [
        'input_data' => 'array',
        'result_data' => 'array'
    ];
}
