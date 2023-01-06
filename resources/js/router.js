import {createRouter, createWebHashHistory} from "vue-router";
import Login from "./pages/auth/login.vue"
import Register from "./pages/auth/register.vue"

export default createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
    ]
});
