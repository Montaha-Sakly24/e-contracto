<template>
    <v-row>
        <v-col sm="6" md="4" v-for="model in models" :key="model.id">
            <v-card class="mx-auto fixed-size">
                <v-img
                    height="180px"
                    src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
                    cover
                ></v-img>
                <v-card-title class="text-truncate">{{
                    model.title
                }}</v-card-title>
                <v-card-text class="text-truncate">{{
                    model.description
                }}</v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            models: [],
        };
    },
    mounted() {
        this.fetchModels();
    },
    methods: {
        async fetchModels() {
            try {
                const response = await axios.get("/api/models");
                this.models = response.data;
            } catch (error) {
                console.error("Error fetching models:", error);
            }
        },
    },
};
</script>
<style scoped>
.mx-auto {
    margin-left: 8px;
    margin-right: 8px;
}
.fixed-size {
    height: 350px;
    width: 300px;
    overflow: auto;
}
</style>
