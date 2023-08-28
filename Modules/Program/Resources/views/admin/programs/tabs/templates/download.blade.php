<script type="text/html" id="program-download-template">
    <tr>
        <td class="text-center">
            <span class="drag-icon">
                <i class="fa">&#xf142;</i>
                <i class="fa">&#xf142;</i>
            </span>
        </td>

        <td>
            <div class="form-group">
                <label class="visible-xs">
                    {{ trans('program::programs.form.file') }}
                </label>

                <div class="choose-file-group">
                    <input
                        type="text"
                        value="<%- download.filename %>"
                        class="form-control download-name"
                        readonly
                    >

                    <span class="btn btn-default btn-choose-file" data-location="downloads">
                        {{ trans('program::programs.form.choose') }}
                    </span>

                    <input
                        type="hidden"
                        name="files[downloads][]"
                        value="<%- download.id %>"
                        class="download-file"
                    >
                </div>
            </div>
        </td>

        <td class="text-center">
            <button
                type="button"
                class="btn btn-default delete-row"
                data-toggle="tooltip"
                data-title="{{ trans('program::programs.form.delete_file') }}"
            >
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
</script>
