<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $this->applySettings();
    }

    private function applySettings(): void
    {
        try {
            // ── Email ────────────────────────────────────────────────────────
            $email = Setting::getGroup('email');

            if (!empty($email['mail_host'])) {
                Config::set('mail.default',                           $email['mail_mailer']      ?? 'smtp');
                Config::set('mail.mailers.smtp.host',                 $email['mail_host']);
                Config::set('mail.mailers.smtp.port',           (int) ($email['mail_port']       ?? 587));
                Config::set('mail.mailers.smtp.username',             $email['mail_username']    ?? null);
                Config::set('mail.mailers.smtp.password',             $email['mail_password']    ?? null);
                Config::set('mail.mailers.smtp.encryption',           $email['mail_encryption']  ?? 'tls');
                Config::set('mail.from.address',                      $email['mail_from_address'] ?? '');
                Config::set('mail.from.name',                         $email['mail_from_name']   ?? '');
            }

            // ── bKash ────────────────────────────────────────────────────────
            $payment = Setting::getGroup('payment');

            if (!empty($payment['bkash_app_key'])) {
                Config::set('services.bkash.app_key',    $payment['bkash_app_key']);
                Config::set('services.bkash.app_secret', $payment['bkash_app_secret'] ?? '');
                Config::set('services.bkash.username',   $payment['bkash_username']   ?? '');
                Config::set('services.bkash.password',   $payment['bkash_password']   ?? '');
            }

            // ── General + Social → shared with all views ─────────────────────
            $general = Setting::getGroup('general');
            $social  = Setting::getGroup('social');

            $rawPhone    = $general['contact_phone'] ?? '+8801335111370';
            $waNumber    = preg_replace('/[^0-9]/', '', $rawPhone); // strip + and spaces

            View::share('site', [
                'name'        => $general['app_name']         ?? config('app.name', 'FlyoverBD'),
                'email'       => $general['contact_email']    ?? 'info@flyoverbd.com',
                'phone'       => $rawPhone,
                'whatsapp'    => $waNumber,
                'description' => $general['site_description'] ?? '',
                'facebook'    => $social['facebook']  ?? '',
                'instagram'   => $social['instagram'] ?? '',
                'twitter'     => $social['twitter']   ?? '',
                'youtube'     => $social['youtube']   ?? '',
                'linkedin'    => $social['linkedin']  ?? '',
            ]);

        } catch (\Throwable) {
            // DB not ready (fresh install / migration not run) — fail silently.
            View::share('site', [
                'name'     => config('app.name', 'FlyoverBD'),
                'email'    => 'info@flyoverbd.com',
                'phone'    => '+8801335111370',
                'whatsapp' => '8801335111370',
                'description' => '',
                'facebook' => '', 'instagram' => '', 'twitter' => '',
                'youtube'  => '', 'linkedin'  => '',
            ]);
        }
    }
}
