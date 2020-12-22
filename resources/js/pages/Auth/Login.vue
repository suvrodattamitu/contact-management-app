<template>
    <div class="content-body">
        <h4>Login</h4>
        <form>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" v-model="email" class="form-control" placeholder="Enter email">
                <p v-if="Object.keys(errors).length && errors.email" style="color:red">{{ errors.email[0] }}</p>
                <p v-if="error_message" style="color:red">{{ error_message }}</p>
            </div><br>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" v-model="password" class="form-control" placeholder="Password">
                <p v-if="errors.password" style="color:red">{{ errors.password[0] }}</p>
            </div><br>
            <button type="submit" class="btn btn-info" @click.prevent="signInUser">Login</button>
        </form>

        <div class="credentials">
            <h3>Demo Credentials</h3><br>
            <p>Email : admin@admin.com </p>
            <p>Password : 12345678 </p>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'Login',

        data() {
            return {
                email: '',
                password: '',
                errors: {},
                error_message: ''
            }
        },

        methods: {

            signInUser() {

                this.error_message = ''
                this.errors = {}
                
                const url = "/login"

                const data = {
                    email: this.email,
                    password: this.password
                }

                const headers = {
                    "Content-Type": "application/json"
                }

                axios.post(url, data, headers)
                    .then(response => {

                        if(response.data.token !== null) {

                            //signup done
                            localStorage.setItem( "user", response.data.token )
                            this.$store.dispatch("authentication_action",true)
                            this.$router.push("/")

                        }

                    })
                    .catch(error => {

                        if( error.response && error.response.data ) {
                            if( error.response.data.errors && Object.keys(error.response.data.errors).length ) {
                                this.errors = error.response.data.errors
                            }else if( error.response.data.message ) {
                                this.error_message = error.response.data.message
                            }
                        }

                    })
                    .then(() => {
                        
                    });

            }

        }
    
    }

</script>