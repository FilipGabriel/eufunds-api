@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('user::users.users'))

    <li class="active">{{ trans('user::users.users') }}</li>
@endcomponent

@component('admin::components.page.index_table')
    @slot('buttons', ['create'])
    @slot('resource', 'users')
    @slot('name', trans('user::users.user'))

    <div style="text-align: center; display: flex; flex-direction: column;">
        <form method="post" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group form-inline choose-file-group">
                <label for="file">Încarcă fișierul excel (Nume, Email) &nbsp;</label>
                <input type="file" value="" class="form-control file-name" name="file" id="file" readonly="" style="padding: 8px 30px">

                <button type="submit" class="btn btn-primary form-control" data-loading>
                    Importa
                </button>
            </div>
        </form>
    </div>

    @slot('thead')
        <tr>
            @include('admin::partials.table.select_all')

            <th>{{ trans('admin::admin.table.id') }}</th>
            <th>{{ trans('user::users.table.name') }}</th>
            <th>{{ trans('user::users.table.email') }}</th>
            <th>{{ trans('user::users.table.last_login') }}</th>
            <th data-sort>{{ trans('admin::admin.table.created') }}</th>
        </tr>
    @endslot
@endcomponent

@push('scripts')
    <script>
        new DataTable('#users-table .table', {
            columns: [
                { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                { data: 'id', width: '5%' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'last_login', name: 'last_login', searchable: false },
                { data: 'created', name: 'created_at' },
                { data: 'phone', visible: false, searchable: true },
            ]
        });
    </script>
@endpush
