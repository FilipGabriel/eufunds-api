@push('globals')
    <script>
        SMIS.maxFileSize = {{ (int) ini_get('upload_max_filesize') }}
    </script>
@endpush

<div class="row">
    <div class="col-md-12">
        <form method="POST" class="dropzone">
            {{ csrf_field() }}
            <input type="hidden" name="location" value="ftp">

            <div class="dz-message needsclick">
                {{ trans('media::media.drop_files_here') }}
            </div>
        </form>
    </div>
</div>
