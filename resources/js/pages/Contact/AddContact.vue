<template>
    <div class="content-body">
        <h4>Add Contact</h4>

        <router-link class="btn btn-sm btn-primary redirect-button" to="/">All Contacts</router-link>

        <form enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="first-name">First Name</label>
                <input type="text" v-model="first_name" class="form-control" placeholder="Enter First Name">
                <p v-if="errors.first_name" style="color:red">{{ errors.first_name[0] }}</p>
            </div><br>
            <div class="form-group">
                <label for="last-name">Last Name</label>
                <input type="text" v-model="last_name" class="form-control" placeholder="Enter Last Name">
                <p v-if="errors.last_name" style="color:red">{{ errors.last_name[0] }}</p>
            </div><br>
            <div class="form-group">
                <label for="email-address">Email Address</label>
                <input type="text" v-model="email_address" class="form-control" placeholder="Email Address">
                <p v-if="errors.email_address" style="color:red">{{ errors.email_address[0] }}</p>
            </div><br>
            <div class="form-group">
                <label for="email-address">Phone Number</label>
                <input type="text" v-model="phone_number" class="form-control" placeholder="Phone Number">
                <p v-if="errors.phone_number" style="color:red">{{ errors.phone_number[0] }}</p>
            </div><br>
            <div class="form-group">
                <label for="image">Image</label><br>
                <input type="file" id="file" @change="onInputChange($event)" />
                <img v-if="show_image" :src="show_image" height="80" width="80" />
            </div><br>

            <button type="submit" class="btn btn-info" @click.prevent="addProduct">Add</button>
        </form>
    </div>
</template>

<script>

    export default {
        name: 'AddContact',

        data() {
            return {
                first_name: '',
                last_name: '',
                email_address: '',
                phone_number: '',
                image_file: null,
                show_image: null,
                errors:{}
            }
        },

        methods: {

            addProduct() {

                let token = localStorage.getItem('user')

                let formData = new FormData();
                formData.append("first_name", this.first_name);
                formData.append("last_name", this.last_name);
                formData.append("image", this.image_file);
                formData.append("email_address", this.email_address);
                formData.append("phone_number", this.phone_number);
                formData.append("token", token);

                const url = "/contact/add"

                const headers = {
                    "Content-Type": "application/json",
                    "Authorization": 'Bearer '+token
                }

                axios.post(url, formData, { 'headers' : headers } )
                    .then(response => {

                        this.$router.push("/");

                    })
                    .catch(error => {

                        if( error.response ) {
                            this.errors = error.response.data.errors
                        }

                    })
                    .then(() => {
                        
                    });


            },

            //for image file
            onInputChange(event) {
                const file = event.target.files[0];
                this.image_file = file;
                let that = this;
                let reader = new FileReader();
                reader.onload = (event) => {
                    // The file's text will be printed here
                    that.show_image = event.target.result;
                };
                reader.readAsDataURL(file);
            },

        }
    
    }

</script>