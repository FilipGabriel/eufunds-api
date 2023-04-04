<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Setting\Entities\Setting;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::setMany([
            'active_theme' => 'Appfront',
            'supported_countries' => ['BD'],
            'default_country' => 'BD',
            'supported_locales' => ['en'],
            'default_locale' => 'en',
            'default_timezone' => 'Asia/Dhaka',
            'customer_role' => 2,
            'reviews_enabled' => true,
            'auto_approve_reviews' => true,
            'cookie_bar_enabled' => true,
            'supported_currencies' => ['USD'],
            'default_currency' => 'USD',
            'app_email' => 'admin@smis.ro',
            'newsletter_enabled' => false,
            'search_engine' => 'mysql',
            'translatable' => [
                'app_name' => 'EUFunds',
            ],
            'appfront_copyright_text' => 'Copyright © <a href="{{ app_url }}">{{ app_name }}</a> {{ year }}. All rights reserved.',
        ]);
    }
}
