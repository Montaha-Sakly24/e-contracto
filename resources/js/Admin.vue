<template>
    <v-app>
        <link
            href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
            rel="stylesheet"
        />
        <v-navigation-drawer v-model="drawer" app theme="dark" permanent>
            <v-list dense nav>
                <v-list-item>
                    <v-list-item-avatar color="white" contain>
                        <v-img :src="logoEcontracto" max-height="150" />
                    </v-list-item-avatar>
                    <v-list-item-content class="text-center">
                        <v-list-item-title class="ml-2" style="font-size: 18px">
                            {{ account.name }} 's Account
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <v-divider class="mb-2" />

            <v-list expand nav>
                <v-list-item
                    v-for="(item, index) in items"
                    :key="index"
                    :to="item.to"
                    class="my-4 mt-n3"
                    color="black"
                    rounded="shaped"
                >
                    <v-list-item-content class="d-flex align-center">
                        <v-list-item-icon>
                            <v-icon size="28">{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title
                            class="ml-3"
                            style="font-size: 18px"
                            >{{ item.title }}</v-list-item-title
                        >
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar id="app" absolute app color="transparent" flat height="75">
            <v-btn
                class="mr-3"
                elevation="1"
                fab
                small
                @click.stop="drawer = !drawer"
            >
                <v-icon v-if="value"> mdi-view-quilt </v-icon>

                <v-icon v-else> mdi-dots-vertical </v-icon>
            </v-btn>

            <v-spacer />
            <v-btn
                class="ml-2"
                min-width="0"
                link
                exact
                @click.prevent="logout"
            >
                <v-icon>mdi-logout</v-icon>
            </v-btn>
        </v-app-bar>
        <div class="page-content" :class="{ 'content-shifted': drawer }">
            <router-view></router-view>
        </div>
    </v-app>
</template>
<script>
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import contract from "./Pages/contract.png";
export default {
    props: {
        value: {
            type: Boolean,
            default: false,
        },
    },

    mounted() {
        this.fetchProfile();
    },
    methods: {
        logout() {
            localStorage.clear();
            this.$router.push({ name: "Login" });
        },
        async fetchProfile() {
            const response = await axios.get("/api/profile", {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });
            this.account = response.data;
        },
    },
    data: () => ({
        router: useRouter(),
        drawer: false,
        logoEcontracto: contract,
        account: {
            name: "",
        },
        items: [
            {
                icon: "mdi-view-dashboard",
                title: "Dashboard",
                to: "/admin/dashboardAdmin",
            },
            {
                icon: "mdi-account-circle",
                title: "Profile",
                to: "/admin/profile",
            },
            {
                icon: "mdi-account",
                title: "Contract Seekers",
                to: "/admin/users",
            },
            {
                icon: "mdi-folder",
                title: "Contract Categories",
                to: "/admin/categories",
            },
            {
                icon: "mdi-format-quote-open",
                title: "Placeholders",
                to: "/admin/placeholders",
            },
            {
                icon: "mdi-file-document-outline",
                title: "Gabarits",
                to: "/admin/gabarits",
            },
            {
                icon: "mdi-file-document",
                title: "Contract Models",
                to: "/admin/models",
            },
        ],
    }),
};
</script>
<style scoped>
.page-content {
    margin-top: 64px;
    transition: margin-left 0.3s;
}

.content-shifted {
    margin-left: 250px;
}
.v-navigation-drawer {
    background-image: linear-gradient(30deg, #3b6c76, #0c4d86);
}
</style>
