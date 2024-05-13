<template>
    <!-- <div>
        <template v-if="hasRole('Superadmin')">
            <Superadmin />
        </template>
        <template v-else-if="hasRole('Admin')">
            <Admin />
        </template>
        <template v-else-if="hasRole('Simple User')">
            <User />
        </template>
    </div> -->
    <div>
        <router-view></router-view>
    </div>
</template>
<script>
import Superadmin from "./Superadmin.vue";
import Admin from "./Admin.vue";
import User from "./User.vue";
export default {
    components: {
        Superadmin,
        Admin,
        User,
    },
    data() {
        return {
            drawer: false,
        };
    },
    methods: {
        hasRole(role) {
            const userRoles = ["Admin", "Superadmin", "Simple User"];
            return userRoles.includes(role.toLowerCase());
        },
    },
};
</script>
