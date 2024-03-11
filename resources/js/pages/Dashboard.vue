<template>
    <link
        href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
        rel="stylesheet"
    />
    <v-navigation-drawer v-model="drawer" temporary>
        <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/men/78.jpg"
            title="John Leider"
        ></v-list-item>

        <v-divider></v-divider>

        <v-list density="compact" nav>
            <v-list-item
                prepend-icon="mdi-view-dashboard"
                title="Home"
                value="home"
            ></v-list-item>
            <v-list-item
                prepend-icon="mdi-forum"
                title="About"
                value="about"
            ></v-list-item>
            <v-list-item type="button" color="primary" @click="logout">
                Logout
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
    <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
</template>
<script>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
export default {
    components: {},
    setup() {
        const router = useRouter();
        const store = useStore();

        function logout() {
            store.dispatch("removeToken");
            router.push({ name: "Home" });
        }

        return {
            logout,
        };
    },
    data() {
        return {
            drawer: null,
        };
    },
};
</script>
