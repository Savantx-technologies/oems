<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $settings = Setting::all()->keyBy('key')->map(fn($setting) => $setting->value);

            if ($settings->isNotEmpty()) {
                $config = [
                    'mail.default' => $settings->get('mail_mailer', config('mail.default')),
                    'mail.mailers.smtp.host' => $settings->get('mail_host', config('mail.mailers.smtp.host')),
                    'mail.mailers.smtp.port' => $settings->get('mail_port', config('mail.mailers.smtp.port')),
                    'mail.mailers.smtp.encryption' => $settings->get('mail_encryption', config('mail.mailers.smtp.encryption')),
                    'mail.mailers.smtp.username' => $settings->get('mail_username', config('mail.mailers.smtp.username')),
                    'mail.from.address' => $settings->get('mail_from_address', config('mail.from.address')),
                    'mail.from.name' => $settings->get('mail_from_name', config('mail.from.name')),
                ];

                if ($settings->has('mail_password') && !is_null($settings->get('mail_password'))) {
                    $config['mail.mailers.smtp.password'] = $settings->get('mail_password');
                }

                Config::set($config);
            }
        }
    }
}
