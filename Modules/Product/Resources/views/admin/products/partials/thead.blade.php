<tr>
    <th></th>
    <th></th>
    <th></th>
    <th class="text-center">
        <select id="category_id" class="form-control custom-select-black w-100">
            <option value="">{{ trans('product::products.table.please_select') }}</option>
            @foreach(Modules\Category\Entities\Category::treeList() as $key => $name)
            <option value="{{ $key }}">{{ $name }}</option>
            @endforeach
        </select>
    </th>
    <th></th>
    <th></th>
    <th></th>
</tr>

<tr>
    @include('admin::partials.table.select_all')

    <th>{{ trans('admin::admin.table.id') }}</th>
    <th>{{ trans('product::products.table.thumbnail') }}</th>
    <th>{{ trans('product::products.table.name') }}</th>
    <th>{{ trans('product::products.table.price') }}</th>
    <th>{{ trans('admin::admin.table.status') }}</th>
    <th data-sort>{{ trans('admin::admin.table.created') }}</th>
</tr>
