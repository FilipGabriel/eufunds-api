<?php

namespace Modules\Setting\Admin;

use Modules\Admin\Ui\Tab;
use Modules\Admin\Ui\Tabs;
use Modules\Support\Locale;
use Modules\Support\Country;
use Modules\Support\TimeZone;
use Modules\Currency\Currency;
use Modules\User\Entities\Role;

class SettingTabs extends Tabs
{
    /**
     * Make new tabs with groups.
     *
     * @return void
     */
    public function make()
    {
        $this->group('general_settings', trans('setting::settings.tabs.group.general_settings'))
            ->active()
            ->add($this->general())
            ->add($this->maintenance())
            ->add($this->app())
            ->add($this->currency())
            ->add($this->mail())
            ->add($this->newsletter())
            ->add($this->customCssJs());

        $this->group('social_logins', trans('setting::settings.tabs.group.social_logins'))
            ->add($this->facebook())
            ->add($this->google());

        $this->group('payment_methods', trans('setting::settings.tabs.group.payment_methods'))
            ->add($this->bankTransfer())
            ->add($this->mobilpay());
    }

    private function general()
    {
        return tap(new Tab('general', trans('setting::settings.tabs.general')), function (Tab $tab) {
            $tab->active();
            $tab->weight(5);

            $tab->fields([
                'supported_countries.*',
                'default_country',
                'supported_locales.*',
                'default_locale',
                'default_timezone',
                'customer_role',
            ]);

            $tab->view('setting::admin.settings.tabs.general', [
                'locales' => Locale::all(),
                'countries' => Country::all(),
                'timeZones' => TimeZone::all(),
                'roles' => Role::list(),
            ]);
        });
    }

    private function maintenance()
    {
        return tap(new Tab('maintenance', trans('setting::settings.tabs.maintenance')), function (Tab $tab) {
            $tab->weight(7);
            $tab->view('setting::admin.settings.tabs.maintenance');
        });
    }

    private function app()
    {
        return tap(new Tab('app', trans('setting::settings.tabs.app')), function (Tab $tab) {
            $tab->weight(10);

            $tab->fields([
                'translatable.app_name',
                'app_phone',
                'app_email',
                'app_address_1',
                'app_address_2',
                'app_city',
                'app_country',
                'app_state',
                'app_zip',
            ]);

            $tab->view('setting::admin.settings.tabs.app', [
                'countries' => Country::all(),
            ]);
        });
    }

    private function currency()
    {
        return tap(new Tab('currency', trans('setting::settings.tabs.currency')), function (Tab $tab) {
            $tab->weight(20);

            $tab->fields([
                'supported_currencies.*',
                'default_currency',
                'currency_rate_exchange_service',
                'fixer_access_key',
                'forge_api_key',
                'currency_data_feed_api_key',
                'auto_refresh_currency_rates',
                'auto_refresh_currency_rate_frequency',
            ]);

            $tab->view('setting::admin.settings.tabs.currency', [
                'currencies' => Currency::names(),
                'currencyRateExchangeServices' => $this->getCurrencyRateExchangeServices(),
            ]);
        });
    }

    private function getCurrencyRateExchangeServices()
    {
        $currencyRateExchangeServices = ['' => trans('setting::settings.form.select_service')];

        return $currencyRateExchangeServices += trans('currency::services');
    }

    private function mail()
    {
        return tap(new Tab('mail', trans('setting::settings.tabs.mail')), function (Tab $tab) {
            $tab->weight(30);
            $tab->fields(['mail_from_address']);
            $tab->view('setting::admin.settings.tabs.mail', [
                'encryptionProtocols' => $this->getMailEncryptionProtocols(),
                'orderStatuses' => trans('order::statuses'),
            ]);
        });
    }

    private function getMailEncryptionProtocols()
    {
        return ['' => trans('admin::admin.form.please_select')] + trans('setting::settings.form.mail_encryption_protocols');
    }

    private function newsletter()
    {
        return tap(new Tab('newsletter', trans('setting::settings.tabs.newsletter')), function (Tab $tab) {
            $tab->weight(32);
            $tab->fields(['newsletter_enabled', 'mailchimp_api_key', 'mailchimp_list_id']);
            $tab->view('setting::admin.settings.tabs.newsletter');
        });
    }

    private function customCssJs()
    {
        return tap(new Tab('custom_css_js', trans('setting::settings.tabs.custom_css_js')), function (Tab $tab) {
            $tab->weight(35);
            $tab->view('setting::admin.settings.tabs.custom_css_js');
        });
    }

    private function facebook()
    {
        return tap(new Tab('facebook', trans('setting::settings.tabs.facebook')), function (Tab $tab) {
            $tab->weight(38);

            $tab->fields([
                'facebook_login_enabled',
                'translatable.facebook_login_label',
                'facebook_login_app_id',
                'facebook_login_app_secret',
            ]);

            $tab->view('setting::admin.settings.tabs.facebook');
        });
    }

    private function google()
    {
        return tap(new Tab('google', trans('setting::settings.tabs.google')), function (Tab $tab) {
            $tab->weight(39);

            $tab->fields([
                'google_login_enabled',
                'translatable.google_login_label',
                'google_login_client_id',
                'google_login_client_secret',
            ]);

            $tab->view('setting::admin.settings.tabs.google');
        });
    }

    private function bankTransfer()
    {
        return tap(new Tab('bank_transfer', trans('setting::settings.tabs.bank_transfer')), function (Tab $tab) {
            $tab->weight(41);

            $tab->fields([
                'bank_transfer_enabled',
                'translatable.bank_transfer_label',
                'translatable.bank_transfer_description',
                'translatable.bank_transfer_instructions',
            ]);

            $tab->view('setting::admin.settings.tabs.bank_transfer');
        });
    }

    private function mobilpay()
    {
        return tap(new Tab('mobilpay', trans('setting::settings.tabs.mobilpay')), function (Tab $tab) {
            $tab->weight(42);

            $tab->fields([
                'mobilpay_enabled',
                'translatable.mobilpay_label',
                'translatable.mobilpay_description',
                'mobilpay_test_mode',
            ]);

            $tab->view('setting::admin.settings.tabs.mobilpay');
        });
    }
}
