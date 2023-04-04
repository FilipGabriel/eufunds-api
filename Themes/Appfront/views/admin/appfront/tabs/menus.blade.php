<div class="row">
    <div class="col-md-8">
        {{ Form::select('appfront_primary_app_menu', trans('appfront::attributes.appfront_primary_app_menu'), $errors, $menus, $settings) }}
        {{ Form::select('appfront_primary_landing_menu', trans('appfront::attributes.appfront_primary_landing_menu'), $errors, $menus, $settings) }}
        {{ Form::text('translatable[appfront_footer_menu_one_title]', trans('appfront::attributes.appfront_footer_menu_one_title'), $errors, $settings) }}
        {{ Form::select('appfront_footer_menu_one', trans('appfront::attributes.appfront_footer_menu_one'), $errors, $menus, $settings) }}
        {{ Form::text('translatable[appfront_footer_menu_two_title]', trans('appfront::attributes.appfront_footer_menu_two_title'), $errors, $settings) }}
        {{ Form::select('appfront_footer_menu_two', trans('appfront::attributes.appfront_footer_menu_two'), $errors, $menus, $settings) }}
    </div>
</div>
