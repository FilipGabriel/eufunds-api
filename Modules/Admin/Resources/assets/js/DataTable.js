// Initialize state holders.
SMIS.dataTable = { routes: {}, selected: {} };

export default class {
    constructor(selector, options, callback) {
        this.selector = selector;
        this.element = $(selector);

        if (SMIS.dataTable.selected[selector] === undefined) {
            SMIS.dataTable.selected[selector] = [];
        }

        this.initiateDataTable(options, callback);

        this.addErrorHandler();
        this.registerTableProcessingPlugin();
    }

    initiateDataTable(options, callback) {
        let sortColumn = this.element.find('th[data-sort]');
        let params = options['params'] ? options['params'] : { table: true };
        delete options['params'];

        this.element.dataTable(_.merge({
            serverSide: true,
            processing: true,
            ajax: this.route('index', params),
            stateSave: true,
            sort: true,
            info: true,
            filter: true,
            lengthChange: true,
            paginate: true,
            autoWidth: false,
            pageLength: 20,
            lengthMenu: [10, 20, 50, 100, 200],
            language: { processing: '<i class="fa fa-refresh fa-spin"></i>' },
            order: [
                sortColumn.index() !== -1 ? sortColumn.index() : 1,
                sortColumn.data('sort') || 'desc',
            ],
            initComplete: () => {
                if (this.hasRoute('destroy')) {
                    let deleteButton = this.addTableActions();

                    deleteButton.on('click', () => this.deleteRows());

                    this.selectAllRowsEventListener();
                }

                if (this.hasRoute('hide')) {
                    let hideButton = this.addTableHideActions();

                    hideButton.on('click', () => this.hideRows());

                    this.selectAllRowsEventListener();
                }

                if (this.hasRoute('show') || this.hasRoute('edit')) {
                    this.onRowClick(this.redirectToRowPage);
                }

                if (callback !== undefined) {
                    callback.call(this);
                }
            },
            rowCallback: (row, data) => {
                if (this.hasRoute('show') || this.hasRoute('edit')) {
                    this.makeRowClickable(row, data.id);
                }
            },
            drawCallback: () => {
                this.element.find('.select-all').prop('checked', false);

                setTimeout(() => {
                    this.selectRowEventListener();
                    this.checkSelectedCheckboxes(this.constructor.getSelectedIds(this.selector));
                });
            },
            stateSaveParams(settings, data) {
                delete data.start;
                delete data.search;
            },
        }, options));
    }

    addTableActions() {
        let button = `
            <button type="button" class="btn btn-default btn-delete">
                ${trans('admin::admin.buttons.delete')}
            </button>
        `;

        return $(button).appendTo(
            this.element.closest('.dataTables_wrapper').find('.dataTables_length')
        );
    }

    addTableHideActions() {
        let textValue = localStorage.getItem("pm_is_hidden") == '0' ? 
            trans('admin::admin.buttons.hide') : trans('admin::admin.buttons.show')
        let button = `
            <button type="button" class="btn btn-default btn-hide m-l-10">
                ${textValue}
            </button>
        `;

        return $(button).appendTo(
            this.element.closest('.dataTables_wrapper').find('.dataTables_length')
        );
    }

