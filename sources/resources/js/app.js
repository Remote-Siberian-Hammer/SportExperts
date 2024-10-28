/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import {createApp} from 'vue';
import VueCookies from 'vue-cookies'
import PrimeVue from "primevue/config";
import 'primevue/resources/themes/aura-light-blue/theme.css';
import "primeicons/primeicons.css";
import '@vueup/vue-quill/dist/vue-quill.snow.css';

/**
 * Next, we will create a fresh Vue application instance. You may thlayouten begin
 * registering rules with the application instance so they are ready
 * to use in your application's pages. An example is included for you.
 */

const app = createApp({});
app.use(PrimeVue, {unstyled: false});
app.use(VueCookies, {
    expires: '7d',
    path: '/',
    domain: '',
    secure: '',
    sameSite: 'Lax',
    partitioned: false
});

// Use Layouts
import HeaderComponent from './components/layouts/AppHeaderComponent.vue';

app.component('app-header-component', HeaderComponent);

import AppFooterComponent from './components/layouts/AppFooterComponent.vue';

app.component('app-footer-component', AppFooterComponent);


// Use Pages
import RegistrationView from './pages/Users/RegistrationPage.vue';

app.component('registration-view', RegistrationView);

import LoginView from './pages/Users/LoginPage.vue';

app.component('login-view', LoginView);

import RecoveryView from './pages/Users/RecoveryPage.vue';

app.component('recovery-view', RecoveryView);

import ProfileView from './pages/Users/ProfilePage.vue';

app.component('profile-view', ProfileView);

import Settings from './pages/Users/SettingsPage.vue';

app.component('settings-view', Settings);

import EventListView from './pages/Events/ListPage.vue';

app.component('event-view', EventListView);

import CreateOfUpdateView from './pages/Events/CreateOfUpdatePage.vue';

app.component('event-create-of-update-view', CreateOfUpdateView);

import EventDetailsView from './pages/Events/DetailsPage.vue';

app.component('event-detail-view', EventDetailsView);

import ParticipantSearchView from './pages/Participants/ParticipantSearchPage.vue';

app.component('participant-search-view', ParticipantSearchView);

import InviteView from './pages/Invites/IndexPage.vue';

app.component('invite-view', InviteView);

import TournamentView from './pages/Tournaments/IndexPage.vue';

app.component('tournament-view', TournamentView);

import TournamentHistoryView from './pages/Tournaments/HistoryPage.vue';

app.component('tournament-history-view', TournamentHistoryView);

/**
 * The following block of code may be used to automatically register your
 * Vue rules. It will recursively scan this directory for the Vue
 * rules and automatically register them with their "basename".
 *
 * Eg. ./rules/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */
app.mount('#app');
