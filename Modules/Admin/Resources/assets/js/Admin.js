import NProgress from 'nprogress';

export default class {
    constructor() {
        this.selectize();
        this.dateTimePicker();
        this.changeAccordionTabState();
        this.preventChangingCurrentTab();
        this.buttonLoading();
        this.confirmationModal();
        this.tooltip();
        this.shortcuts();
        this.nprogress();
        this.notifications();
    }

    selectize() {
        let selects = $('select.selectize').removeClass('form-control custom-select-black');

        for (let select of selects) {
            select = $(select);

            let labelField = 'name';
            if (select.data('label')) {
                labelField = select.data('label');
            }

            let searchField = 'name';
            if (select.data('search')) {
                searchField = select.data('search');
            }

            let options = _.merge({
                valueField: 'id',
                labelField: labelField,
                searchField: searchField,
                delimiter: ',',
                persist: true,
                selectOnTab: true,
                hideSelected: false,
                allowEmptyOption: true,
                onItemAdd(value) {
                    this.getItem(value)[0].innerHTML = this.getItem(value)[0].innerHTML.replace(/¦––\s/g, '');
                },
                onInitialize() {
                    for (let index in this.options) {
                        let label = this.options[index][labelField];
                        let value = this.options[index].id;

                        this.$control.find(`.item[data-value="${value}"]`).html(
                            label.replace(/¦––\s/g, '') +
                            '<a href="javascript:void(0)" class="remove" tabindex="-1">×</a>'
                        );
                    }
                },
            }, ...SMIS.selectize);

            let create = true;
            let plugins = ['remove_button', 'restore_on_backspace'];

            if (select.hasClass('prevent-creation')) {
                create = false;

                plugins.remove('restore_on_backspace');
            }

            select.selectize(_.merge(options, { create, plugins }));
        }
    }

    dateTimePicker(elements) {
        elements = elements || $('.datetime-picker');

        elements = elements instanceof jQuery ? elements : $(elements);

        for (let el of elements) {
            $(el).flatpickr({
                mode: el.hasAttribute('data-range') ? 'range' : 'single',
                enableTime: el.hasAttribute('data-time'),
                noCalender: el.hasAttribute('data-no-calender'),
                minDate: el.hasAttribute('min-date') ? el.data('min-date') : null,
                altInput: true,
            });
        }
    }

    changeAccordionTabState() {
        $('.accordion-box [data-toggle="tab"]').on('click', (e) => {
            if (! $(e.currentTarget).parent().hasClass('active')) {
                $('.accordion-tab li.active').removeClass('active');
            }
        });
    }

    preventChangingCurrentTab() {
        $('[data-toggle="tab"]').on('click', (e) => {
            let targetElement = $(e.currentTarget);

            if (targetElement.parent().hasClass('active')) {
                return false;
            }
        });
    }

    removeSubmitButtonOffsetOn(tabs, tabsSelector = null) {
        tabs = Array.isArray(tabs) ? tabs : [tabs];

        $(tabsSelector || '.accordion-tab li > a').on('click', (e) => {
            if (tabs.includes(e.currentTarget.getAttribute('href'))) {
                setTimeout(() => {
                    $('button[type=submit]').parent().removeClass('col-md-offset-2');
                }, 150);
            } else {
                setTimeout(() => {
                    $('button[type=submit]').parent().addClass('col-md-offset-2');
                }, 150);
            }
        });
    }

    buttonLoading() {
        $(document).on('click', '[data-loading]', (e) => {
            let button = $(e.currentTarget);

            button.data('loading-text', button.html())
                .addClass('btn-loading')
                .button('loading');
        });
    }

    stopButtonLoading(button) {
        button = button instanceof jQuery ? button : $(button);

        button.data('loading-text', button.html())
            .removeClass('btn-loading')
            .button('reset');
    }

    confirmationModal() {
        let confirmationModal = $('#confirmation-modal');

        $('[data-confirm]').on('click', () => {
            confirmationModal.modal('show');
        });

        confirmationModal.find('form').on('submit', () => {
            confirmationModal.find('button.delete').prop('disabled', true);
        });

        confirmationModal.on('hidden.bs.modal', () => {
            confirmationModal.find('button.delete').prop('disabled', false);
        });

        confirmationModal.on('shown.bs.modal', () => {
            confirmationModal.find('button.delete').focus();
        });
    }

    tooltip() {
        $('[data-toggle="tooltip"]').tooltip({ trigger : 'hover' })
            .on('click', (e) => {
                $(e.currentTarget).tooltip('hide');
            });
    }

    shortcuts() {
        Mousetrap.bind('?', () => {
            $('#keyboard-shortcuts-modal').modal();
        });
    }

    nprogress() {
        let inMobile = /iphone|ipod|android|ie|blackberry|fennec/i.test(window.navigator.userAgent);

        if (inMobile) {
            return;
        }

        NProgress.configure({ showSpinner: false });

        $(document).ajaxStart(() => NProgress.start());
        $(document).ajaxComplete(() => NProgress.done());
    }

    notifications() {
        $('.notifications-list .item').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var self = this;
            $.ajax({
                type: 'POST',
                url: route('api.notifications.read'),
                data: {
                    'id': $(this).data('id'),
                },
                success: function(data) {
                    if (data.updated) {
                        var notification = $(self).closest('li');
                        notification.addClass('animated fadeOut');
                        setTimeout(function() {
                            notification.remove();
                        }, 510)
                        var count = parseInt($('.notifications-counter').text());
                        $('.notifications-counter').text(count - 1);
                    }
                }
            });
        });

        $('.view-all').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $.ajax({
                type: 'POST',
                url: route('api.notifications.all_read'),
                success: function(data) {
                    if (data.updated) {
                        var notifications = $('.notifications-list li');
                        setTimeout(function() {
                            notifications.remove();
                        }, 510)
                        $('.notifications-counter').text(0);
                    }
                }
            });
        });
    }
}
