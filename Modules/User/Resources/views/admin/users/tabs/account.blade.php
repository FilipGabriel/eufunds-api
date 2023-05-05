<div class="row">
    <div class="col-md-8">
        {{ Form::text('name', trans('user::attributes.users.name'), $errors, $user, ['required' => true]) }}
        {{ Form::email('email', trans('user::attributes.users.email'), $errors, $user, ['required' => true]) }}
        {{ Form::text('phone', trans('user::attributes.users.phone'), $errors, $user) }}
        {{ Form::text('manager_name', trans('user::attributes.users.manager_name'), $errors, $user) }}
        {{ Form::text('manager_email', trans('user::attributes.users.manager_email'), $errors, $user) }}
        
        {{ Form::select('roles', trans('user::attributes.users.roles'), $errors, $roles, $user, ['multiple' => true, 'required' => true, 'class' => 'selectize prevent-creation']) }}
        
        @if (request()->routeIs('admin.users.create'))
            {{ Form::password('password', trans('user::attributes.users.password'), $errors, null) }}
            {{ Form::password('password_confirmation', trans('user::attributes.users.password_confirmation'), $errors, null) }}
        @endif
    </div>
</div>
