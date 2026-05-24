<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'room_type',
        'price_per_night',
        'capacity',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_per_night' => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(HotelBooking::class, 'room_id');
    }
}
