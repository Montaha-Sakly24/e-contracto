<template>
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
            </div>
        </div>
        <router-view></router-view>
    </div>
</template>
