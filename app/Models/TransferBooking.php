<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'is_custom',
        'pickup_location',
        'drop_location',
        'passenger_count',
        'travel_date',
        'pickup_time',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_request',
        'status',
        'total_amount',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'is_custom' => 'boolean',
    ];

    public function route()
    {
        return $this->belongsTo(TransferRoute::class, 'route_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
