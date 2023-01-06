<template>
    <v-container>
        <v-form ref="form" v-model="valid">
            <v-text-field v-model="name" label="Name" required></v-text-field>
            <v-text-field v-model="email" label="Email" required></v-text-field>
            <v-text-field v-model="password" label="Password" required></v-text-field>
            <v-text-field v-model="password_confirmation" label="Confirm Password" required></v-text-field>
            <v-btn :disabled="!valid" color="primary" @click="register">Register</v-btn>
        </v-form>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            valid: false,
        };
    },
    methods: {
        async register() {
            this.$authStore.register(this.name, this.email, this.password, this.password_confirmation)
        },
    },
};
</script>
