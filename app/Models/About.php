<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'mission',
        'vision',
        'values',
        'team_info',
        'history',
        'image',
        'status'
    ];

    protected $casts = [
        'values' => 'array',
        'team_info' => 'array',
        'status' => 'boolean'
    ];
}
