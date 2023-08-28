import ProgramTree from './ProgramTree';
import Download from './Download';
import Offer from './Offer';

export default class {
    constructor() {
        let tree = $('.program-tree');
        this.downloadsCount = 0;
        this.offersCount = 0;
        this.downloads = [];
        this.offers = [];

        new ProgramTree(this, tree);

        this.collapseAll(tree);
        this.expandAll(tree);
        this.addRootProgram();
        this.addSubProgram();

        this.addDownloads(this.downloads);
        this.addOffers(this.offers);

        this.attachEventListeners();
        this.makeDownloadsSortable();
        this.makeOffersSortable();

        $('#program-form').on('submit', this.submit);

        window.admin.removeSubmitButtonOffsetOn('#image', '.program-details-tab li > a', '#downloads', '#offers');
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

        $('#downloads-wrapper tr').remove();

        this.downloadsCount = 0;
        this.downloads = program.files.filter((file) => {
            return file.location == 'downloads';
        });

        this.addDownloads(this.downloads);

        $('#offers-wrapper tr').remove();

        this.offersCount = 0;
        this.offers = program.files.filter((file) => {
            return file.location == 'download_offers';
        });

        this.addOffers(this.offers);
        
        $('#id').val(program.id);
        $('#name').val(program.name);
        $('#title').val(program.title);
        this.addProgramTypes(program.types);
        this.addProgramCategories(program.categories);
        this.addProgramListCategories(program.list_categories);

        $('#slug').val(program.slug);
        $('#slug-field').removeClass('hide');
        $('.program-details-tab .seo-tab').removeClass('hide');

        $('#is_searchable').prop('checked', program.is_searchable);
        $('#is_active').prop('checked', program.is_active);

        $('.banner .image-holder-wrapper').html(this.programImage('banner', program.banner));
        $('.small-banner .image-holder-wrapper').html(this.programImage('small_banner', program.small_banner));

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

    addProgramListCategories(categories) {
        let categoryValues = $('#list_categories\\[\\]')[0].selectize;
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
        $('#title').val('');
        let categoryValues = $('#categories\\[\\]')[0].selectize;
        categoryValues.clear();
        let listCategoryValues = $('#list_categories\\[\\]')[0].selectize;
        listCategoryValues.clear();
        
        $('#downloads-wrapper tr').remove();
        
        this.downloads = [];
        this.downloadsCount = 0;

        this.addDownloads(this.downloads);
        
        $('#offers-wrapper tr').remove();
        
        this.offers = [];
        this.offersCount = 0;

        this.addOffers(this.offers);

        $('#slug').val('');
        $('#slug-field').addClass('hide');
        $('.program-details-tab .seo-tab').addClass('hide');

        $('#is_searchable').prop('checked', false);
        $('#is_active').prop('checked', false);

        $('.banner .image-holder-wrapper').html(this.imagePlaceholder());
        $('.small-banner .image-holder-wrapper').html(this.imagePlaceholder());

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

    addDownloads(downloads) {
        for (let attributes of downloads) {
            this.addDownload(attributes);
        }

        if (this.downloadsCount === 0) {
            this.addDownload();
        }
    }

    addOffers(offers) {
        for (let attributes of offers) {
            this.addOffer(attributes);
        }

        if (this.offersCount === 0) {
            this.addOffer();
        }
    }

    addDownload(attributes = {}) {
        let download = new Download({ download: attributes });

        $('#downloads-wrapper').append(download.render());

        this.downloadsCount++;
        window.admin.tooltip();
    }

    addOffer(attributes = {}) {
        let offer = new Offer({ offer: attributes });

        $('#offers-wrapper').append(offer.render());

        this.offersCount++;
        window.admin.tooltip();
    }

    attachEventListeners() {
        $('#add-new-file').on('click', () => {
            this.addDownload();
        });

        $('#add-new-offer').on('click', () => {
            this.addOffer();
        });
    }

    makeDownloadsSortable() {
        Sortable.create(document.getElementById('downloads-wrapper'), {
            handle: '.drag-icon',
            animation: 150,
        });
    }

    makeOffersSortable() {
        Sortable.create(document.getElementById('offers-wrapper'), {
            handle: '.drag-icon',
            animation: 150,
        });
    }
}
