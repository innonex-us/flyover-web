<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'thumbnail',
        'amenities',
        'star_rating',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
        'star_rating' => 'decimal:1',
    ];

    public function rooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(HotelBooking::class, HotelRoom::class, 'hotel_id', 'room_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
