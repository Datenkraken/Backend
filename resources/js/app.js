/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap.js');
import Vue from 'vue';
window.Vue = Vue;

import {
    Users,
    Sources,
    SourceCategoryContainer,
    Categories,
    Modal,
    Button,
    DocumentEdit,
    ChartContainer,
    AccountDropdown,
    LanguageDropdown,
    DatePicker,
    RetentionSettings,
    ConfirmModal,
    UserSelection,
    UserMap,
    BluetoothNetwork,
    FontAwesomeIconWrapper,
    ButtonLink
} from './components';
import { AuthorizedClients, Clients, PersonalAccessTokens } from './components/passport';
import { LineChart, UserCountChart, AppOpenedChart } from './components/charts';

import { library as falibrary} from '@fortawesome/fontawesome-svg-core';
import { faBluetoothB } from '@fortawesome/free-brands-svg-icons';
import { faCogs, faSignOutAlt, faKey, faEdit, faTrash, faCalendar, faTimes, faCircle, faCircleNotch, faUserShield, faHome, faUsers, faFolderOpen, faFileAlt, faIdCard, faSignInAlt, faHourglassEnd, faMapMarkerAlt, faQuestion, faInfoCircle} from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import CKEditor from '@ckeditor/ckeditor5-vue';
import Notifications from 'vue-notification';
import VueFlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import "vue-vis-network/dist/vueVisNetwork.css";
import Lang from 'lang.js';
import VueFlags from "@growthbunker/vueflags";

import ApolloClient from 'apollo-boost';
import VueApollo from 'vue-apollo';

import 'leaflet/dist/leaflet.css';
import 'vue-slider-component/theme/antd.css';
import '@fortawesome/fontawesome-svg-core/styles.css'

falibrary.add(faCogs, faSignOutAlt, faKey, faEdit, faTrash, faCalendar, faTimes, faCircle, faCircleNotch, faUserShield, faHome, faUsers, faFolderOpen, faFileAlt, faIdCard, faSignInAlt, faHourglassEnd, faMapMarkerAlt, faQuestion, faInfoCircle);
falibrary.add(faBluetoothB);

const apolloProvider = new VueApollo({
  defaultClient: new ApolloClient({
	uri: 'graphql',
	headers: {
		'X-Requested-With': 'XMLHttpRequest',
		'X-CSRF-TOKEN': window.csrf
	},
  }),
})

Vue.use(Notifications);
Vue.use(CKEditor);
Vue.use(VueFlatPickr);
Vue.use(VueFlags, { iconPath: "/flags/" });
Vue.use(VueApollo);

// localization
const default_locale = window.default_language;
const fallback_locale = window.fallback_locale;
const messages = window.messages;
window.lang = new Lang( { messages, locale: default_locale, fallback: fallback_locale } );

Vue.prototype.$lang = new Lang( { messages, locale: default_locale, fallback: fallback_locale } );

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('font-awesome-icon-wrapper', FontAwesomeIconWrapper);

Vue.component('dk-users', Users);
Vue.component('dk-sources', Sources);
Vue.component('dk-categories', Categories);
Vue.component('dk-document-edit', DocumentEdit);
Vue.component('dk-retention-settings', RetentionSettings);

Vue.component('dk-source-category-container', SourceCategoryContainer);
Vue.component('dk-modal', Modal);
Vue.component('dk-button', Button);
Vue.component('dk-confirm-modal', ConfirmModal);
Vue.component('dk-datepicker', DatePicker);
Vue.component('dk-account-dropdown', AccountDropdown);
Vue.component('dk-language-dropdown', LanguageDropdown);

Vue.component('dk-oauth-clients', Clients);
Vue.component('dk-oauth-authorized-clients', AuthorizedClients);
Vue.component('dk-oauth-personal-access-tokens', PersonalAccessTokens);

Vue.component('dk-chart-container', ChartContainer);
Vue.component('dk-line-chart', LineChart);
Vue.component('dk-user-count-chart', UserCountChart);
Vue.component('dk-app-opened-chart', AppOpenedChart);

Vue.component('user-selection', UserSelection);
Vue.component('dk-user-map', UserMap);
Vue.component('dk-bluetooth-network', BluetoothNetwork);
Vue.component('button-link', ButtonLink);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
	apolloProvider,
});
