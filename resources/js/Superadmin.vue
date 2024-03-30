<template>
    <v-app>
        <link
            href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
            rel="stylesheet"
        />
        <v-navigation-drawer
            v-model="drawer"
            app
            image="https://cdn.vuetifyjs.com/images/backgrounds/bg-2.jpg"
            theme="dark"
            permanent
        >
            <v-list dense nav>
                <v-list-item>
                    <v-list-item-avatar
                        class="align-self-center"
                        color="white"
                        contain
                    >
                        <v-img
                            src="https://demos.creative-tim.com/vuetify-material-dashboard/favicon.ico"
                            max-height="60"
                        />
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title class="display-1" />
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <v-divider class="mb-2" />

            <v-list expand nav>
                <v-list-item
                    v-for="(item, index) in items"
                    :key="index"
                    :to="item.to"
                    class="my-4"
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

            <v-toolbar-title
                class="hidden-sm-and-down font-weight-light"
                v-text="$route.name"
            />
            <v-spacer />
            <v-btn
                class="ml-2"
                min-width="0"
                link
                exact
                :to="{ name: 'Login' }"
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
export default {
    props: {
        value: {
            type: Boolean,
            default: false,
        },
    },

    computed: {},

    methods: {},
    data: () => ({
        drawer: false,
        items: [
            {
                icon: "mdi-view-dashboard",
                title: "Dashboard",
                to: "/superadmin/dashboard",
            },
            { icon: "mdi-account", title: "Admins", to: "/superadmin/admins" },
            {
                icon: "mdi-account-circle",
                title: "Simples Users",
                to: "/superadmin/users",
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
</style>
