<div class="accordion-box-content">
    <div class="tab-content clearfix">
        <div class="panel-wrap">
            @include('admin.appfront.tabs.partials.single_banner', [
                'label' => trans('appfront::appfront.form.banner_1'),
                'name' => 'appfront_slider_banner_1',
                'banner' => $banners['banner_1'],
            ])

            @include('admin.appfront.tabs.partials.single_banner', [
                'label' => trans('appfront::appfront.form.banner_2'),
                'name' => 'appfront_slider_banner_2',
                'banner' => $banners['banner_2'],
            ])
        </div>
    </div>
</div>
