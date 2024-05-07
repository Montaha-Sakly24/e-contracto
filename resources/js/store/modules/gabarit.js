const PREFIX_GABARIT = "gabarits";
const PREFIX_PLACEHOLDER = "placeholders";

import axios from "axios";
const state = {
    gabarits: [],
    placeholders: [],
    gabarit: null,
};
const getters = {
    getListGabarit: (state) => state.gabarits,
    getListPlaceholders: (state) => state.placeholders,
    getOneGabarit: (state) => state.gabarit,
};
const mutations = {
    SET_DATA_GABARITS(state, payload) {
        state.gabarits = payload;
    },
    SET_DATA_PLACEHOLDERS(state, payload) {
        state.placeholders = payload;
    },
    ADD_NEW_GABARIT(state, payload) {
        state.gabarits.unshift(payload);
    },
    SET_ONE_GABARIT(state, payload) {
        state.gabarit = payload;
    },
    UPDATE_GABARIT(state, payload) {
        state.gabarits = state.gabarits.map((gabarit) => {
            if (gabarit.id === payload.id) {
                return { ...gabarit, ...payload };
            }
            return gabarit;
        });
    },
};
const actions = {
    async getAllGabarits({ commit }) {
        try {
            const response = await axios.get(`/api/${PREFIX_GABARIT}`);
            commit("SET_DATA_GABARITS", response.data);
            return true;
        } catch (error) {
            console.error(error);
            return false;
        }
    },
    async getAllPlaceholders({ commit }) {
        try {
            const response = await axios.get(`/api/${PREFIX_PLACEHOLDER}`);
            commit("SET_DATA_PLACEHOLDERS", response.data);
            return true;
        } catch (error) {
            console.error(error);
            return false;
        }
    },
    async createGabarit({ commit }, payload) {
        try {
            const response = await axios.post(
                `/api/${PREFIX_GABARIT}`,
                payload
            );
            commit("ADD_NEW_GABARIT", response.data.gabarit);
            return true;
        } catch (error) {
            console.error(error);
            return false;
        }
    },
    async getGabaritById({ commit }, payload) {
        try {
            const response = await axios.get(
                `/api/${PREFIX_GABARIT}/${payload}`
            );
            commit("SET_ONE_GABARIT", response.data);
            return true;
        } catch (error) {
            console.error(error);
            return false;
        }
    },
    async updateGabarit({ commit }, payload) {
        try {
            const response = await axios.put(
                `/api/${PREFIX_GABARIT}/${payload.id}`,
                payload
            );
            commit("UPDATE_GABARIT", response.data);
            return true;
        } catch (error) {
            console.error(error);
            return false;
        }
    },
};
export default {
    state,
    getters,
    mutations,
    actions,
};
