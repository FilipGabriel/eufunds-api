@include('media::admin.image_picker.single', [
    'title' => trans('user::users.form.user_logo'),
    'inputName' => 'files[user_logo]',
    'file' => $user->user_logo,
    'location' => 'users' 
])