@include('media::admin.image_picker.single', [
    'title' => trans('appfront::appfront.form.newsletter_bg_image'),
    'inputName' => 'appfront_newsletter_bg_image',
    'file' => $newsletterBgImage,
    'location' => 'appfront'
])
