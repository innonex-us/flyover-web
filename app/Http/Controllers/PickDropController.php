<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PickDropController extends Controller
{
    public function index()
    {
        $services = [
            ['icon' => '✈', 'title' => 'Airport transfer',  'price' => 'From ৳ 1,400', 'desc' => 'Driver waits in arrivals with your name. Flight tracked — they\'re there even if you\'re late.'],
            ['icon' => '⏱', 'title' => 'Hourly chauffeur',  'price' => '৳ 600 / hr',   'desc' => 'A car for the day — meetings, shopping, the airport at the end. Fuel + waiting included.'],
            ['icon' => '🛣', 'title' => 'Intercity',         'price' => 'From ৳ 8,500', 'desc' => 'Dhaka ↔ Chittagong, Sylhet, Cox\'s Bazar. One-way or return. Bottled water, cooler box.'],
            ['icon' => '🌐', 'title' => 'Cross-border',      'price' => 'From ৳ 18,000','desc' => 'Dhaka to Kolkata via Petrapole. Permits handled, driver waits while you walk across.'],
        ];

        $fleet = [
            ['name' => 'Toyota Axio',    'desc' => 'Sedan · 3 pax · 2 bags',    'price' => '৳ 1,400 ↑'],
            ['name' => 'Toyota Noah',    'desc' => 'SUV · 6 pax · 4 bags',      'price' => '৳ 2,200 ↑'],
            ['name' => 'Mercedes E-class','desc' => 'Premium · 3 pax · 3 bags', 'price' => '৳ 3,800 ↑'],
            ['name' => 'Hiace Coaster',  'desc' => 'Group · 12 pax · 12 bags',  'price' => '৳ 4,200 ↑'],
        ];

        $coverage = [
            ['Dhaka',           '24/7',          '14 cars on rotation'],
            ['Chittagong',      '24/7',          '6 cars on rotation'],
            ['Sylhet',          'Mon–Sat',        '3 cars on call'],
            ['Cox\'s Bazar',    'Daily',          '4 cars peak season'],
            ['Sundarbans',      'Day trips',      'From Khulna'],
            ['Rangamati',       'Tour add-on',    '1-day notice'],
            ['Petrapole · India','Cross-border',  'Same-day'],
            ['Phulbari · India', 'Cross-border',  '1-day notice'],
        ];

        return view('pickdrop.index', compact('services', 'fleet', 'coverage'));
    }
}
