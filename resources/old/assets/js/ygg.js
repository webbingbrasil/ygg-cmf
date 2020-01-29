import './polyfill';
import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import {install as VueGoogleMaps} from 'vue2-google-maps';

import YggActionView from './components/ActionView';
import YggForm from './components/form/Form';
import YggFieldDisplay from './components/form/field-display/FieldDisplay';

import YggCollapsibleItem from './components/menu/CollapsibleItem';
import YggNavItem from './components/menu/NavItem';
import YggLeftNav from './components/menu/LeftNav';

import YggItemVisual from './components/ui/ItemVisual';
import Loading from './components/ui/Loading';

import routes from './routes';

import axios from 'axios';
import cookies from 'axios/lib/helpers/cookies';
import qs from 'qs';

import Notifications from 'vue-notification';

import store from './store';
import {BASE_URL} from "./consts";

import locale from 'element-ui/lib/locale';
import {elLang} from './util/element-ui';

locale.use(elLang());

Vue.use(Notifications);
Vue.use(VueGoogleMaps, {
    installComponents: false
});

Vue.config.ignoredElements = [/^trix-/];

// prevent recursive components import
Vue.component(YggFieldDisplay.name, YggFieldDisplay);
const YggLoading = Vue.extend(Loading);

new Vue({
    el: "#ygg-app",

    provide: {
        mainLoading: new YggLoading({el: '#glasspane'}),
        xsrfToken: cookies.read(axios.defaults.xsrfCookieName)
    },

    components: {
        YggActionView,
        YggForm,
        YggCollapsibleItem,
        YggNavItem,
        YggLeftNav,
        YggItemVisual
    },

    created() {
        this.$on('setClass', (className, active) => {
            this.$el.classList[active ? 'add' : 'remove'](className);
        });
    },

    store: new Vuex.Store(store),
    router: new VueRouter({
        mode: 'history',
        routes,
        base: `${BASE_URL}/`,
        parseQuery: query => qs.parse(query, {strictNullHandling: true}),
        stringifyQuery: query => qs.stringify(query, {addQueryPrefix: true, skipNulls: true}),
    })
});
