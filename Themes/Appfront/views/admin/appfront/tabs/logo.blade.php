@include('media::admin.image_picker.single', [
    'title' => trans('appfront::appfront.form.favicon'),
    'inputName' => 'appfront_favicon',
    'file' => $favicon,
    'location' => 'appfront'
])

<div class="media-picker-divider"></div>

@include('media::admin.image_picker.single', [
    'title' => trans('appfront::appfront.form.header_logo'),
    'inputName' => 'translatable[appfront_header_logo]',
    'file' => $headerLogo,
    'location' => 'appfront'
])

<div class="media-picker-divider"></div>

@include('media::admin.image_picker.single', [
    'title' => trans('appfront::appfront.form.mail_logo'),
    'inputName' => 'translatable[appfront_mail_logo]',
    'file' => $mailLogo,
    'location' => 'appfront'
])
