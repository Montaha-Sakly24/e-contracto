<template>
    <link
        href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
        rel="stylesheet"
    />
    <v-layout class="rounded rounded-md">
        <v-navigation-drawer
            color="grey-darken-2"
            permanent
        ></v-navigation-drawer>

        <v-app-bar :order="order" color="grey-lighten-2" flat>
            <template v-slot:append>
                <v-icon @click="toggleOrder">mdi-swap-horizontal</v-icon>
            </template>
        </v-app-bar>

        <v-main
            class="d-flex align-center justify-center"
            style="min-height: 300px"
        >
            Main Content
        </v-main>
    </v-layout>
</template>

<script>
export default {
    data: () => ({
        order: 0,
    }),
    methods: {
        toggleOrder() {
            this.order = this.order === 0 ? -1 : 0;
        },
    },
};
</script>
