<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            if (!Schema::hasTable('settings')) {
                return;
            }
        } catch (\Exception $e) {
            return; // Likely no DB connection, e.g., during initial setup
        }

        try {
            $settings = Setting::all()->keyBy('key');

            if ($settings->isNotEmpty() && $settings->has('mail_mailer') && $settings->get('mail_mailer')->value) {
                $driver = $settings->get('mail_mailer')->value;
                $mailerConfig = config("mail.mailers.{$driver}", []);

                $dbConfig = [
                    'transport'  => $driver,
                    'host'       => $settings->get('mail_host')?->value,
                    'port'       => (int) $settings->get('mail_port')?->value,
                    'encryption' => $settings->get('mail_encryption')?->value,
                    'username'   => $settings->get('mail_username')?->value,
                    'password'   => $settings->get('mail_password')?->value,
                ];

                Config::set('mail.default', $driver);
                Config::set("mail.mailers.{$driver}", array_merge($mailerConfig, array_filter($dbConfig, fn($v) => !is_null($v))));
                Config::set('mail.from.address', $settings->get('mail_from_address')?->value);
                Config::set('mail.from.name', $settings->get('mail_from_name')?->value ?? config('app.name'));
            }
        } catch (\Exception $e) {
            // Fail silently if DB is not available. It will use .env settings.
        }
    }
}
