import { createStore } from "vuex";
import gabarit from "./modules/gabarit";

const store = createStore({
    state: {
        //define variables
        token: localStorage.getItem("token") || 0,
        user: null,
    },

    mutations: {
        // update variable value
        UPDATE_TOKEN(state, payload) {
            state.token = payload;
        },

        SET_USER(state, user) {
            state.user = user;
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

        setUser({ commit }, user) {
            commit("SET_USER", user);
        },
        clearUser({ commit }) {
            commit("SET_USER", null);
        },
    },

    getters: {
        // get state variable value
        getToken: function (state) {
            return state.token;
        },

        getUser: function (state) {
            return state.user;
        },
    },
    modules: { gabarit },
});

export default store;
