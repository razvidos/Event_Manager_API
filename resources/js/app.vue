<template>
    <v-app>
        <v-app-bar app color="primary" dark>
            <v-toolbar-title>{{ appName }}</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn v-if="!this.$authStore.isLoggedIn" to="/login">Login</v-btn>
            <v-btn v-if="!this.$authStore.isLoggedIn" to="/register">Register</v-btn>
            <v-btn v-if="this.$authStore.isLoggedIn" @click="logout">Logout</v-btn>
        </v-app-bar>

        <v-main>
            <navBar></navBar>
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import navBar from './components/global/navBar.vue'

export default {
    components: {navBar,},
    data() {
        return {
            appName: 'SPA',
        };
    },
    mounted() {
        this.$authStore.isLoggedIn = window.auth_user !== null
    },
    methods: {
        async logout() {
            await this.$authStore.logout();
            this.$router.push('/');

        }
    },
};
</script>
