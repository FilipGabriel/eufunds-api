export default class {
    constructor(download) {
        this.downloadHtml = this.getDownloadHtml(download);
    }

    getDownloadHtml(download) {
        let template = _.template($('#program-download-template').html());

        return $(template(download));
    }

    render() {
        this.attachEventListeners();

        return this.downloadHtml;
    }

    attachEventListeners() {
        this.downloadHtml.find('.delete-row').on('click', () => {
            this.downloadHtml.remove();
        });

        this.downloadHtml.find('.btn-choose-file').on('click', (e) => {
            let location = e.currentTarget.dataset.location;

            let picker = new MediaPicker({ type: null, multiple: false, location });

            picker.on('select', (file) => {
                this.downloadHtml.find('.download-name').val(file.filename);
                this.downloadHtml.find('.download-file').val(file.id);
            });
        });
    }
}
