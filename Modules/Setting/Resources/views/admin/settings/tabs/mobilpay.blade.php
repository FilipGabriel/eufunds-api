<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('mobilpay_enabled', trans('setting::attributes.mobilpay_enabled'), trans('setting::settings.form.enable_mobilpay'), $errors, $settings) }}
        {{ Form::text('translatable[mobilpay_label]', trans('setting::attributes.translatable.mobilpay_label'), $errors, $settings, ['required' => true]) }}
        {{ Form::textarea('translatable[mobilpay_description]', trans('setting::attributes.translatable.mobilpay_description'), $errors, $settings, ['rows' => 3, 'required' => true]) }}
        {{ Form::checkbox('mobilpay_test_mode', trans('setting::attributes.mobilpay_test_mode'), trans('setting::settings.form.use_sandbox_for_test_payments'), $errors, $settings) }}
    </div>
</div>