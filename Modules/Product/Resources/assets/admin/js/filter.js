let filter = {}

$(() => {
    let category_id = localStorage.getItem("category_id")
    let table = $("#products-table .table").DataTable();
    let url = new URL(table.ajax.url());

    if(category_id) {
        filter.category_id = category_id
        $('#category_id').val(category_id)
    }

    // const reset = function() {
    //     $('#category_id').val('');

    //     url.searchParams.delete('category_id');

    //     table.ajax.url(url.toString()).load();
    // }

    // reset();

    $('#category_id').on('change', function() {
        if ($(this).val()) {
            localStorage.setItem("category_id", $(this).val())
            url.searchParams.set($(this).attr('id'), $(this).val());
        } else {
            localStorage.removeItem("category_id")
            url.searchParams.delete($(this).attr('id'));
        }

        table.ajax.url(url.toString()).load();
    });

    $('#category_id').trigger('change')
});