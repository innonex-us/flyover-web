<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    private array $groups = ['general', 'email', 'security', 'payment', 'social', 'stats', 'banners'];

    private array $defaults = [
        'general' => [
            'app_name'         => 'FlyoverBD',
            'app_url'          => 'https://flyoverbd.com',
            'contact_email'    => 'info@flyoverbd.com',
            'contact_phone'    => '+8801234567890',
            'site_description' => 'Your trusted travel partner for amazing journeys',
            'timezone'         => 'Asia/Dhaka',
            'language'         => 'en',
            'maintenance_mode' => '0',
        ],
        'email' => [
            'mail_mailer'       => 'smtp',
            'mail_host'         => 'smtp.gmail.com',
            'mail_port'         => '587',
            'mail_username'     => '',
            'mail_password'     => '',
            'mail_encryption'   => 'tls',
            'mail_from_address' => 'noreply@flyoverbd.com',
            'mail_from_name'    => 'FlyoverBD',
        ],
        'security' => [
            'force_https'         => '0',
            'session_lifetime'    => '120',
            'two_factor_admin'    => '0',
            'password_min_length' => '1',
            'password_uppercase'  => '1',
            'password_numbers'    => '1',
            'password_symbols'    => '0',
        ],
        'payment' => [
            'currency'           => 'BDT',
            'bkash_app_key'      => '',
            'bkash_app_secret'   => '',
            'bkash_username'     => '',
            'bkash_password'     => '',
            'ssl_store_id'       => '',
            'ssl_store_password' => '',
        ],
        'social' => [
            'facebook'  => '',
            'twitter'   => '',
            'instagram' => '',
            'linkedin'  => '',
            'youtube'   => '',
            'whatsapp'  => '',
        ],
        'stats' => [
            'stat_rating'                => '4.8',
            'stat_visa_approval_rate'    => '94.2',
            'stat_travellers_offset'     => '0',
            'stat_travellers_display'    => '1.2M+',
            'stat_destinations'          => '62',
        ],
        'banners' => [
            'enabled'       => '0',
            'type'          => 'promo',
            'title'         => 'Special Offer!',
            'message'       => 'Get 20% off on all bookings this week. Use code SUMMER20',
            'button_text'   => 'Book Now',
            'link'          => '/tours',
            'image'         => '',
            'delay'         => '1',
            'frequency'     => 'once',
            'pages'         => 'home',
        ],
    ];

    public function show(string $group = 'general')
    {
        if (!in_array($group, $this->groups)) {
            $group = 'general';
        }

        $settings = [];
        foreach ($this->groups as $g) {
            $saved = Setting::getGroup($g);
            $settings[$g] = array_merge($this->defaults[$g], $saved);
        }

        return view('admin.settings', compact('settings', 'group'));
    }

    public function update(Request $request, string $group = 'general')
    {
        if (!in_array($group, $this->groups)) {
            $group = 'general';
        }

        $allowed = array_keys($this->defaults[$group] ?? []);
        $data    = $request->only($allowed);

        // Handle banner image upload
        if ($group === 'banners' && $request->hasFile('image_upload')) {
            $file = $request->file('image_upload');
            $path = $file->store('banners', 'public');
            $data['image'] = asset('storage/' . $path);
        }

        foreach ($allowed as $key) {
            $value = $data[$key] ?? ($group === 'security' ? '0' : '');
            Setting::set($key, $value, $group);
        }

        if ($group === 'stats') {
            Cache::forget('site_stats');
        }

        return redirect()->route('admin.settings.show', $group)
            ->with('success', ucfirst($group) . ' settings saved successfully.');
    }
}
