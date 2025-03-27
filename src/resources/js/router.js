import { createRouter, createWebHistory } from 'vue-router';

import HomeComponent from './components/Home.vue';
import AdminPanel from './components/AdminPanel.vue';
import AddFish from './components/AddFish.vue';
import EditFish from './components/EditFish.vue';
import Login from './components/Login.vue';
import Register from './components/Register.vue';

const routes = [
    { path: '/', component: HomeComponent, name: 'home' },
    { path: '/admin', component: AdminPanel, name: 'admin.dashboard' },
    { path: '/admin/add-fish', component: AddFish, name: 'admin.add-fish' },
    { path: '/admin/edit-fish/:id', component: EditFish, name: 'admin.edit-fish', props: true },
    { path: '/login', component: Login, name: 'login' },
    { path: '/register', component: Register, name: 'register' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;