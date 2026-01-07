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
            'inclusions' => ['3 Nights Hotel Accommodation', 'Daily Breakfast', 'Airport Transfers', 'City Tour'],
            'exclusions' => ['Airfare', 'Lunch & Dinner', 'Personal Expenses'],
            'requirements' => "1. Copy of NID card\n2. Passport Validity 6 months",
            'policy' => "80% of the fees will be refunded if the booking is canceled more than Twenty-One (21) days before the beginning of the experience/tour.",
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
            'policy' => "Cancellation
To cancel any tour, an email has to be sent to tours@gozayaan.com mentioning the tour booking ID and details about the cancellation.
Travelers are responsible for notifying GoZayaan of any cancellations as soon as possible.
The email acts as the final application for cancellation. Phone calls to GoZayaan's hotline or contacting any team member directly will not be considered as cancellation requests.

Refund
80% of the fees will be refunded if the booking is canceled more than Twenty-One (21) days before the beginning of the experience/tour.
50% of the fees will be refunded if the booking is canceled within Fourteen (14) to Twenty-One (21) days before the beginning of the experience/tour.
30% of the tour fee will be refunded if the booking is canceled within Seven (7) to Fourteen (14) days before the beginning of the experience/tour.
Refund will not be provided if the tour is cancelled less than Seven (7) days before the beginning of the experience/tour.",
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
            'entry_type' => 'Tourist Visa(E-Visa)',
            'price' => 6500,
            'fees' => 'BDT 6,500 (Without Airport Transfer)',
            'processing_time' => '07 to 10 working days',
            'validity_info' => '07 to 10 working days',
            
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
            'entry_type' => 'Tourist E-Visa',
             'price' => 4500,
             'fees' => 'BDT 4,500',
            'processing_time' => '2-3 Working Days',
             'validity_info' => '2-3 Working Days',
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
            'processing_time' => '5-7 Working Days',
            'requirements' => "1. Original Passport\n2. Photo (Matte Paper, White Background)\n3. Bank Solvency Certificate\n4. Invitation Letter (if any)",
            'description' => 'Apply for Singapore tourist visa with confidence.',
            'thumbnail' => 'https://plus.unsplash.com/premium_photo-1661882403996-d86b03657fcb?q=80&w=2622&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ]);
    }
}
