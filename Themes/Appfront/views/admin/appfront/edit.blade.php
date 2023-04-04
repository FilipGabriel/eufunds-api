@extends('admin::layout')

@section('title', trans('appfront::appfront.appfront'))

@section('content_header')
    <h3>{{ trans('appfront::appfront.appfront') }}</h3>

    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}">{{ trans('admin::dashboard.dashboard') }}</a></li>
        <li class="active">{{ trans('appfront::appfront.appfront') }}</li>
    </ol>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.appfront.settings.update') }}" class="form-horizontal" id="appfront-settings-edit-form" novalidate>
        {{ csrf_field() }}
        {{ method_field('put') }}

        {!! $tabs->render(compact('settings')) !!}
    </form>
@endsection
