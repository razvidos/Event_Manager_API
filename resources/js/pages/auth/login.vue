<template>
    <v-container>
        <v-card class="mx-auto mt-12" max-width="500">
            <v-card-title class="text-center">Login</v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid">
                    <v-text-field
                        v-model="email"
                        :rules="emailRules"
                        label="Email"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="password"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="showPassword ? 'text' : 'password'"
                        label="Password"
                        required
                        @click:append="showPassword = !showPassword"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :disabled="!valid" color="primary" @click="login">Login</v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            valid: false,
            email: '',
            password: '',
            showPassword: false,
            emailRules: [
                v => !!v || 'Email is required',
                v => /.+@.+\..+/.test(v) || 'Email must be valid',
            ],
        }
    },
    methods: {
        async login() {
            await this.$authStore.login(this.email, this.password);
            this.$router.push('/');
        }
    }
}
</script>
