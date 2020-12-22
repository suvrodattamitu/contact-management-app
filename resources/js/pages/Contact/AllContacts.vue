<template>
    <div class="content-body">
       <h4>Contacts</h4>

       <div v-if="totalContacts" class="contacts-table">

            <div class="row">
                <div class="col-md-2">
                    <input type="text" name="search_contact" v-model="search_string" placeholder="Search Contacts" @keyup="realTimeSearch">
                </div>
            </div>

           <router-link class="btn btn-sm btn-primary redirect-button" to="/add-contact">Add Contact</router-link>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Image</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody v-for="(contact,index) in contacts" :key="index">
                    <tr>
                        <th scope="row">{{ contact.id }}</th>
                        <td>{{ contact.first_name }}</td>
                        <td>{{ contact.last_name }}</td>
                        <td>{{ contact.email_address }}</td>
                        <td>{{ contact.phone_number }}</td>
                        <td>
                            <img v-if="contact.image" height="80" width="80" :src="file_directory+'/'+contact.image" />
                            <p v-else> No Image </p>
                        </td>
                        <td class="custom-btn-group">
                            <button type="button" class="btn btn-sm btn-success" @click.prevent="redirectToEdit(contact.id)">Edit</button>
                            <button type="button" class="btn btn-sm btn-warning" @click.prevent="deleteContact(contact.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else>
            <p>No contacts found!! Please add one</p>
            <router-link class="btn btn-sm btn-primary" to="/add-contact">Add Contact</router-link>
        </div>

    </div>
</template>

<script>

    import _ from 'lodash';

    export default {
        name: 'AllContacts',

        data() {
            return {
                file_directory: '',
                contacts: [],
                search_string: '',
                totalContacts: 0
            }
        },

        methods: {

            realTimeSearch: _.debounce(function(){
                this.getContacts();
            }),

            getContacts() {

                let token = localStorage.getItem('user')
                let url = '/contacts/'+ token + '?q='+this.search_string
                
                axios.get(url)
                    .then(response => {

                        if( response.data.contacts ) {
                            this.contacts = response.data.contacts
                            this.file_directory = response.data.file_directory
                            this.totalContacts  = response.data.totalContacts
                        }

                    })
                    .catch(error => {

                    })
                    .then(() => {
                        
                    })

            },

            deleteContact( contactId ) {

                let token = localStorage.getItem('user')

                const url = "/contact/delete/"+contactId

                const headers = {
                    "Content-Type": "application/json",
                    "Authorization": 'Bearer '+token
                }

                axios.delete(url, {'headers':headers})
                    .then(response => {

                        this.getContacts()

                    })
                    .catch(error => {

                    })
                    .then(() => {
                        
                    })

            },

            redirectToEdit( contactId ) {
                this.$router.push(`/edit-contact/${contactId}`)
            }

        },

        mounted() {

            this.getContacts()

        }
    
    }

</script>