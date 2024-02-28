<template>
    <div>Dashboard page</div>

    <v-btn type="button" color="primary" @click="logout"> Logout </v-btn>
</template>
<script>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
export default {
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
};
</script>
