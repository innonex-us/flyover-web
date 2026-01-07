<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'duration_days',
        'location',
        'thumbnail',
        'images',
        'start_date',
        'is_active',
        'inclusions',
        'exclusions',
        'requirements',
        'itinerary',
        'policy',
    ];

    protected $casts = [
        'images' => 'array',
        'start_date' => 'date',
        'is_active' => 'boolean',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'itinerary' => 'array',
    ];
}
