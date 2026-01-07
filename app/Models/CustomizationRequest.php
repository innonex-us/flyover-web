<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizationRequest extends Model
{
    protected $fillable = [
        'package_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
