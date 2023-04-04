<div class="accordion-box-content">
    <div class="row">
        <div class="col-md-8">
            {{ Form::checkbox('appfront_one_column_banner_enabled', trans('appfront::attributes.section_status'), trans('appfront::appfront.form.enable_one_column_banner_section'), $errors, $settings) }}
        </div>
    </div>

    <div class="tab-content clearfix">
        <div class="panel-wrap">
            @include('admin.appfront.tabs.partials.single_banner', [
                'label' => trans('appfront::appfront.form.banner'),
                'name' => 'appfront_one_column_banner',
                'banner' => $banner,
            ])
        </div>
    </div>
</div>
