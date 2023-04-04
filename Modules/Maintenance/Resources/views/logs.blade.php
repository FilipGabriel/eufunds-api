@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('maintenance::sidebar.logs'))

    <li class="active">{{ trans('maintenance::sidebar.logs') }}</li>
@endcomponent

@section('content')
    <select class="form-control custom-select-black" onchange="location.href=this.value" style="margin-bottom: 15px;">
        @foreach($logsDropdown as $key => $name)
            <option value="{{ $key }}" {{ $name == $logFile ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
    </select>
    <pre>{{ $logContent }}</pre>
@endsection
