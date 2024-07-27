import { createRouter, createWebHistory } from 'vue-router';
import Login from './views/Login.vue';
import Home from './views/Home.vue';
import Register from './views/Register.vue';
import Admin from './views/Admin.vue';
import Artist from './views/Artist.vue';

const routes = [
  {
    path: '/',
    name: 'login',
    component: Login
  },
  {
    path: '/home',
    name: 'home',
    component: Home
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
  {
    path: '/admin',
    name: 'admin',
    component: Admin
  },
  {
    path: '/artist',
    name: 'artist',
    component: Artist
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
