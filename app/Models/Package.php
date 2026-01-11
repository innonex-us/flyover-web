<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'duration_days',
        'location',
        'thumbnail',
        'images',
        'start_date',
        'is_active',
        'inclusions',
        'exclusions',
        'requirements',
        'itinerary',
        'policy',
    ];

    protected $casts = [
        'images' => 'array',
        'start_date' => 'date',
        'is_active' => 'boolean',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'itinerary' => 'array',
    ];

    public function getItineraryAttribute($value)
    {
        if (empty($value)) return [];
        
        $itinerary = is_string($value) ? json_decode($value, true) : $value;
        
        if (!is_array($itinerary)) return [];

        return array_map(function($day) {
            // If it has 'activity' (old structure), convert to 'activities' (new structure)
            if (isset($day['activity']) && !isset($day['activities'])) {
                $day['activities'] = [$day['activity']];
                unset($day['activity']);
            }
            
            // Ensure properties exist
            $day['title'] = $day['title'] ?? '';
            $day['activities'] = $day['activities'] ?? [''];
            
            return $day;
        }, $itinerary);
    }
}
