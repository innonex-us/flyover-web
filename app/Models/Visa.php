<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'slug',
        'type',
        'price',
        'validity',
        'maximum_stay',
        'requirements',
        'description',
        'thumbnail',
        'important_notes',
        'required_documents',
        'terms',
        'fees',
        'is_active',
    ];

    protected $casts = [
        'required_documents' => 'array',
        'price' => 'decimal:2',
    ];
}
