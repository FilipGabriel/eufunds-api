export default class {
    constructor(offer) {
        this.offerHtml = this.getOfferHtml(offer);
    }

    getOfferHtml(offer) {
        let template = _.template($('#program-offer-template').html());

        return $(template(offer));
    }

    render() {
        this.attachOfferEventListeners();

        return this.offerHtml;
    }

    attachOfferEventListeners() {
        this.offerHtml.find('.delete-row').on('click', () => {
            this.offerHtml.remove();
        });

        this.offerHtml.find('.btn-choose-file').on('click', (e) => {
            let location = e.currentTarget.dataset.location;

            let picker = new MediaPicker({ type: null, multiple: false, location });

            picker.on('select', (file) => {
                this.offerHtml.find('.offer-name').val(file.filename);
                this.offerHtml.find('.offer-file').val(file.id);
            });
        });
    }
}
