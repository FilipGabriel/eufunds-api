<script>
    window.SMIS = {
        version: '{{ smis_version() }}',
        csrfToken: '{{ csrf_token() }}',
        baseUrl: '{{ url('/') }}',
        rtl: {{ is_rtl() ? 'true' : 'false' }},
        langs: {},
        data: {},
        errors: {},
        selectize: [],
    };

    SMIS.langs['admin::admin.buttons.show'] = '{{ trans('admin::admin.buttons.show') }}';
    SMIS.langs['admin::admin.buttons.hide'] = '{{ trans('admin::admin.buttons.hide') }}';
    SMIS.langs['admin::admin.buttons.delete'] = '{{ trans('admin::admin.buttons.delete') }}';
    SMIS.langs['media::media.file_manager.title'] = '{{ trans('media::media.file_manager.title') }}';
    SMIS.langs['media::messages.image_has_been_added'] = '{{ trans('media::messages.image_has_been_added') }}';
</script>

@stack('globals')

@routes
