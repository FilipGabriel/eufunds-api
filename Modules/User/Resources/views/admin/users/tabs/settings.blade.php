@if($errors->get('settings'))
<span class="help-block text-red m-b-10">Invalid setting</span>
@endif
<table class="table table-hover settings-table">
    @foreach(config('user_settings') as $setting)
    <thead>
        <tr>
            <th>{{ $setting['name'] }}</th>
            <th>Email</th>
            <th>Browser</th>
            <th>Web</th>
            <th>Mobile</th>
        </tr>
    </thead>
    <tbody>
        @foreach($setting['values'] as $key => $value)
        <tr>
            <td>{{ $value['name'] }}</td>
            <td>
            {{ Form::checkbox("settings[$key][email]", '', '', $errors, null, ['checked' => user_setting($user, $key, 'email'), 'labelCol' => 0]) }}
            </td>
            <td>
            {{ Form::checkbox("settings[$key][browser]", '', '', $errors, null, ['checked' => user_setting($user, $key, 'browser'), 'labelCol' => 0]) }}
            </td>
            <td>
            {{ Form::checkbox("settings[$key][web]", '', '', $errors, null, ['checked' => user_setting($user, $key, 'web'), 'labelCol' => 0]) }}
            </td>
            <td>
            {{ Form::checkbox("settings[$key][mobile]", '', '', $errors, null, ['checked' => user_setting($user, $key, 'mobile'), 'labelCol' => 0]) }}
            </td>
        </tr>
        @endforeach
    </tbody>
    @endforeach
</table>