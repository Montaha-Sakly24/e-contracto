<template>
    <div class="page-container">
        <v-tabs v-model="activeTab" show-arrows>
            <v-tab v-for="item in tabs" :key="item.tab" :value="item.tab">
                <v-icon>{{ item.icon }}</v-icon>
                <span>{{ item.title }}</span>
            </v-tab>
        </v-tabs>
        <v-divider />

        <v-window v-model="activeTab" class="mt-5 disable-tab-transition">
            <v-window-item value="account">
                <UserAccount></UserAccount>
            </v-window-item>

            <v-window-item value="security">
                <AccountSettings></AccountSettings>
            </v-window-item>

            <v-window-item value="delete">
                <DeleteAccount></DeleteAccount>
            </v-window-item>
        </v-window>
    </div>
</template>

<script>
import { useRoute } from "vue-router";
import { ref } from "vue";
import UserAccount from "../Components/UserAccount.vue";
import AccountSettings from "../Components/AccountSettings.vue";
import DeleteAccount from "../Components/DeleteAccount.vue";

export default {
    components: {
        UserAccount,
        AccountSettings,
        DeleteAccount,
    },
    setup() {
        const route = useRoute();
        const activeTab = ref(route.params.tab);

        // tabs
        const tabs = [
            {
                title: "Account",
                icon: "bx-user",
                tab: "account",
            },
            {
                title: "Security",
                icon: "bx-lock-open",
                tab: "security",
            },
            {
                title: "Delete Account",
                icon: "bx-lock-open",
                tab: "delete",
            },
        ];
        return {
            activeTab,
            tabs,
        };
    },
};
</script>

<style scoped>
.page-container {
    background-color: #f2f2f2;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    padding: 0 20px;
}

.page-content {
    flex-grow: 1;
    padding: 20px;
    padding: 20px 0;
}
.disable-tab-transition .v-window-item {
    transition: none;
}
</style>
