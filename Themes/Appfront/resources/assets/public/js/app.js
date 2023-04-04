require('./appfront');

import Vue from 'vue';
import { notify, trans, chunk } from './functions';
import VueToast from 'vue-toast-notification';
import vClickOutside from 'v-click-outside';
import VPagination from './components/VPagination.vue';
import HeaderSearch from './components/layout/HeaderSearch.vue';
import NewsletterPopup from './components/layout/NewsletterPopup';
import NewsletterSubscription from './components/layout/NewsletterSubscription';
import CookieBar from './components/layout/CookieBar';
import DynamicTab from './components/home/DynamicTab';
import HomeFeatures from './components/home/HomeFeatures.vue';
import BannerThreeColumnFullWidth from './components/home/BannerThreeColumnFullWidth.vue';
import BannerTwoColumn from './components/home/BannerTwoColumn.vue';
import BannerThreeColumn from './components/home/BannerThreeColumn.vue';
import BannerOneColumn from './components/home/BannerOneColumn.vue';

Vue.prototype.route = route;
Vue.prototype.$notify = notify;
Vue.prototype.$trans = trans;
Vue.prototype.$chunk = chunk;

Vue.use(VueToast);
Vue.use(vClickOutside);

Vue.component('v-pagination', VPagination);
Vue.component('header-search', HeaderSearch);
Vue.component('newsletter-popup', NewsletterPopup);
Vue.component('newsletter-subscription', NewsletterSubscription);
Vue.component('cookie-bar', CookieBar);
Vue.component('dynamic-tab', DynamicTab);
Vue.component('home-features', HomeFeatures);
Vue.component('banner-three-column-full-width', BannerThreeColumnFullWidth);
Vue.component('banner-two-column', BannerTwoColumn);
Vue.component('banner-three-column', BannerThreeColumn);
Vue.component('banner-one-column', BannerOneColumn);

new Vue({
    el: '#app',
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': SMIS.csrfToken,
    },
});
