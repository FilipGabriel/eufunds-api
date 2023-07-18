@include('media::admin.image_picker.single', [
    'title' => trans('brand::brands.form.logo'),
    'inputName' => 'files[logo]',
    'file' => $brand->logo,
    'location' => 'brands'
])

<div class="media-picker-divider"></div>

@include('media::admin.image_picker.single', [
    'title' => trans('brand::brands.form.banner'),
    'inputName' => 'files[banner]',
    'file' => $brand->banner,
    'location' => 'brands'
])

<div class="media-picker-divider"></div>

@include('media::admin.image_picker.single', [
    'title' => trans('brand::brands.form.slider_banner'),
    'inputName' => 'files[slider_banner]',
    'file' => $brand->slider_banner,
    'location' => 'brands'
])
