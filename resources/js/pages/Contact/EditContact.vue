<template>
    <div class="content-body">
        <h4>Edit Contact</h4>

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
                <label for="image">Image</label>
                <input type="file" class="form-control" id="file" @change="onInputChange($event)" />
                <img v-if="show_image" :src="show_image" alt="" height="80" width="80">
            </div><br>

            <button type="submit" class="btn btn-info" @click.prevent="updateContact">Update</button>
        </form>
    </div>
</template>

<script>

    export default {
        name: 'EditContact',

        data() {
            return {
                file_directory: '',
                first_name: '',
                last_name: '',
                email_address: '',
                phone_number: '',
                image_file: null,
                contactId: this.$route.params.contact_id,
                show_image:null,
                errors:{},
            }
        },

        methods: {

            getContact() {

                let token = localStorage.getItem('user')
                let url = `/contact/edit/${this.contactId}/${token}`
                
                const headers = {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                }

                axios.get(url,{'headers':headers})
                    .then(response => {

                        if( response.data && response.data.contact ) {

                            this.file_directory = response.data.file_directory

                            let contact = response.data.contact

                            this.first_name = contact.first_name
                            this.last_name = contact.last_name
                            this.email_address = contact.email_address
                            this.phone_number = contact.phone_number
                            
                            if( contact.image ) {
                                this.show_image = this.file_directory+'/'+contact.image
                            }
                            
                        }

                    })
                    .catch(error => {

                        if( error.response ) {
                            this.errors = error.response.data.errors
                        }

                    })
                    .then(() => {
                        
                    })

            },

            updateContact() {

                let token = localStorage.getItem('user')

                const formData = new FormData();
                formData.append("first_name", this.first_name);
                formData.append("last_name", this.last_name);
                formData.append("image", this.image_file);
                formData.append("email_address", this.email_address);
                formData.append("phone_number", this.phone_number);
                formData.append('token', token)
                formData.append("_method", "put")

                const url = `/contact/update/${this.contactId}`

                const headers = {
                    "Content-Type": "application/json",
                    "Authorization": 'Bearer '+token
                }

                let data = {
                    'title' : this.title,
                    'description' : this.description,
                    'price' : this.price,
                }

                //actually this is put request but form data creates issue when not set it to post to put
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
                        
                    })


            },

            //for image file
            onInputChange(event) {
                const file = event.target.files[0]
                this.image_file = file
                let that = this
                let reader = new FileReader()
                reader.onload = (event) => {
                    // The file's text will be printed here
                    that.show_image = event.target.result
                }
                reader.readAsDataURL(file)
            },

        },

        mounted() {
            
            this.getContact()

        }
    
    }

</script>