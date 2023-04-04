@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('maintenance::sidebar.info'))

    <li class="active">{{ trans('maintenance::sidebar.info') }}</li>
@endcomponent

@section('content')
{!! $content !!}
@endsection
