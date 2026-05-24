<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'check_in',
        'check_out',
        'nights',
        'guests',
        'total_amount',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_request',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
