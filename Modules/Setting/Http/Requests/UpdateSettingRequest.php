<?php

namespace Modules\Setting\Http\Requests;

use Modules\Support\Locale;
use Modules\Support\Country;
use Modules\Support\TimeZone;
use Modules\Currency\Currency;
use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\Request;

class UpdateSettingRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'setting::attributes';

    /**
     * Array of attributes that should be merged with null
     * if attribute is not found in the current request.
     *
     * @var array
     */
    private $shouldCheck = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supported_countries.*' => ['required', Rule::in(Country::codes())],
            'default_country' => 'required|in_array:supported_countries.*',
            'supported_locales.*' => ['required', Rule::in(Locale::codes())],
            'default_locale' => 'required|in_array:supported_locales.*',
            'default_timezone' => ['required', Rule::in(TimeZone::all())],
            'customer_role' => ['required', Rule::exists('roles', 'id')],
            'supported_currencies.*' => ['required', Rule::in(Currency::codes())],
            'default_currency' => 'required|in_array:supported_currencies.*',

            'translatable.app_name' => 'required',
            'app_phone' => ['required'],
            'app_email' => 'required|email',
            'app_country' => ['required', Rule::in(Country::codes())],

            'fixer_access_key' => 'required_if:currency_rate_exchange_service,fixer',
            'forge_api_key' => 'required_if:currency_rate_exchange_service,forge',
            'currency_data_feed_api_key' => 'required_if:currency_rate_exchange_service,currency_data_feed',
            'auto_refresh_currency_rates' => 'required|boolean',
            'auto_refresh_currency_rate_frequency' => ['required_if:auto_refresh_currency_rates,1', Rule::in($this->refreshFrequencies())],

            'mail_from_address' => 'nullable|email',
            'mail_encryption' => ['nullable', Rule::in($this->mailEncryptionProtocols())],
        ];
    }

    /**
     * Returns currency rate refresh frequencies..
     *
     * @return array
     */
    private function refreshFrequencies()
    {
        return array_keys(trans('setting::settings.form.auto_refresh_currency_rate_frequencies'));
    }

    /**
     * Returns mail encryption protocols.
     *
     * @return array
     */
    private function mailEncryptionProtocols()
    {
        return array_keys(trans('setting::settings.form.mail_encryption_protocols'));
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        foreach ($this->shouldCheck as $attribute) {
            if (! $this->has($attribute)) {
                $this->merge([$attribute => null]);
            }
        }

        return $this->all();
    }
}
