<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferRoute;

class TransferRouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            [
                'name'             => 'Dhaka Airport → City Centre',
                'pickup_location'  => 'Hazrat Shahjalal International Airport, Dhaka',
                'drop_location'    => 'Dhaka City Centre (Gulshan / Dhanmondi / Motijheel)',
                'price_per_person' => 500,
                'description'      => 'Comfortable AC vehicle from Dhaka airport to any major city area. Available 24/7.',
                'thumbnail'        => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2674&auto=format&fit=crop',
                'is_active'        => true,
            ],
            [
                'name'             => "Dhaka → Cox's Bazar",
                'pickup_location'  => 'Dhaka (Any Location)',
                'drop_location'    => "Cox's Bazar Hotel Zone",
                'price_per_person' => 1800,
                'description'      => "Premium AC car service from Dhaka to Cox's Bazar. Approx 10–12 hours drive with one rest stop.",
                'thumbnail'        => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2669&auto=format&fit=crop',
                'is_active'        => true,
            ],
            [
                'name'             => "Cox's Bazar Airport → Hotel Zone",
                'pickup_location'  => "Cox's Bazar Airport",
                'drop_location'    => "Cox's Bazar Hotel Zone / Beach Road",
                'price_per_person' => 350,
                'description'      => "Quick transfer from Cox's Bazar airport to your hotel. AC sedan or microbus available.",
                'thumbnail'        => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?q=80&w=2671&auto=format&fit=crop',
                'is_active'        => true,
            ],
            [
                'name'             => 'Chittagong Airport → City',
                'pickup_location'  => 'Shah Amanat International Airport, Chittagong',
                'drop_location'    => 'Chittagong City (Agrabad / GEC / Nasirabad)',
                'price_per_person' => 450,
                'description'      => 'Reliable airport-to-city transfer in Chittagong. Fixed price, no hidden charges.',
                'thumbnail'        => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2670&auto=format&fit=crop',
                'is_active'        => true,
            ],
            [
                'name'             => 'Sylhet Airport → City / Jaflong',
                'pickup_location'  => 'Osmani International Airport, Sylhet',
                'drop_location'    => 'Sylhet City or Jaflong',
                'price_per_person' => 400,
                'description'      => 'Smooth ride from Sylhet airport to city hotels or onward to Jaflong tourist spot.',
                'thumbnail'        => 'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?q=80&w=2670&auto=format&fit=crop',
                'is_active'        => true,
            ],
            [
                'name'             => 'Dhaka → Sylhet',
                'pickup_location'  => 'Dhaka (Any Location)',
                'drop_location'    => 'Sylhet City / Jaflong / Ratargul',
                'price_per_person' => 1500,
                'description'      => 'Long-distance AC car service from Dhaka to Sylhet region. Approx 5–6 hours.',
                'thumbnail'        => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?q=80&w=2675&auto=format&fit=crop',
                'is_active'        => true,
            ],
            [
                'name'             => 'Dhaka → Bandarban',
                'pickup_location'  => 'Dhaka (Any Location)',
                'drop_location'    => 'Bandarban Town / Resort Area',
                'price_per_person' => 2000,
                'description'      => 'Scenic hill tracts journey from Dhaka to Bandarban. Comfortable AC vehicle.',
                'thumbnail'        => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2670&auto=format&fit=crop',
                'is_active'        => true,
            ],
            [
                'name'             => 'Chittagong → Saint Martin Island (Ferry Drop-off)',
                'pickup_location'  => 'Chittagong City',
                'drop_location'    => 'Teknaf Ferry Ghat (for Saint Martin ferry)',
                'price_per_person' => 1200,
                'description'      => "AC vehicle from Chittagong city to Teknaf ferry terminal for Saint Martin's Island trip.",
                'thumbnail'        => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?q=80&w=2670&auto=format&fit=crop',
                'is_active'        => true,
            ],
        ];

        foreach ($routes as $route) {
            TransferRoute::create($route);
        }
    }
}
