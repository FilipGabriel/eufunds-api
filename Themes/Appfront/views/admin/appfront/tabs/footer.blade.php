<div class="row">
    <div class="col-md-8">
        <div class="box-content clearfix">
        	{{ Form::text('translatable[appfront_copyright_text]', trans('appfront::attributes.appfront_copyright_text'), $errors, $settings) }}
        </div>

        <div class="box-content clearfix">
        	@include('media::admin.image_picker.single', [
        	    'title' => trans('appfront::appfront.form.accepted_payment_methods_image'),
        	    'inputName' => 'appfront_accepted_payment_methods_image',
        	    'file' => $acceptedPaymentMethodsImage,
                'location' => 'appfront'
        	])
        </div>
    </div>
</div>
