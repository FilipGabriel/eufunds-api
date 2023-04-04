<div class="accordion-box-content">
    <div class="row">
        <div class="col-md-8">
            {{ Form::checkbox('appfront_three_column_full_width_banners_enabled', trans('appfront::attributes.section_status'), trans('appfront::appfront.form.enable_three_column_full_width_banners_section'), $errors, $settings) }}
        </div>
    </div>

    <div class="tab-content clearfix">
        <div class="panel-wrap">
            @include('media::admin.image_picker.single', [
                'title' => trans('appfront::appfront.form.background'),
                'inputName' => 'appfront_three_column_full_width_banners_background_file_id',
                'file' => $banners['background']->image,
                'location' => 'appfront'
            ])

            @include('admin.appfront.tabs.partials.single_banner', [
                'label' => trans('appfront::appfront.form.banner_1'),
                'name' => 'appfront_three_column_full_width_banners_1',
                'banner' => $banners['banner_1'],
                'location' => 'appfront'
            ])

            @include('admin.appfront.tabs.partials.single_banner', [
                'label' => trans('appfront::appfront.form.banner_2'),
                'name' => 'appfront_three_column_full_width_banners_2',
                'banner' => $banners['banner_2'],
                'location' => 'appfront'
            ])

            @include('admin.appfront.tabs.partials.single_banner', [
                'label' => trans('appfront::appfront.form.banner_3'),
                'name' => 'appfront_three_column_full_width_banners_3',
                'banner' => $banners['banner_3'],
                'location' => 'appfront'
            ])
        </div>
    </div>
</div>
