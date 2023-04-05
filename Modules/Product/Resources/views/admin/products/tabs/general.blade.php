{{ Form::text('name', trans('product::attributes.name'), $errors, $product, ['labelCol' => 2, 'required' => true]) }}
{{ Form::wysiwyg('description', trans('product::attributes.description'), $errors, $product, ['labelCol' => 2, 'required' => true]) }}

<div class="row">
    <div class="col-md-8">
        {{ Form::text('product_id', trans('product::attributes.product_id'), $errors, $product) }}
        {{ Form::text('sku', trans('product::attributes.sku'), $errors, $product) }}
        {{ Form::select('brand_id', trans('product::attributes.brand_id'), $errors, $brands, $product) }}
        {{ Form::select('categories', trans('product::attributes.categories'), $errors, $categories, $product, ['class' => 'selectize prevent-creation', 'multiple' => true]) }}
        {{ Form::checkbox('is_active', trans('product::attributes.is_active'), trans('product::products.form.enable_the_product'), $errors, $product, ['checked' => true]) }}
    </div>
</div>
