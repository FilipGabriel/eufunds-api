<div class="row">
    <div class="col-md-8">
        {{ Form::text('name', trans('user::attributes.users.name'), $errors, $currentUser, ['required' => true]) }}
        {{ Form::email('email', trans('user::attributes.users.email'), $errors, $currentUser, ['required' => true]) }}
    </div>
</div>
