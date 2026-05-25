<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\HotelRoom;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            [
                'name'        => 'The Long Beach Hotel',
                'slug'        => 'the-long-beach-hotel',
                'location'    => "Cox's Bazar",
                'description' => 'Beachfront property with stunning sea views, rooftop pool, and direct beach access. Ideal for family and couple getaways.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2670&auto=format&fit=crop',
                'amenities'   => ['Free WiFi', 'Rooftop Pool', 'Beach Access', 'Restaurant', 'Gym', 'Parking'],
                'star_rating' => 4.5,
                'is_active'   => true,
                'rooms' => [
                    ['name' => 'Standard Sea View', 'room_type' => 'Standard', 'price_per_night' => 3500, 'capacity' => 2, 'description' => 'Comfortable room with partial sea view.'],
                    ['name' => 'Deluxe Sea View', 'room_type' => 'Deluxe', 'price_per_night' => 5500, 'capacity' => 2, 'description' => 'Spacious room with full sea-facing balcony.'],
                    ['name' => 'Family Suite', 'room_type' => 'Suite', 'price_per_night' => 9000, 'capacity' => 4, 'description' => 'Two connected rooms with ocean view and living area.'],
                ],
            ],
            [
                'name'        => 'Radisson Blu Dhaka Water Garden',
                'slug'        => 'radisson-blu-dhaka-water-garden',
                'location'    => 'Dhaka',
                'description' => 'International 5-star hotel in Dhaka with lush gardens, multiple restaurants, spa, and state-of-the-art conference facilities.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?q=80&w=2670&auto=format&fit=crop',
                'amenities'   => ['Free WiFi', 'Swimming Pool', 'Spa', 'Multiple Restaurants', 'Business Center', 'Airport Shuttle'],
                'star_rating' => 5.0,
                'is_active'   => true,
                'rooms' => [
                    ['name' => 'Superior Room', 'room_type' => 'Standard', 'price_per_night' => 8500, 'capacity' => 2, 'description' => 'Modern room with garden or pool view.'],
                    ['name' => 'Business Class Room', 'room_type' => 'Deluxe', 'price_per_night' => 12000, 'capacity' => 2, 'description' => 'Includes lounge access and premium amenities.'],
                    ['name' => 'Executive Suite', 'room_type' => 'Suite', 'price_per_night' => 22000, 'capacity' => 3, 'description' => 'Separate living area, kitchenette, butler service.'],
                ],
            ],
            [
                'name'        => 'Rose View Hotel Sylhet',
                'slug'        => 'rose-view-hotel-sylhet',
                'location'    => 'Sylhet',
                'description' => 'Premier hotel in Sylhet city with tea garden views, indoor pool, and easy access to Jaflong and Ratargul.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2670&auto=format&fit=crop',
                'amenities'   => ['Free WiFi', 'Indoor Pool', 'Restaurant', 'Room Service', 'Parking', 'Garden View'],
                'star_rating' => 4.0,
                'is_active'   => true,
                'rooms' => [
                    ['name' => 'Standard Room', 'room_type' => 'Standard', 'price_per_night' => 2800, 'capacity' => 2, 'description' => 'Clean and cozy with city view.'],
                    ['name' => 'Deluxe Room', 'room_type' => 'Deluxe', 'price_per_night' => 4200, 'capacity' => 2, 'description' => 'Larger room with tea garden view.'],
                    ['name' => 'Junior Suite', 'room_type' => 'Suite', 'price_per_night' => 7500, 'capacity' => 3, 'description' => 'Separate living area and private balcony.'],
                ],
            ],
            [
                'name'        => 'Tiger Garden International Hotel',
                'slug'        => 'tiger-garden-international-hotel',
                'location'    => 'Sundarbans, Khulna',
                'description' => 'Eco-friendly resort at the gateway to the Sundarbans mangrove forest. Perfect for nature lovers and adventure seekers.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=2625&auto=format&fit=crop',
                'amenities'   => ['Free WiFi', 'Restaurant', 'Jungle Safari Desk', 'Boat Tours', 'Parking', 'Open Terrace'],
                'star_rating' => 3.5,
                'is_active'   => true,
                'rooms' => [
                    ['name' => 'Forest View Room', 'room_type' => 'Standard', 'price_per_night' => 2200, 'capacity' => 2, 'description' => 'Cozy room overlooking mangrove trees.'],
                    ['name' => 'Deluxe Cottage', 'room_type' => 'Deluxe', 'price_per_night' => 3800, 'capacity' => 3, 'description' => 'Private cottage with terrace and nature view.'],
                ],
            ],
            [
                'name'        => 'Bandarban Hill Resort',
                'slug'        => 'bandarban-hill-resort',
                'location'    => 'Bandarban',
                'description' => 'Nestled in the Chittagong Hill Tracts with panoramic mountain views, traditional architecture, and trekking facilities.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=2670&auto=format&fit=crop',
                'amenities'   => ['Free WiFi', 'Restaurant', 'Trekking Guide', 'Bonfire Area', 'Parking'],
                'star_rating' => 3.5,
                'is_active'   => true,
                'rooms' => [
                    ['name' => 'Hill View Room', 'room_type' => 'Standard', 'price_per_night' => 2500, 'capacity' => 2, 'description' => 'Comfortable room with mountain valley view.'],
                    ['name' => 'Premium Bungalow', 'room_type' => 'Deluxe', 'price_per_night' => 4500, 'capacity' => 4, 'description' => 'Wooden bungalow with panoramic hill view.'],
                ],
            ],
            [
                'name'        => 'Blue Marine Resort',
                'slug'        => 'blue-marine-resort-saint-martin',
                'location'    => "Saint Martin's Island",
                'description' => "Boutique beachside resort on Bangladesh's only coral island. Crystal clear water, fresh seafood, and stunning sunsets.",
                'thumbnail'   => 'https://images.unsplash.com/photo-1537953773345-d172ccf13cf1?q=80&w=2670&auto=format&fit=crop',
                'amenities'   => ['Free WiFi', 'Private Beach', 'Seafood Restaurant', 'Snorkeling', 'Coral View Rooms'],
                'star_rating' => 4.0,
                'is_active'   => true,
                'rooms' => [
                    ['name' => 'Coral View Room', 'room_type' => 'Standard', 'price_per_night' => 3200, 'capacity' => 2, 'description' => 'Direct view of the coral beach.'],
                    ['name' => 'Beach Cottage', 'room_type' => 'Deluxe', 'price_per_night' => 5800, 'capacity' => 2, 'description' => 'Private cottage steps from the beach.'],
                    ['name' => 'Honeymoon Suite', 'room_type' => 'Suite', 'price_per_night' => 8500, 'capacity' => 2, 'description' => 'Romantic suite with ocean-facing jacuzzi.'],
                ],
            ],
        ];

        foreach ($hotels as $data) {
            $rooms = $data['rooms'];
            unset($data['rooms']);

            $hotel = Hotel::create($data);

            foreach ($rooms as $room) {
                HotelRoom::create(array_merge($room, ['hotel_id' => $hotel->id, 'is_active' => true]));
            }
        }
    }
}
