<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'endpoint_hash',
        'public_key',
        'auth_token',
        'user_agent',
    ];

    public static function findOrCreateByEndpoint(string $endpoint, string $publicKey, string $authToken, ?string $userAgent = null): static
    {
        $hash = hash('sha256', $endpoint);

        return static::updateOrCreate(
            ['endpoint_hash' => $hash],
            [
                'endpoint'   => $endpoint,
                'public_key' => $publicKey,
                'auth_token' => $authToken,
                'user_agent' => $userAgent,
            ]
        );
    }
}
