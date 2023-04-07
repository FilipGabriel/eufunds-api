@extends('admin::layout')

@section('title', trans('admin::dashboard.dashboard'))

@section('content_header')
    <h2 class="pull-left">{{ trans('admin::dashboard.dashboard') }}</h2>
@endsection

@section('content')
    <div class="grid clearfix">
        <div class="row">
            @hasAccess('admin.users.index')
                @include('admin::dashboard.grids.total_sales')
                @include('admin::dashboard.grids.total_orders')
            @endHasAccess

            @hasAccess('admin.products.index')
                @include('admin::dashboard.grids.total_products')
            @endHasAccess

            @hasAccess('admin.users.index')
                @include('admin::dashboard.grids.total_customers')
            @endHasAccess
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            @include('admin::dashboard.panels.latest_orders')
        </div>
        <div class="col-lg-6">
            @include('admin::dashboard.panels.latest_search_terms')
        </div>
    </div>
@endsection
