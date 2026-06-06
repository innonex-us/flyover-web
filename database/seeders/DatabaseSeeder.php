<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use App\Models\Visa;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\TransferRoute;

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
            'password' => bcrypt('password'), // You can change this later
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
            'inclusions' => ['3 Nights Hotel Accommodation', 'Daily Breakfast', 'Airport Transfers', 'City Tour'],
            'exclusions' => ['Airfare', 'Lunch & Dinner', 'Personal Expenses'],
            'requirements' => "1. Copy of NID card\n2. Passport Validity 6 months",
            'policy' => "80% of the fees will be refunded if the booking is canceled more than Twenty-One (21) days before the beginning of the experience/tour.",
            'hotel_details' => "3-star hotel accommodation in Bangkok and Pattaya. Rooms are air-conditioned with private bathroom, TV, and minibar. Double or twin sharing basis.",
            'additional_info' => "Best time to visit: November to February. Peak season supplements may apply during Christmas and New Year. Indian meals available on request.",
            'travel_tips' => "Carry light cotton clothes, comfortable walking shoes, sunscreen, and sunglasses. Keep copies of your passport and visa separately. Drink bottled water only.",
            'pickup_note' => "Airport pickup included from Suvarnabhumi Airport (BKK). Please provide your flight details at least 48 hours before arrival. Look for our representative holding a FlyoverBD sign at the arrival gate.",
            'travel_data' => [
                ['label' => 'Visa Requirements', 'content' => 'Visa on arrival for Bangladeshi citizens (THB 2,000 fee). Valid passport with 6 months validity required.'],
                ['label' => 'Currency', 'content' => 'Thai Baht (THB). 1 BDT ≈ 0.30 THB. Exchange available at airport and major tourist areas.'],
                ['label' => 'Time Zone', 'content' => 'GMT+7 (1 hour ahead of Bangladesh)'],
                ['label' => 'Language', 'content' => 'Thai is official. English widely spoken in tourist areas.'],
            ],
            'itinerary' => [
                ['day' => 1, 'title' => 'Arrival in Bangkok', 'activities' => ['Airport pickup and transfer to hotel', 'Check-in and free time to explore', 'Welcome dinner at Indian restaurant']],
                ['day' => 2, 'title' => 'Bangkok City Tour', 'activities' => ['Visit Grand Palace and Wat Phra Kaew', 'Cruise on Chao Phraya River', 'Shopping at MBK Center']],
                ['day' => 3, 'title' => 'Bangkok to Pattaya', 'activities' => ['Drive to Pattaya (2 hours)', 'Visit Coral Island by speedboat', 'Evening at Walking Street']],
                ['day' => 4, 'title' => 'Pattaya Adventure', 'activities' => ['Nong Nooch Tropical Garden', 'Alcazar Cabaret Show', 'Free time for beach activities']],
                ['day' => 5, 'title' => 'Departure', 'activities' => ['Free time for last-minute shopping', 'Transfer to Bangkok airport', 'Fly back home']],
            ],
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
            'inclusions' => ['Villa Stay', 'Candle Light Dinner', 'Spa Treatment', 'Island Tour'],
            'exclusions' => ['Visa Fees', 'Travel Insurance'],
            'requirements' => "1. Passport\n2. Marriage Certificate Copy",
            'hotel_details' => "Private pool villa in Seminyak with ocean views. Features king-size bed, outdoor shower, jacuzzi, and 24-hour butler service.",
            'additional_info' => "Honeymoon perks include welcome champagne, flower petal decoration, and couple's massage. Best time: April to October.",
            'travel_tips' => "Pack light summer clothes, swimwear, and reef-safe sunscreen. Respect local customs at temples (cover shoulders and knees).",
            'pickup_note' => "Airport pickup from Ngurah Rai International Airport (DPS). Private car with cold towels and refreshments.",
            'policy' => "50% refund if cancelled 14+ days before. No refund within 14 days.",
            'travel_data' => [
                ['label' => 'Visa', 'content' => 'Free visa on arrival for 30 days for Bangladeshi citizens.'],
                ['label' => 'Currency', 'content' => 'Indonesian Rupiah (IDR). 1 BDT ≈ 135 IDR.'],
                ['label' => 'Weather', 'content' => 'Tropical climate. Dry season April-October, wet season November-March.'],
            ],
            'itinerary' => [
                ['day' => 1, 'title' => 'Arrival & Romance', 'activities' => ['Airport pickup', 'Check-in to private villa', 'Sunset beach dinner']],
                ['day' => 2, 'title' => 'Island Exploration', 'activities' => ['Uluwatu Temple visit', 'Kecak fire dance', 'Seafood dinner at Jimbaran Bay']],
                ['day' => 3, 'title' => 'Adventure & Relaxation', 'activities' => ['Nusa Penida day trip', 'Couples spa treatment', 'Candlelit pool dinner']],
                ['day' => 4, 'title' => 'Departure', 'activities' => ['Breakfast in villa', 'Souvenir shopping', 'Airport transfer']],
            ],
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
            'inclusions' => ['Desert Safari', 'Dhow Cruise Dinner', 'Burj Khalifa Ticket', 'Hotel Stay'],
            'exclusions' => ['Shopping Expenses', 'Tips'],
            'hotel_details' => "4-star hotel in Deira or Bur Dubai near metro and shopping centers. Modern rooms with city views.",
            'additional_info' => "Shopping Festival dates vary yearly. Gold Souk and Mall of Emirates included in tour.",
            'travel_tips' => "Dress modestly in public areas. Carry light layers for AC indoors. Dubai Metro is efficient for shopping areas.",
            'pickup_note' => "Pickup from Dubai International Airport (DXB) Terminal 1 or 3. Metro card provided for easy travel.",
            'policy' => "Full refund 30+ days before. 50% refund 15-30 days. No refund within 15 days.",
            'requirements' => "Valid passport (6 months). UAE visa pre-approved before travel.",
            'travel_data' => [
                ['label' => 'Visa', 'content' => 'Pre-approved UAE visa required. We handle visa processing.'],
                ['label' => 'Currency', 'content' => 'UAE Dirham (AED). 1 BDT ≈ 0.033 AED.'],
                ['label' => 'Language', 'content' => 'Arabic and English widely spoken.'],
            ],
            'itinerary' => [
                ['day' => 1, 'title' => 'Arrival & Check-in', 'activities' => ['Airport pickup', 'Hotel check-in', 'Evening Dubai Mall visit']],
                ['day' => 2, 'title' => 'City Icons', 'activities' => ['Burj Khalifa observation deck', 'Dubai Fountain show', 'Gold Souk shopping']],
                ['day' => 3, 'title' => 'Desert Adventure', 'activities' => ['Morning at leisure', 'Afternoon desert safari', 'BBQ dinner with belly dance']],
                ['day' => 4, 'title' => 'Shopping & Culture', 'activities' => ['Global Village', 'Ibn Battuta Mall', 'Dhow cruise dinner']],
                ['day' => 5, 'title' => 'Departure', 'activities' => ['Last minute shopping', 'Airport transfer', 'Fly home']],
            ],
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
            'inclusions' => ['AC Bus Ticket', '3 Star Hotel Stay', 'Breakfast'],
            'exclusions' => ['Lunch', 'Dinner', 'Sightseeing Entry Fees'],
            'requirements' => "Copy of NID card",
            'policy' => "80% refund 21+ days before. 50% refund 14-21 days. 30% refund 7-14 days. No refund within 7 days.",
            'hotel_details' => "3-star beachfront hotel on Marine Drive. Sea-facing rooms with AC, TV, and attached bath. Rooftop restaurant available.",
            'additional_info' => "Best visited October to March. Himchori and Inani Beach visits included. Seafood lunches available at extra cost.",
            'travel_tips' => "Carry swimwear, sunscreen, sunglasses, and cash. ATMs are limited. Try local seafood at Shugandha Beach.",
            'pickup_note' => "Bus pickup from Dhaka (Kamalapur or Saydabad) at 10:00 PM. Hotel pickup from Cox's Bazar bus terminal included.",
            'travel_data' => [
                ['label' => 'Distance', 'content' => 'Approximately 400km from Dhaka. 8-10 hour bus journey.'],
                ['label' => 'Weather', 'content' => 'Best time October-March. Monsoon (June-September) has rough seas.'],
                ['label' => 'Local Transport', 'content' => 'CNG auto-rickshaws and tempo available. Hotel can arrange car rental.'],
            ],
            'itinerary' => [
                ['day' => 1, 'title' => 'Arrival & Beach Time', 'activities' => ['Overnight bus from Dhaka', 'Hotel check-in', 'Sunset at Laboni Beach', 'Seafood dinner']],
                ['day' => 2, 'title' => 'Exploration Day', 'activities' => ['Himchori waterfall visit', 'Inani Beach trip', 'Marine Drive sightseeing', 'Beach bonfire evening']],
                ['day' => 3, 'title' => 'Return', 'activities' => ['Sunrise photography', 'Check-out', 'Souvenir shopping', 'Return bus to Dhaka']],
            ],
        ]);

        $this->call([
            HotelSeeder::class,
            TransferRouteSeeder::class,
        ]);

        // Seed Visas with Rich Data
        $docsJobHolders = [
            "A passport valid for at least seven (7) months, along with the old passport (if applicable).",
            "Two recent photographs taken within the last 3 months (white background, photo size 35 mm x 45 mm, matt paper).",
            "Bank statements for the last six months with a bank solvency certificate (minimum balance BDT 80,000 for each applicant and BDT 200,000 for Family applications).",
            "Visiting card.",
            "No Objection Certificate (NOC) from the employer must mention the travel dates, which should fall within a 30-day period.",
            "Copy of office/organization ID card.",
            "Occupational verification document (BMDC certificate for doctors, BAR Council & BAR Association Certificate for advocates)."
        ];
        
        $docsBusinessOwner = [
             "A passport valid for at least seven (7) months, along with the old passport (if applicable).",
             "Two recent photographs taken within the last 3 months (white background, photo size 35 mm x 45 mm, matt paper).",
             "Bank statements for the last six months with a bank solvency certificate (minimum balance BDT 80,000 for each applicant and BDT 200,000 for Family applications).",
             "Copy of public notarized & updated trade license (If in Bangla, notarize the English translation and submit both versions).",
             "Memorandum for Limited Company.",
             "Blank page of the business pad.",
             "Visiting card."
        ];

        
        Visa::create([
            'country' => 'Thailand',
            'slug' => 'thailand-tourist-visa',
            'type' => 'Tourist',
            'price' => 6500,
            'fees' => 'BDT 6,500 (Without Airport Transfer)',
            'validity' => '3 Months',
            'maximum_stay' => '30 Days',
            
            'requirements' => "1. Original Passport\n2. 2 Photos (3.5x4.5cm, White Background)\n3. Bank Statement (Last 6 months)\n4. Visiting Card",
            
            'description' => 'Get your Thailand tourist visa processed hassle-free with us. We ensure accurate documentation.',
            
            'important_notes' => "It is advisable to refrain from booking flight tickets until the visa confirmation letter has been received.
GoZayaan cannot guarantee visa approval, as it depends solely on the embassy.
The applicant is advised to provide accurate documents and share only true information. Do not hide any information.
Visa processing times may vary and are determined by the embassy. GoZayaan has no authority over the visa application process.
It is your responsibility to follow visa terms and depart on time. Any overstay penalties are solely the applicant’s responsibility.
The applicant's passport must have at least seven (7) months' validity before applying for a visa. The applicant is responsible for ensuring this.
GoZayaan cannot be held accountable for any issues arising from visa refusal or rejection.
Emergency modifications or cancellations may incur extra charges.
GoZayaan is not liable for losses or damages due to visa denials, travel plan changes, or unforeseen circumstances.
For individual submissions, GoZayaan only handles the paperwork. Applicants must attend embassy interviews independently if required.
If traveling with children, ensure they have the necessary travel documents, including passports and visas, as required by the destination country.
If the applicant's visa is denied, GoZayaan may assist with understanding the reasons and appeal options.
GoZayaan reserves the right to modify these terms and conditions without prior notice.
Visa fees and charges are non-refundable.",

            'required_documents' => [
                'Job Holders' => $docsJobHolders,
                'Business Owners' => $docsBusinessOwner,
                'Students' => [
                     "A passport valid for at least seven (7) months, along with the old passport (if applicable).",
                     "Two recent photographs taken within the last 3 months (white background, photo size 35 mm x 45 mm, matt paper).",
                     "Bank statements for the last six months with a bank solvency certificate (minimum balance BDT 80,000 for each applicant and BDT 200,000 for Family applications).",
                     "Copy of student ID card (If in Bangla, notarize the English translation and submit both versions).",
                     "Birth certificate (for children and infants).",
                     "Parents / Guardian’s bank statement with solvency certificate and supporting documents"
                ],
                'Other Relations' => [
                    "A passport valid for at least seven (7) months, along with the old passport (if applicable).",
                    "Two recent photographs taken within the last 3 months (white background, photo size 35 mm x 45 mm, matt paper).",
                    "Bank statements for the last six months with a bank solvency certificate.",
                    "Marriage certificate or Nikahnama copy if not mention spouse name in passport."
                ]
            ],
            
             'thumbnail' => 'https://plus.unsplash.com/premium_photo-1674391673810-749e7bdfa5a5?q=80&w=2614&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ]);

        Visa::create([
            'country' => 'Malaysia',
            'slug' => 'malaysia-e-visa',
            'type' => 'E-Visa',
             'price' => 4500,
             'fees' => 'BDT 4,500',
            'validity' => '3 Months',
            'maximum_stay' => '30 Days',
            'requirements' => "1. Passport Scan Copy\n2. Photo (White Background)\n3. Return Ticket\n4. Hotel Booking",
            'description' => 'Quick and easy Malaysia E-Visa processing. No need to submit physical passport.',
            'thumbnail' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?q=80&w=2664&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
             'important_notes' => 'Visa fees are non-refundable.',
             'required_documents' => ['General' => ['Passport copy', 'Photo', 'Ticket']],
        ]);

         Visa::create([
            'country' => 'Singapore',
            'slug' => 'singapore-tourist-visa',
            'type' => 'Tourist',
            'price' => 4500,
            'validity' => '35 Days',
            'maximum_stay' => '30 Days',
            'requirements' => "1. Original Passport\n2. Photo (Matte Paper, White Background)\n3. Bank Solvency Certificate\n4. Invitation Letter (if any)",
            'description' => 'Apply for Singapore tourist visa with confidence.',
            'thumbnail' => 'https://plus.unsplash.com/premium_photo-1661882403996-d86b03657fcb?q=80&w=2622&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ]);
    }
}
