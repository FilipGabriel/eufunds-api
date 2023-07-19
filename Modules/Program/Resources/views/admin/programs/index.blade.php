@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('program::programs.programs'))

    <li class="active">{{ trans('program::programs.programs') }}</li>
@endcomponent

@section('content')
    <div class="box box-default">
        <div class="box-body clearfix">
            <div class="col-lg-4">
                <div class="row">
                    <button class="btn btn-default add-root-program">{{ trans('program::programs.tree.add_root_program') }}</button>
                    <button class="btn btn-default add-sub-program disabled">{{ trans('program::programs.tree.add_sub_program') }}</button>

                    <div class="m-b-10">
                        <a href="#" class="collapse-all">{{ trans('program::programs.tree.collapse_all') }}</a> |
                        <a href="#" class="expand-all">{{ trans('program::programs.tree.expand_all') }}</a>
                    </div>

                    <div class="program-tree"></div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="tab-wrapper program-details-tab">
                    <ul class="nav nav-tabs">
                        <li class="general-information-tab active"><a data-toggle="tab" href="#general-information">{{ trans('program::programs.tabs.general') }}</a></li>

                        @hasAccess('admin.media.index')
                            <li class="image-tab"><a data-toggle="tab" href="#image">{{ trans('program::programs.tabs.image') }}</a></li>
                        @endHasAccess

                        <li class="seo-tab hide"><a data-toggle="tab" href="#seo">{{ trans('program::programs.tabs.seo') }}</a></li>
                    </ul>

                    <form method="POST" action="{{ route('admin.programs.store') }}" class="form-horizontal" id="program-form" novalidate>
                        {{ csrf_field() }}

                        <div class="tab-content">
                            <div id="general-information" class="tab-pane fade in active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="id-field" class="hide">
                                            {{ Form::text('id', trans('program::attributes.id'), $errors, null, ['disabled' => true, 'labelCol' => 2]) }}
                                        </div>

                                        {{ Form::text('name', trans('program::attributes.name'), $errors, null, ['required' => true, 'labelCol' => 2]) }}
                                        {{ Form::select('types', trans('program::attributes.types'), $errors, trans('program::programs.types'), null, ['class' => 'selectize prevent-creation', 'multiple' => true, 'required' => true, 'labelCol' => 2]) }}
                                        {{ Form::select('categories', trans('product::attributes.categories'), $errors, \Modules\Category\Entities\Category::treeList(), null, ['class' => 'selectize prevent-creation', 'multiple' => true, 'required' => true, 'labelCol' => 2]) }}
                                        {{ Form::select('list_categories', trans('product::attributes.list_categories'), $errors, \Modules\Category\Entities\Category::treeList(), null, ['class' => 'selectize prevent-creation', 'multiple' => true, 'labelCol' => 2]) }}
                                        {{ Form::checkbox('is_active', trans('program::attributes.is_active'), trans('program::programs.form.enable_the_program'), $errors, null, ['labelCol' => 2]) }}
                                        {{ Form::checkbox('is_searchable', trans('program::attributes.is_searchable'), trans('program::programs.form.show_this_program_in_search_box'), $errors, null, ['labelCol' => 2]) }}
                                    </div>
                                </div>
                            </div>

                            @if (auth()->user()->hasAccess('admin.media.index'))
                                <div id="image" class="tab-pane fade">
                                    <div class="banner">
                                        @include('media::admin.image_picker.single', [
                                            'title' => trans('program::programs.form.banner'),
                                            'inputName' => 'files[banner]',
                                            'file' => (object) ['exists' => false],
                                            'location' => "programs",
                                        ])
                                    </div>
                                </div>
                            @endif

                            <div id="seo" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="hide" id="slug-field">
                                            {{ Form::text('slug', trans('program::attributes.slug'), $errors) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <button type="submit" class="btn btn-primary" data-loading>
                                    {{ trans('admin::admin.buttons.save') }}
                                </button>

                                <button type="button" class="btn btn-link text-red btn-delete p-l-0 hide" data-confirm>
                                    {{ trans('admin::admin.buttons.delete') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="overlay loader hide">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div>
@endsection
