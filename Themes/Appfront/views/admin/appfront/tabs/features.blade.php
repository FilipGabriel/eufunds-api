<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('appfront_features_section_enabled', trans('appfront::attributes.section_status'), trans('appfront::appfront.form.enable_features_section'), $errors, $settings) }}

        <div class="clearfix"></div>

        <div class="box-content">
            <h4 class="section-title">{{ trans('appfront::appfront.form.feature_1') }}</h4>

            {{ Form::text('translatable[appfront_feature_1_title]', trans('appfront::attributes.title'), $errors, $settings) }}
            {{ Form::text('translatable[appfront_feature_1_subtitle]', trans('appfront::attributes.subtitle'), $errors, $settings) }}
            {{ Form::text('appfront_feature_1_icon', trans('appfront::attributes.icon'), $errors, $settings) }}
        </div>

        <div class="box-content">
            <h4 class="section-title">{{ trans('appfront::appfront.form.feature_2') }}</h4>

            {{ Form::text('translatable[appfront_feature_2_title]', trans('appfront::attributes.title'), $errors, $settings) }}
            {{ Form::text('translatable[appfront_feature_2_subtitle]', trans('appfront::attributes.subtitle'), $errors, $settings) }}
            {{ Form::text('appfront_feature_2_icon', trans('appfront::attributes.icon'), $errors, $settings) }}
        </div>

        <div class="box-content">
            <h4 class="section-title">{{ trans('appfront::appfront.form.feature_3') }}</h4>

            {{ Form::text('translatable[appfront_feature_3_title]', trans('appfront::attributes.title'), $errors, $settings) }}
            {{ Form::text('translatable[appfront_feature_3_subtitle]', trans('appfront::attributes.subtitle'), $errors, $settings) }}
            {{ Form::text('appfront_feature_3_icon', trans('appfront::attributes.icon'), $errors, $settings) }}
        </div>

        <div class="box-content">
            <h4 class="section-title">{{ trans('appfront::appfront.form.feature_4') }}</h4>

            {{ Form::text('translatable[appfront_feature_4_title]', trans('appfront::attributes.title'), $errors, $settings) }}
            {{ Form::text('translatable[appfront_feature_4_subtitle]', trans('appfront::attributes.subtitle'), $errors, $settings) }}
            {{ Form::text('appfront_feature_4_icon', trans('appfront::attributes.icon'), $errors, $settings) }}
        </div>

        <div class="box-content">
            <h4 class="section-title">{{ trans('appfront::appfront.form.feature_5') }}</h4>

            {{ Form::text('translatable[appfront_feature_5_title]', trans('appfront::attributes.title'), $errors, $settings) }}
            {{ Form::text('translatable[appfront_feature_5_subtitle]', trans('appfront::attributes.subtitle'), $errors, $settings) }}
            {{ Form::text('appfront_feature_5_icon', trans('appfront::attributes.icon'), $errors, $settings) }}
        </div>
    </div>
</div>
