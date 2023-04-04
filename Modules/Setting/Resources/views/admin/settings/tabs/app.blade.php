<div class="row">
    <div class="col-md-8">
        <div class="box-content clearfix">
            {{ Form::text('translatable[app_name]', trans('setting::attributes.translatable.app_name'), $errors, $settings, ['required' => true]) }}
            {{ Form::text('app_email', trans('setting::attributes.app_email'), $errors, $settings, ['required' => true]) }}
            {{ Form::text('app_phone', trans('setting::attributes.app_phone'), $errors, $settings, ['required' => true]) }}
            {{ Form::select('app_country', trans('setting::attributes.app_country'), $errors, $countries, $settings) }}
        </div>
    </div>
</div>
