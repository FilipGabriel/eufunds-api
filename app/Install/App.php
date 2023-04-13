<?php

namespace Smis\Install;

use Modules\User\Entities\Role;
use Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Artisan;
use Modules\Currency\Entities\CurrencyRate;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class App
{
    public function setup($data)
    {
        $this->generateAppKey();
        //$this->setEnvVariables();
        $this->createCustomerRole();
        $this->setAppSettings($data);
        $this->createDefaultCurrencyRate();
        $this->createStorageFolder();
    }

    private function generateAppKey()
    {
        Artisan::call('key:generate', ['--force' => true]);
    }

    private function setEnvVariables()
    {
        $env = DotenvEditor::load();

        $env->setKey('APP_ENV', 'production');
        $env->setKey('APP_DEBUG', 'false');
        $env->setKey('APP_CACHE', 'true');
        $env->setKey('APP_URL', url('/'));

        $env->save();
    }

    private function createCustomerRole()
    {
        Role::create(['name' => 'Customer']);
    }

    private function setAppSettings($data)
    {
        Setting::setMany([
            'active_theme' => 'Appfront',
            'supported_countries' => ['RO'],
            'default_country' => 'RO',
            'supported_locales' => ['ro'],
            'default_locale' => 'ro',
            'default_timezone' => 'Europe/Bucharest',
            'supported_currencies' => ['RON'],
            'default_currency' => 'RON',
            'currency_rate_exchange_service' => 'national_bank_of_romania',
            'auto_refresh_currency_rate_frequency' => 'daily',
            'auto_refresh_currency_rates' => false,
            'customer_role' => 2,
            'app_email' => 'administrator@eufunds.ro',
            'newsletter_enabled' => false,
            'search_engine' => 'mysql',
            'mail_from_address' => 'noreply@eufunds.ro',
            'mail_from_name' => 'EUFunds',
            'mail_host' => 'smtp.mailtrap.io',
            'mail_port' => '2525',
            'mail_username' => '31f915ed119fa4',
            'mail_password' => 'a3f4c4fa5a5202',
            'mail_encryption' => 'tls',
            'appfront_copyright_text' => 'Copyright © <a href="{{ app_url }}">{{ app_name }}</a> {{ year }}. All rights reserved.',
        ]);

        Setting::setMany([
            'translatable' => [
                'app_name' => $data['app_name'],
            ],
            'app_email' => $data['app_email'],
            'app_phone' => $data['app_phone'],
            'search_engine' => $data['search_engine'],
            'algolia_app_id' => $data['algolia_app_id'],
            'algolia_secret' => $data['algolia_secret'],
        ]);
    }

    private function createDefaultCurrencyRate()
    {
        CurrencyRate::create(['currency' => 'RON', 'rate' => 1]);
    }

    private function createStorageFolder()
    {
        if (! is_dir(public_path('storage'))) {
            mkdir(public_path('storage'));
        }
    }
}
