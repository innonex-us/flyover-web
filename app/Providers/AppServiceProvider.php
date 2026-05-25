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

            // ── Payment Gateways ─────────────────────────────────────────────
            $payment = Setting::getGroup('payment');

            // bKash
            if (!empty($payment['bkash_app_key'])) {
                Config::set('services.bkash.app_key',    $payment['bkash_app_key']);
                Config::set('services.bkash.app_secret', $payment['bkash_app_secret'] ?? '');
                Config::set('services.bkash.username',   $payment['bkash_username']   ?? '');
                Config::set('services.bkash.password',   $payment['bkash_password']   ?? '');
            }

            // Nagad
            if (!empty($payment['nagad_merchant_id'])) {
                Config::set('services.nagad.merchant_id',  $payment['nagad_merchant_id']);
                Config::set('services.nagad.public_key',   $payment['nagad_public_key']  ?? '');
                Config::set('services.nagad.private_key',  $payment['nagad_private_key'] ?? '');
                Config::set('services.nagad.sandbox',      ($payment['nagad_sandbox'] ?? '1') === '1');
            }

            // Rocket (DBBL)
            if (!empty($payment['rocket_merchant_number'])) {
                Config::set('services.rocket.merchant_number', $payment['rocket_merchant_number']);
                Config::set('services.rocket.api_key',         $payment['rocket_api_key']    ?? '');
                Config::set('services.rocket.api_secret',      $payment['rocket_api_secret'] ?? '');
            }

            // SSL Commerce
            if (!empty($payment['ssl_store_id'])) {
                Config::set('services.sslcommerz.store_id',       $payment['ssl_store_id']);
                Config::set('services.sslcommerz.store_password',  $payment['ssl_store_password'] ?? '');
                Config::set('services.sslcommerz.sandbox',         ($payment['ssl_sandbox'] ?? '1') === '1');
            }

            // Stripe
            if (!empty($payment['stripe_secret_key'])) {
                Config::set('services.stripe.key',            $payment['stripe_publishable_key'] ?? '');
                Config::set('services.stripe.secret',         $payment['stripe_secret_key']);
                Config::set('services.stripe.webhook_secret', $payment['stripe_webhook_secret']  ?? '');
            }

            // PayPal
            if (!empty($payment['paypal_client_id'])) {
                Config::set('services.paypal.client_id',     $payment['paypal_client_id']);
                Config::set('services.paypal.client_secret', $payment['paypal_client_secret'] ?? '');
                Config::set('services.paypal.mode',          $payment['paypal_mode']          ?? 'sandbox');
            }

            // Razorpay
            if (!empty($payment['razorpay_key_id'])) {
                Config::set('services.razorpay.key_id',     $payment['razorpay_key_id']);
                Config::set('services.razorpay.key_secret', $payment['razorpay_key_secret'] ?? '');
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
