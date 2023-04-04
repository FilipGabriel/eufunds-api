<div class="row">
    <div class="col-md-8">
        {{ Form::select('appfront_theme_color', trans('appfront::attributes.appfront_theme_color'), $errors, trans('appfront::themes'), $settings) }}

        <div class="{{ old('appfront_theme_color', array_get($settings, 'appfront_theme_color')) === 'custom_color' ? '' : 'hide' }}" id="custom-theme-color">
            {{ Form::color('appfront_custom_theme_color', trans('appfront::attributes.appfront_custom_theme_color'), $errors, $settings) }}
        </div>

        {{ Form::select('appfront_mail_theme_color', trans('appfront::attributes.appfront_mail_theme_color'), $errors, trans('appfront::themes'), $settings) }}

        <div class="{{ old('appfront_mail_theme_color', array_get($settings, 'appfront_mail_theme_color')) === 'custom_color' ? '' : 'hide' }}" id="custom-mail-theme-color">
            {{ Form::color('appfront_custom_mail_theme_color', trans('appfront::attributes.appfront_custom_mail_theme_color'), $errors, $settings) }}
        </div>

        {{ Form::text('translatable[appfront_address]', trans('appfront::attributes.appfront_address'), $errors, $settings) }}
    </div>
</div>
