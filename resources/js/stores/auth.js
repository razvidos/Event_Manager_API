import {defineStore} from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        isLoggedIn: false,
    }),
    actions: {
        async login(email, password) {
            try {
                await axios.post('/login', {
                    email,
                    password,
                })
                this.isLoggedIn = true
            } catch (error) {
                console.error(error)
                throw new Error('Invalid login credentials')
            }
        },
        async register(name, email, password, password_confirmation) {
            try {
                const response = await axios.post('/register', {
                    name,
                    email,
                    password,
                    password_confirmation,
                })
                this.isLoggedIn = true
                this.user = response.data.user
                this.token = response.data.token
            } catch (error) {
                console.error(error)
                throw new Error('Invalid registration data')
            }
        },
        async logout() {
            await axios.post('/logout')
            this.isLoggedIn = false;
        },
    },
})
