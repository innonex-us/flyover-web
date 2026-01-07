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
        'processing_time',
        'requirements',
        'description',
        'thumbnail',
        'entry_type',
        'validity_info',
        'important_notes',
        'required_documents',
        'terms',
        'terms',
        'fees',
        'is_active',
    ];

    protected $casts = [
        'required_documents' => 'array',
        'price' => 'decimal:2',
    ];
}
