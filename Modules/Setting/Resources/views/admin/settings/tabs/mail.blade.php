<div class="row">
    <div class="col-md-8">
        <div class="box-content clearfix">
            {{ Form::text('mail_from_address', trans('setting::attributes.mail_from_address'), $errors, $settings) }}
            {{ Form::text('mail_from_name', trans('setting::attributes.mail_from_name'), $errors, $settings) }}
            {{ Form::text('mail_host', trans('setting::attributes.mail_host'), $errors, $settings) }}
            {{ Form::text('mail_port', trans('setting::attributes.mail_port'), $errors, $settings) }}
            {{ Form::text('mail_username', trans('setting::attributes.mail_username'), $errors, $settings) }}
            {{ Form::password('mail_password', trans('setting::attributes.mail_password'), $errors, $settings) }}
            {{ Form::select('mail_encryption', trans('setting::attributes.mail_encryption'), $errors, $encryptionProtocols, $settings) }}
        </div>

        <div class="box-content clearfix">
            <h4 class="section-title">{{ trans('setting::settings.form.customer_notification_settings') }}</h4>

            {{ Form::checkbox('welcome_email', trans('setting::attributes.welcome_email'), trans('setting::settings.form.send_welcome_email_after_registration'), $errors, $settings) }}
        </div>
    </div>
</div>
