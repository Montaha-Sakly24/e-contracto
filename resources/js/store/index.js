import { createStore } from "vuex";

const store = createStore({
    state: {
        //define variables
        token: localStorage.getItem("token") || 0,
        //role: null,
    },

    mutations: {
        // update variable value
        UPDATE_TOKEN(state, payload) {
            state.token = payload;
        },
        //
        // Set user role
        // SET_ROLE(state, payload) {
        //     state.role = payload;
        // },
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
        // //
        // setRole(context, payload) {
        //     context.commit("SET_ROLE", payload);
        // },
    },

    getters: {
        // get state variable value
        getToken: function (state) {
            return state.token;
        },

        // getRole: function (state) {
        //     return state.role;
        // },
    },
});

export default store;
