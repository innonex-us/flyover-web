<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        // Static hotel data for the new Hotel Booking service
        $hotels = collect([
            ['name' => 'The Pavilions Himalayas', 'location' => 'Pokhara, Nepal',     'type' => '5-star · Resort',       'price' => '18,500', 'was' => '23,400', 'rating' => '4.92', 'reviews' => 421,  'amenities' => ['Lake view', 'Spa', 'Pool', 'Free breakfast'], 'tone' => 0],
            ['name' => 'Amankora Punakha',         'location' => 'Punakha, Bhutan',    'type' => '5-star · Lodge',        'price' => '84,000', 'was' => null,     'rating' => '4.97', 'reviews' => 188,  'amenities' => ['All-inclusive', 'River view', 'Hot stone bath'], 'tone' => 2],
            ['name' => 'Soneva Jani',              'location' => 'Noonu, Maldives',    'type' => 'Overwater villa',       'price' => '1,42,000','was' => '1,68,000','rating' => '4.99','reviews' => 96,   'amenities' => ['Private pool', 'Slide', 'Half board'], 'tone' => 5],
            ['name' => 'The Lalit',                'location' => 'Srinagar, Kashmir',  'type' => '5-star · Heritage',     'price' => '12,400', 'was' => null,     'rating' => '4.78', 'reviews' => 312,  'amenities' => ['Dal Lake view', 'Pool', 'Houseboats'], 'tone' => 2],
            ['name' => 'Atlantis the Palm',        'location' => 'Dubai, UAE',         'type' => '5-star · Resort',       'price' => '26,800', 'was' => null,     'rating' => '4.84', 'reviews' => 1422, 'amenities' => ['Aquaventure pass', 'Private beach'], 'tone' => 4],
            ['name' => 'Hotel Yak & Yeti',         'location' => 'Kathmandu, Nepal',   'type' => '4-star · Central',      'price' => '6,200',  'was' => '8,400',  'rating' => '4.62', 'reviews' => 1080, 'amenities' => ['Casino', 'Pool', 'Thamel walk'], 'tone' => 0],
        ]);

        $destination = $request->input('city', '');

        return view('hotels.index', compact('hotels', 'destination'));
    }
}
