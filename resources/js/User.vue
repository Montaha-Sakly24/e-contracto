<template>
    <v-app>
        <link
            href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
            rel="stylesheet"
        />
        <v-navigation-drawer v-model="drawer" app theme="dark" permanent>
            <v-list dense nav>
                <v-list-item class="d-flex justify-center">
                    <v-list-item-avatar
                        rounded="lg"
                        size="100"
                        class="me-6"
                        contain
                    >
                        <img :src="'/storage/' + account.avatar" />
                    </v-list-item-avatar>
                </v-list-item>
                <v-list-item class="d-flex justify-center">
                    {{ account.name }}</v-list-item
                >
            </v-list>

            <v-divider class="mb-2" />

            <v-list expand nav>
                <v-list-item
                    v-for="(item, index) in items"
                    :key="index"
                    :to="item.to"
                    class="my-4"
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

        <v-app-bar app color="#0c4d86">
            <v-app-bar-nav-icon
                variant="text"
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <v-img :src="logoEcontracto" max-height="80" />
            <v-spacer></v-spacer>
            <v-btn @click="viewModels"> Contract Models </v-btn>
        </v-app-bar>

        <div class="page-content" :class="{ 'content-shifted': drawer }">
            <router-view></router-view>
        </div>
    </v-app>
</template>
<script>
import axios from "axios";
import { useRouter } from "vue-router";
import ContractModels from "./Pages/ContractModels.vue";
import contract from "./Pages/contract.png";

export default {
    components: {
        ContractModels,
    },
    props: {
        value: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            router: useRouter(),
            drawer: false,
            logoEcontracto: contract,
            items: [
                {
                    icon: "mdi-account-circle",
                    title: "Profile",
                    to: "/user/profile",
                },
                {
                    icon: "mdi-file-outline",
                    title: "Contracts",
                    to: "/user/contracts",
                },
            ],
            account: {
                avatar: "",
                name: "",
            },
        };
    },
    mounted() {
        this.fetchProfile();
    },
    methods: {
        async fetchProfile() {
            const response = await axios.get("/api/profile", {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });
            this.account = response.data;
        },
        viewModels() {
            this.$router.push({ name: "contractModels" });
        },
        viewCategories() {
            this.$router.push({ name: "contractCategories" });
        },
    },
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
    background-image: linear-gradient(30deg, #7aaeb9, #0c4d86);
}
</style>
