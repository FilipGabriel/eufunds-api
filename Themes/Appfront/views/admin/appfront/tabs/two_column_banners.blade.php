<div class="accordion-box-content">
    <div class="row">
        <div class="col-md-8">
            {{ Form::checkbox('appfront_two_column_banners_enabled', trans('appfront::attributes.section_status'), trans('appfront::appfront.form.enable_two_column_banners_section'), $errors, $settings) }}
        </div>
    </div>

    <div class="tab-content clearfix">
        <div class="panel-wrap">
            @include('admin.appfront.tabs.partials.single_banner', [
                'label' => trans('appfront::appfront.form.banner_1'),
                'name' => 'appfront_two_column_banners_1',
                'banner' => $banners['banner_1'],
            ])

            @include('admin.appfront.tabs.partials.single_banner', [
                'label' => trans('appfront::appfront.form.banner_2'),
                'name' => 'appfront_two_column_banners_2',
                'banner' => $banners['banner_2'],
            ])
        </div>
    </div>
</div>
