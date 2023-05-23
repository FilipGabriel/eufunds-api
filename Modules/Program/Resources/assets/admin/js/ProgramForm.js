import ProgramTree from './ProgramTree';

export default class {
    constructor() {
        let tree = $('.program-tree');

        new ProgramTree(this, tree);

        this.collapseAll(tree);
        this.expandAll(tree);
        this.addRootProgram();
        this.addSubProgram();

        $('#program-form').on('submit', this.submit);

        window.admin.removeSubmitButtonOffsetOn('#image', '.program-details-tab li > a');
    }

    collapseAll(tree) {
        $('.collapse-all').on('click', (e) => {
            e.preventDefault();

            tree.jstree('close_all');
        });
    }

    expandAll(tree) {
        $('.expand-all').on('click', (e) => {
            e.preventDefault();

            tree.jstree('open_all');
        });
    }

    addRootProgram() {
        $('.add-root-program').on('click', () => {
            this.loading(true);

            $('.add-sub-program').addClass('disabled');

            $('.program-tree').jstree('deselect_all');

            this.clear();

            // Intentionally delay 150ms so that user feel new form is loaded.
            setTimeout(this.loading, 150, false);
        });
    }

    addSubProgram() {
        $('.add-sub-program').on('click', () => {
            let selectedId = $('.program-tree').jstree('get_selected')[0];

            if (selectedId === undefined) {
                return;
            }

            this.clear();
            this.loading(true);

            window.form.appendHiddenInput('#program-form', 'parent_id', selectedId);

            // Intentionally delay 150ms so that user feel new form is loaded.
            setTimeout(this.loading, 150, false);
        });
    }

    fetchProgram(id) {
        this.loading(true);

        $('.add-sub-program').removeClass('disabled');

        $.ajax({
            type: 'GET',
            url: route('admin.programs.show', id),
            success: (program) => {
                this.update(program);

                this.loading(false);
            },
            error: (xhr) => {
                error(xhr.responseJSON.message);

                this.loading(false);
            },
        });
    }

    update(program) {
        window.form.removeErrors();

        $('.btn-delete').removeClass('hide');
        $('.form-group .help-block').remove();

        $('#confirmation-form').attr('action', route('admin.programs.destroy', program.id));

        $('#id-field').removeClass('hide');
        
        $('#id').val(program.id);
        $('#name').val(program.name);
        this.addProgramTypes(program.types);
        this.addProgramCategories(program.categories);

        $('#slug').val(program.slug);
        $('#slug-field').removeClass('hide');
        $('.program-details-tab .seo-tab').removeClass('hide');

        $('#is_searchable').prop('checked', program.is_searchable);
        $('#is_active').prop('checked', program.is_active);

        $('.banner .image-holder-wrapper').html(this.programImage('banner', program.banner));

        $('#program-form input[name="parent_id"]').remove();
    }

    programImage(fieldName, file) {
        if (! file.exists) {
            return this.imagePlaceholder();
        }

        return `
            <div class="image-holder">
                <img src="${file.path}">
                <button type="button" class="btn remove-image" data-input-name="files[${fieldName}]"></button>
                <input type="hidden" name="files[${fieldName}]" value="${file.id}">
            </div>
        `;
    }

    addProgramCategories(categories) {
        let categoryValues = $('#categories\\[\\]')[0].selectize;
        categoryValues.clear();
        
        categories.forEach(category => {
            categoryValues.addItem(category.id);
        });
    }

    addProgramTypes(types) {
        let typeValues = $('#types\\[\\]')[0].selectize;
        typeValues.clear();
        
        types.forEach(type => {
            typeValues.addItem(type);
        });
    }

    clear() {
        $('#id-field').addClass('hide');

        $('#id').val('');
        $('#name').val('');
        let categoryValues = $('#categories\\[\\]')[0].selectize;
        categoryValues.clear();

        $('#slug').val('');
        $('#slug-field').addClass('hide');
        $('.program-details-tab .seo-tab').addClass('hide');

        $('#is_searchable').prop('checked', false);
        $('#is_active').prop('checked', false);

        $('.banner .image-holder-wrapper').html(this.imagePlaceholder());

        $('.btn-delete').addClass('hide');
        $('.form-group .help-block').remove();

        $('#program-form input[name="parent_id"]').remove();

        $('.general-information-tab a').click();
    }

    imagePlaceholder() {
        return `
            <div class="image-holder placeholder">
                <i class="fa fa-picture-o"></i>
            </div>
        `;
    }

    loading(state) {
        if (state === true) {
            $('.overlay.loader').removeClass('hide');
        } else {
            $('.overlay.loader').addClass('hide');
        }
    }

    submit(e) {
        let selectedId = $('.program-tree').jstree('get_selected')[0];

        if (! $('#slug-field').hasClass('hide')) {
            window.form.appendHiddenInput('#program-form', '_method', 'put');

            $('#program-form').attr('action', route('admin.programs.update', selectedId));
        }

        e.currentTarget.submit();
    }
}
