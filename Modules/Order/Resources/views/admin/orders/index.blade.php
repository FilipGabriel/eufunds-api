@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('order::orders.orders'))

    <li class="active">{{ trans('order::orders.orders') }}</li>
@endcomponent

@component('admin::components.page.index_table')
    @slot('buttons', ['export', 'export_products'])
    @slot('resource', 'orders')
    @slot('name', trans('order::orders.orders'))

    @slot('thead')
        <tr>
            <th>{{ trans('admin::admin.table.id') }}</th>
            <th width="25%">{{ trans('order::orders.table.funding') }}</th>
            <th>{{ trans('order::orders.table.company_name') }}</th>
            <th>{{ trans('order::orders.table.customer_name') }}</th>
            <th>{{ trans('order::orders.table.customer_email') }}</th>
            <th>{{ trans('program::attributes.types') }}</th>
            <th>{{ trans('order::orders.table.total') }}</th>
            <th data-sort>{{ trans('admin::admin.table.created') }}</th>
        </tr>
    @endslot
@endcomponent

@push('scripts')
    <script>
        DataTable.setRoutes('#orders-table .table', {
            index: '{{ "admin.orders.index" }}',
            show: '{{ "admin.orders.show" }}',
        });

        new DataTable('#orders-table .table', {
            columns: [
                { data: 'id', width: '5%' },
                { data: 'funding', orderable: false, searchable: false },
                { data: 'company_name' },
                { data: 'customer_name', orderable: false, searchable: false },
                { data: 'customer_email' },
                { data: 'type' },
                { data: 'total' },
                { data: 'created', name: 'created_at' },
            ],
        });
    </script>
@endpush
