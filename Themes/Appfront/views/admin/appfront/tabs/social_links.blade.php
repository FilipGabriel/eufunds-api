<div class="row">
    <div class="col-md-8">
        {{ Form::text('appfront_facebook_link', trans('appfront::attributes.appfront_facebook_link'), $errors, $settings) }}
        {{ Form::text('appfront_twitter_link', trans('appfront::attributes.appfront_twitter_link'), $errors, $settings) }}
        {{ Form::text('appfront_instagram_link', trans('appfront::attributes.appfront_instagram_link'), $errors, $settings) }}
        {{ Form::text('appfront_youtube_link', trans('appfront::attributes.appfront_youtube_link'), $errors, $settings) }}
    </div>
</div>
