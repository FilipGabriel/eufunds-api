<script type="text/html" id="program-offer-template">
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
                        value="<%- offer.filename %>"
                        class="form-control offer-name"
                        readonly
                    >

                    <span class="btn btn-default btn-choose-file" data-location="download_offers">
                        {{ trans('program::programs.form.choose') }}
                    </span>

                    <input
                        type="hidden"
                        name="files[offers][]"
                        value="<%- offer.id %>"
                        class="offer-file"
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
