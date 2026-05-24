<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pickup_location',
        'drop_location',
        'price_per_person',
        'thumbnail',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_per_person' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(TransferBooking::class, 'route_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
