import Vue from "vue"
import Router from "vue-router"

//Contacts
import Home from '../pages/Contact/AllContacts'
import AddContact from '../pages/Contact/AddContact'
import EditContact from '../pages/Contact/EditContact'

//auth
import Signup from '../pages/Auth/Signup'
import Login from '../pages/Auth/Login'

Vue.use(Router)

const ifNotAuthenticated = (to, from, next) => {
	if (!window.localStorage.getItem('user')) {
		next()
		return
	}
	next("/")
}

const ifAuthenticated = (to, from, next) => {
	if (window.localStorage.getItem('user')) {
		next()
		return
	}
	next("/login")
}

export default new Router({

	mode: "history", 
	routes: [
		{
			path:'/',
			component:Home,
			name:'Home',
			meta: {title: 'Home'},
			beforeEnter: ifAuthenticated

		},
		{
			path:'/add-contact',
			component:AddContact,
			name:'AddContact',
			meta: {title: 'Add Contact'},
			beforeEnter: ifAuthenticated
		},
		{
			path:'/edit-contact/:contact_id',
			component:EditContact,
			name:'EditContact',
			meta: {title: 'Edit Contact'},
			beforeEnter: ifAuthenticated
		},
		{
			path:'/register',
			component:Signup,
			name:'Signup',
			meta: {title: 'Signup'},
			beforeEnter: ifNotAuthenticated
		},
		{
			path:'/login',
			component:Login,
			name:'Login',
			meta: {title: 'Login'},
			beforeEnter: ifNotAuthenticated
		},
	]

})