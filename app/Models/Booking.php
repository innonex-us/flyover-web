<?php

namespace App\Models;

use App\Models\Concerns\HasPayments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, HasPayments;

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'payable_type',
        'payable_id',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'quantity',
        'total_amount',
        'booking_date',
        'notes',
        'details',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }
}
