<template>
    <v-app>
        <v-app-bar absolute color="#82B1FF" dark prominent>
            <div>
                <div>
                    <div>
                        <router-link :to="{ name: 'Home' }">Home</router-link>
                    </div>
                    <div>
                        <router-link
                            :to="{ name: 'Login' }"
                            v-if="$store.getters.getToken == 0"
                            >Login</router-link
                        >
                    </div>
                    <div>
                        <router-link
                            :to="{ name: 'Register' }"
                            v-if="$store.getters.getToken == 0"
                            >Register</router-link
                        >
                    </div>
                    <div>
                        <router-link
                            :to="{ name: 'Dashboard' }"
                            v-if="$store.getters.getToken != 0"
                            >Dashboard</router-link
                        >
                        <router-link
                            :to="{ name: 'ContractCategories' }"
                            v-if="$store.getters.getToken != 0"
                            >ContractCategories</router-link
                        >
                    </div>
                </div>
            </div>
        </v-app-bar>
        <div class="page-content"><router-view></router-view></div>
    </v-app>
</template>
<script>
import { mapState, mapMutations } from "vuex";
export default {
    computed: {
        ...mapState(["drawer"]),
    },

    methods: {
        ...mapMutations({
            setDrawer: "SET_DRAWER",
        }),
    },
    data() {
        return {
            drawer: null,
        };
    },
};
</script>
<style>
.page-content {
    margin-top: 64px; /* Hauteur de la barre de navigation */
}
</style>
