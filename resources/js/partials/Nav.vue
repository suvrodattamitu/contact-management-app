<template>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">

            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav custom-right pull-right">
                    <li class="nav-item" v-if="isAuthenticated">
                        <router-link class="nav-link active" to="/">Home</router-link>
                    </li>
                    <li class="nav-item" v-if="!isAuthenticated">
                        <router-link class="nav-link active" aria-current="page" to="/login">Login</router-link>
                    </li>
                    <li class="nav-item" v-if="!isAuthenticated">
                        <router-link class="nav-link" to="/register" exact>Signup</router-link>
                    </li>
                    <li class="nav-item" v-if="isAuthenticated">
                        <a href="#" class="nav-link" @click.prevent="logoutUser">Logout</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
</template>

<script>
export default {

    name: 'Navigation',

    data() {

        return {
            isAuthUser: window.localStorage.getItem('user'), 
        }

    },

    methods: {

        logoutUser() {

            localStorage.removeItem('user')
            this.$store.dispatch("authentication_action",false)
            this.$router.push("/login")

        }

    },

    computed: {
        isAuthenticated() {
            return this.$store.getters.isAuthenticated
        }
    }

}
</script>