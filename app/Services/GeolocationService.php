<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeolocationService
{
    private ?string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.ipapi.key') ?? env('IPAPI_KEY');
        $this->baseUrl = 'http://ip-api.com/json';
    }

    public function getLocationByIp(?string $ip = null): array
    {
        if (!$ip) {
            $ip = request()->ip();
        }

        if (!$ip || $ip === '127.0.0.1' || $ip === '::1') {
            return $this->getDefaultLocation();
        }

        $cacheKey = "geolocation_{$ip}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($ip) {
            try {
                $response = Http::timeout(5)
                    ->get($this->baseUrl, [
                        'ip' => $ip,
                        'fields' => 'status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query'
                    ]);

                if ($response->successful() && $response->json('status') === 'success') {
                    return [
                        'country' => $response->json('country'),
                        'country_code' => $response->json('countryCode'),
                        'region' => $response->json('regionName'),
                        'city' => $response->json('city'),
                        'postal_code' => $response->json('zip'),
                        'latitude' => $response->json('lat'),
                        'longitude' => $response->json('lon'),
                        'timezone' => $response->json('timezone'),
                        'isp' => $response->json('isp'),
                        'organization' => $response->json('org'),
                        'connection_type' => $this->detectConnectionType($response->json('org')),
                        'is_valid' => true
                    ];
                }
            } catch (\Exception $e) {
                \Log::warning('Geolocation service failed: ' . $e->getMessage());
            }

            return $this->getDefaultLocation();
        });
    }

    public function detectConnectionType(?string $organization = null): string
    {
        if (!$organization) {
            return 'unknown';
        }

        $org = strtolower($organization);

        if (str_contains($org, 'mobile') || str_contains($org, 'cellular') || str_contains($org, 'wireless')) {
            return 'mobile';
        }

        if (str_contains($org, 'fiber') || str_contains($org, 'optic')) {
            return 'fiber';
        }

        if (str_contains($org, 'cable') || str_contains($org, 'coax')) {
            return 'cable';
        }

        if (str_contains($org, 'dsl') || str_contains($org, 'broadband')) {
            return 'dsl';
        }

        if (str_contains($org, 'satellite')) {
            return 'satellite';
        }

        return 'broadband';
    }

    public function isEuropeanCountry(string $countryCode): bool
    {
        $europeanCountries = [
            'AL', 'AD', 'AT', 'BY', 'BE', 'BA', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FO', 'FI', 'FR',
            'DE', 'GR', 'GG', 'VA', 'HU', 'IS', 'IE', 'IM', 'IT', 'JE', 'LV', 'LI', 'LT', 'LU', 'MT', 'MD', 'MC', 'ME', 'NL', 'MK', 'NO', 'PL', 'PT', 'RO', 'RU', 'SM', 'RS', 'SK', 'SI', 'ES', 'SJ', 'SE', 'CH', 'UA', 'GB'
        ];

        return in_array(strtoupper($countryCode), $europeanCountries);
    }

    public function requiresGDPRCompliance(?string $ip = null): bool
    {
        $location = $this->getLocationByIp($ip);
        return $this->isEuropeanCountry($location['country_code'] ?? '');
    }

    private function getDefaultLocation(): array
    {
        return [
            'country' => null,
            'country_code' => null,
            'region' => null,
            'city' => null,
            'postal_code' => null,
            'latitude' => null,
            'longitude' => null,
            'timezone' => null,
            'isp' => null,
            'organization' => null,
            'connection_type' => 'unknown',
            'is_valid' => false
        ];
    }

    public function getCurrencyByCountry(string $countryCode): ?string
    {
        $currencyMap = [
            'US' => 'USD',
            'GB' => 'GBP',
            'EU' => 'EUR',
            'CA' => 'CAD',
            'AU' => 'AUD',
            'JP' => 'JPY',
            'CN' => 'CNY',
            'IN' => 'INR',
            'BD' => 'BDT',
            'AE' => 'AED',
            'SA' => 'SAR'
        ];

        return $currencyMap[$countryCode] ?? null;
    }

    public function getTimezoneByLocation(float $lat, float $lon): ?string
    {
        try {
            $response = Http::timeout(5)
                ->get("http://api.timezonedb.com/v2.1/get-time-zone", [
                    'key' => env('TIMEZONEDB_KEY'),
                    'format' => 'json',
                    'by' => 'position',
                    'lat' => $lat,
                    'lng' => $lon
                ]);

            if ($response->successful()) {
                return $response->json('zone');
            }
        } catch (\Exception $e) {
            \Log::warning('Timezone detection failed: ' . $e->getMessage());
        }

        return null;
    }
}
