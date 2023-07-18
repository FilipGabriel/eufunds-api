<div class="row">
    <div class="col-md-8">
        {{ Form::text('name', trans('brand::attributes.name'), $errors, $brand, ['required' => true]) }}
        {{ Form::text('title', trans('brand::attributes.title'), $errors, $brand) }}
        {{ Form::wysiwyg('description', trans('brand::attributes.description'), $errors, $brand) }}
        {{ Form::checkbox('is_searchable', trans('brand::attributes.is_searchable'), trans('brand::brands.form.is_searchable'), $errors, $brand) }}
        {{ Form::checkbox('is_active', trans('brand::attributes.is_active'), trans('brand::brands.form.enable_the_brand'), $errors, $brand) }}
    </div>
</div>
