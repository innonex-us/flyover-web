<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use App\Models\Visa;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@flyoverbd.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Regular User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Seed Packages
        Package::create([
            'title' => 'Amazing Thailand Tour',
            'slug' => 'amazing-thailand-tour',
            'description' => 'Experience the beauty of Thailand with our 5-day tour package covering Bangkok and Pattaya. Includes hotel, transfers, and sightseeing.',
            'price' => 25000,
            'duration_days' => 5,
            'location' => 'Thailand',
            'thumbnail' => 'https://plus.unsplash.com/premium_photo-1661919589683-f11880119fb7?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3',
            'is_active' => true,
        ]);

        Package::create([
            'title' => 'Bali Honeymoon Special',
            'slug' => 'bali-honeymoon-special',
            'description' => 'A romantic getaway to Bali. Enjoy sunset dinners, beach walks, and luxurious villa stay.',
            'price' => 45000,
            'duration_days' => 4,
            'location' => 'Indonesia',
            'thumbnail' => 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?q=80&w=2688&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'is_active' => true,
        ]);

         Package::create([
            'title' => 'Dubai Shopping Festival',
            'slug' => 'dubai-shopping-festival',
            'description' => 'Shop till you drop in Dubai! Visit Burj Khalifa, Desert Safari, and enjoy the shopping festival.',
            'price' => 60000,
            'duration_days' => 5,
            'location' => 'UAE',
            'thumbnail' => 'https://images.unsplash.com/photo-1546412414-e1885259563a?q=80&w=2574&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'is_active' => true,
        ]);

          Package::create([
            'title' => 'Cox\'s Bazar Beach Retreat',
            'slug' => 'coxs-bazar-beach-retreat',
            'description' => 'Relax at the world\'s longest natural sea beach. 3 days of sun, sand, and seafood.',
            'price' => 8000,
            'duration_days' => 3,
            'location' => 'Bangladesh',
            'thumbnail' => 'https://images.unsplash.com/photo-1599579737526-f7f6fd918239?q=80&w=2669&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'is_active' => true,
        ]);

        // Seed Visas
        Visa::create([
            'country' => 'Thailand',
            'slug' => 'thailand-tourist-visa',
            'type' => 'Tourist',
            'price' => 5500,
            'processing_time' => '5-7 Working Days',
            'requirements' => "1. Original Passport\n2. 2 Photos (3.5x4.5cm, White Background)\n3. Bank Statement (Last 6 months)\n4. Visiting Card",
            'description' => 'Get your Thailand tourist visa processed hassle-free with us. We ensure accurate documentation.',
             'thumbnail' => 'https://plus.unsplash.com/premium_photo-1674391673810-749e7bdfa5a5?q=80&w=2614&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ]);

        Visa::create([
            'country' => 'Malaysia',
            'slug' => 'malaysia-e-visa',
            'type' => 'E-Visa',
            'price' => 4500,
            'processing_time' => '2-3 Working Days',
            'requirements' => "1. Passport Scan Copy\n2. Photo (White Background)\n3. Return Ticket\n4. Hotel Booking",
            'description' => 'Quick and easy Malaysia E-Visa processing. No need to submit physical passport.',
            'thumbnail' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?q=80&w=2664&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ]);

         Visa::create([
            'country' => 'Singapore',
            'slug' => 'singapore-tourist-visa',
            'type' => 'Tourist',
            'price' => 4500,
            'processing_time' => '5-7 Working Days',
            'requirements' => "1. Original Passport\n2. Photo (Matte Paper, White Background)\n3. Bank Solvency Certificate\n4. Invitation Letter (if any)",
            'description' => 'Apply for Singapore tourist visa with confidence.',
            'thumbnail' => 'https://plus.unsplash.com/premium_photo-1661882403996-d86b03657fcb?q=80&w=2622&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ]);
    }
}
