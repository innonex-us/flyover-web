<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'url',
        'label',
        'clicks',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
