<template>
    <div>
        <v-card>
            <v-card-title>Total Categories</v-card-title>
            <v-card-text>{{ categoriesCount }}</v-card-text>
        </v-card>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            categoriesCount: null,
        };
    },
    mounted() {
        this.getCategoriesCount();
    },
    methods: {
        async getCategoriesCount() {
            try {
                const instance = axios.create({
                    baseURL: "http://localhost:8000/api",
                });
                const response = await instance.get("/admin/categories/count");
                this.categoriesCount = response.data;
            } catch (error) {
                console.error("Error fetching total admins:", error);
            }
        },
    },
};
</script>
