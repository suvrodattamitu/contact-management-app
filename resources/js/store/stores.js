export default{

    state: {
        
        authenticated: localStorage.getItem("user") || ""
        
    },

    getters: {

	  	isAuthenticated(state) {
            return state.authenticated
        }
         
    },

    actions: {

        authentication_action(context,payload) {
            context.commit('update_authentication',payload)
        }

    },

    mutations: {

        update_authentication(state,data) {
            return state.authenticated = data
        }

    }

}
