$(() => {
    let table = $("#products-table .table").DataTable();
    let url = new URL(table.ajax.url());

    const reset = function() {
        $('#category_id').val('');

        url.searchParams.delete('category_id');

        table.ajax.url(url.toString()).load();
    }

    reset();

    $('#category_id').on('change', function() {
        if ($(this).val()) {
            url.searchParams.set($(this).attr('id'), $(this).val());
        } else {
            url.searchParams.delete($(this).attr('id'));
        }

        table.ajax.url(url.toString()).load();
    });
});