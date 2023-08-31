<?php

return [
    'settings' => 'Settings',
    'tabs' => [
        'group' => [
            'general_settings' => 'General Settings',
            'social_logins' => 'Social Logins',
            'payment_methods' => 'Payment Methods',
            'm3' => 'Measure 3',
        ],
        'general' => 'General',
        'maintenance' => 'Maintenance',
        'app' => 'App',
        'currency' => 'Currency',
        'sms' => 'SMS',
        'mail' => 'Mail',
        'newsletter' => 'Newsletter',
        'custom_css_js' => 'Custom CSS/JS',
        'facebook' => 'Facebook',
        'google' => 'Google',
        'mobilpay' => 'Netopia Payments',
        'bank_transfer' => 'Bank Transfer',
        'check_payment' => 'Check / Money Order',
    ],
    'form' => [
        'allow_reviews' => 'Allow customers to give reviews & ratings',
        'approve_reviews_automatically' => 'Customer reviews will be approved automatically',
        'approve_comments_automatically' => 'Customer comments will be approved automatically',
        'show_cookie_bar' => 'Show cookie bar in your website',
        'use_smartbill' => 'Send invoices to Smartbill',
        'privacy_settings' => 'Privacy Settings',
        'hide_app_phone' => 'Hide app phone from the appfront',
        'hide_app_email' => 'Hide app email from the appfront',
        'put_the_application_into_maintenance_mode' => 'Put the application into maintenance mode',
        'select_service' => 'Select Service',
        'enable_auto_refreshing_currency_rates' => 'Enable auto-refreshing currency rates',
        'auto_refresh_currency_rate_frequencies' => [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
        ],
        'customer_notification_settings' => 'Customer Notification Settings',
        'send_welcome_sms_after_registration' => 'Send welcome SMS after registration',
        'mail_encryption_protocols' => [
            'ssl' => 'SSL',
            'tls' => 'Tls',
        ],
        'send_welcome_email_after_registration' => 'Send welcome email after registration',
        'send_invoice_email' => 'Send invoice email to the customer after checkout',
        'allow_customers_to_subscribe' => 'Allow customers to subscribe to your newsletter',
        'enable_facebook_login' => 'Enable Facebook Login',
        'enable_google_login' => 'Enable Google Login',
        'enable_mobilpay' => 'Enable Netopia Payments',
        'use_sandbox_for_test_payments' => 'Use sandbox for test payments',
        'enable_bank_transfer' => 'Enable Bank Transfer',
        'add_vat_to_plans' => 'Add VAT to Plans',
        'vat_value' => 'VAT Value',
        'update_old_products_on_import' => 'Update old products on import',
    ],
    'validation' => [
        'sqlite_is_not_installed' => 'SQLite is not installed.',
    ],
];
