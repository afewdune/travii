/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
import { createApp } from 'vue';
import router from './router';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import 'bootstrap/dist/css/bootstrap.min.css'; // นำเข้า Bootstrap CSS

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import HomeComponent from './components/Home.vue';
app.component('home-component', HomeComponent);

import AdminPanel from './components/AdminPanel.vue';
app.component('admin-panel', AdminPanel);

import Login from './components/Login.vue';
app.component('login-component', Login);

import Register from './components/Register.vue';
app.component('register-component', Register);

import AddFish from './components/AddFish.vue';
app.component('add-fish', AddFish);

import EditFish from './components/EditFish.vue';
app.component('edit-fish', EditFish);

import FishingSkillCheckComponent from './components/FishingSkillCheckComponent.vue';
app.component('fishing-skill-check-component', FishingSkillCheckComponent);

import FishingPanicComponent from './components/FishingPanicComponent.vue';
app.component('fishing-panic-component', FishingPanicComponent);

import InventoryPage from './components/InventoryPage.vue';
app.component('inventory-page', InventoryPage);

import InventoryComponent from './components/InventoryComponent.vue';
app.component('inventory-component', InventoryComponent);

import LeaderBoardComponent from './components/LeaderBoardComponent.vue';
app.component('leaderboard-component', LeaderBoardComponent);

import ShopComponent from './components/ShopComponent.vue';
app.component('shop-component', ShopComponent);

app.use(router);
app.use(Toast);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
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
