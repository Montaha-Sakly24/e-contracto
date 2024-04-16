import { createStore } from "vuex";

const store = createStore({
    state: {
        //define variables
        token: localStorage.getItem("token") || 0,
        userRole: null,
    },

    mutations: {
        // update variable value
        UPDATE_TOKEN(state, payload) {
            state.token = payload;
        },

        UPDATE_USER_ROLE(state, payload) {
            state.userRole = payload;
        },
    },

    actions: {
        // action to be performed
        setToken(context, payload) {
            localStorage.setItem("token", payload);
            context.commit("UPDATE_TOKEN", payload);
        },
        removeToken(context) {
            localStorage.removeItem("token");
            context.commit("UPDATE_TOKEN", 0);
        },

        setUserRole(context, payload) {
            context.commit("UPDATE_USER_ROLE", payload);
        },
    },

    getters: {
        // get state variable value
        getToken: function (state) {
            return state.token;
        },

        getUserRole: function (state) {
            return state.userRole;
        },
    },
});

export default store;