    hideRows() {
        let checked = this.element.find('.hide-row:checked');

        if (checked.length === 0) {
            return;
        }

        let hidden = [];

        let table = this.element.DataTable();

        table.processing(true);

        let ids = this.constructor.getRowIds(checked);

        // Don't make ajax request if an id was previously hidden.
        if (hidden.length !== 0 && _.difference(hidden, ids).length === 0) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: this.route('hide', { ids: ids.join() }),
            success: () => {
                hidden = _.flatten(hidden.concat(ids));

                this.constructor.setSelectedIds(this.selector, []);

                this.constructor.reload(this.element);
            },
            error: (xhr) => {
                error(xhr.responseJSON.message);

                hidden = _.flatten(hidden.concat(ids));

                this.constructor.setSelectedIds(this.selector, []);

                this.constructor.reload(this.element);
            },
        });
    }

    deleteRows() {
        let checked = this.element.find('.select-row:checked');

        if (checked.length === 0) {
            return;
        }

        let confirmationModal = $('#confirmation-modal');
        let deleted = [];

        confirmationModal.modal('show').find('form').on('submit', (e) => {
            e.preventDefault();

            confirmationModal.modal('hide');

            let table = this.element.DataTable();

            table.processing(true);

            let ids = this.constructor.getRowIds(checked);

            // Don't make ajax request if an id was previously deleted.
            if (deleted.length !== 0 && _.difference(deleted, ids).length === 0) {
                return;
            }

            $.ajax({
                type: 'DELETE',
                url: this.route('destroy', { ids: ids.join() }),
                success: () => {
                    deleted = _.flatten(deleted.concat(ids));

                    this.constructor.setSelectedIds(this.selector, []);

                    this.constructor.reload(this.element);
                },
                error: (xhr) => {
                    error(xhr.responseJSON.message);

                    deleted = _.flatten(deleted.concat(ids));

                    this.constructor.setSelectedIds(this.selector, []);

                    this.constructor.reload(this.element);
                },
            });
        });
    }

    makeRowClickable(row, id) {
        let key = this.hasRoute('show') ? 'show' : 'edit';
        let url = this.route(key, { id });

        $(row).addClass('clickable-row').data('href', url);

        setTimeout(() => {
            $('.clickable-row td:not(:first-child)').css('cursor', 'pointer');
        });
    }

    onRowClick(handler) {
        let row = 'tbody tr.clickable-row td';

        if (this.element.find('.select-all').length !== 0) {
            row += ':not(:first-child)';
        }

        this.element.on('click', row, handler);
    }

    redirectToRowPage(e) {
        window.open(
            $(e.currentTarget).parent().data('href'),
            e.ctrlKey ? '_blank' : '_self'
        );
    }

    selectAllRowsEventListener() {
        this.element.find('.select-all').on('change', (e) => {
            this.element.find('.select-row').prop('checked', e.currentTarget.checked);
        });
    }

    selectRowEventListener() {
        this.element.find('.select-row').on('change', (e) => {
            if (e.currentTarget.checked) {
                this.appendToSelected(e.currentTarget.value);
            } else {
                this.removeFromSelected(e.currentTarget.value);
            }
        });
    }

    appendToSelected(id) {
        id = parseInt(id);

        if (! SMIS.dataTable.selected[this.selector].includes(id)) {
            SMIS.dataTable.selected[this.selector].push(id);
        }
    }

    removeFromSelected(id) {
        id = parseInt(id);

        SMIS.dataTable.selected[this.selector].remove(id);
    }

    checkSelectedCheckboxes(selectedIds) {
        let rows = this.element.find('.select-row');

        let checkableRows = rows.toArray().filter((row) => {
            return selectedIds.includes(parseInt(row.value));
        });

        $(checkableRows).prop('checked', true);
    }

    route(name, params) {
        let router = SMIS.dataTable.routes[this.selector][name];

        if (typeof router === 'string') {
            router = { name: router, params };
        }

        router.params = _.merge(params, router.params);

        return window.route(router.name, router.params).url();
    }

    hasRoute(name) {
        return SMIS.dataTable.routes[this.selector][name] !== undefined;
    }

    static setRoutes(selector, routes) {
        SMIS.dataTable.routes[selector] = routes;
    }

    static setSelectedIds(selector, selected) {
        SMIS.dataTable.selected[selector] = selected;
    }

    static getSelectedIds(selector) {
        return SMIS.dataTable.selected[selector];
    }

    static reload(selector, callback, resetPaging = false) {
        $(selector).DataTable().ajax.reload(callback, resetPaging);
    }

    static getRowIds(rows) {
        return rows.toArray().reduce((ids, row) => {
            return ids.concat(row.value);
        }, []);
    }

    static removeLengthFields() {
        $('.dataTables_length select').remove();
    }

    addErrorHandler() {
        $.fn.dataTable.ext.errMode = (settings, helpPage, message) => {
            this.element.html(message);
        };
    }

    // https://datatables.net/plug-ins/api/processing()
    registerTableProcessingPlugin() {
        $.fn.dataTable.Api.register('processing()', function (show) {
            return this.iterator('table', function (ctx) {
                ctx.oApi._fnProcessingDisplay(ctx, show);
            });
        });
    }
}
