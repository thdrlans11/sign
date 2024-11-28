<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'sid';

    protected $guarded = [
        'sid',
    ];

    protected $casts = [
        'query' => 'array',
        'content' => 'object'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
