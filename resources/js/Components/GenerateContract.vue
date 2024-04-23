<template>
    <v-container fluid>
        <v-row>
            <!-- Dynamic Form Column -->
            <v-col>
                <v-card outlined>
                    <v-card-text>
                        <v-col cols="12"> </v-col>
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- Preview Column -->
            <v-col>
                <v-card class="a4-size d-flex flex-column" dark>
                    <v-card-title>
                        Preview
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-card-text class="flex-grow-1"
                        >{{ model.content }}
                    </v-card-text>
                </v-card>
                <v-btn @click="generateContract">Save Contract</v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from "axios";

export default {
    props: {
        modelId: String,
    },
    data() {
        return {
            model: {},
        };
    },
    mounted() {
        this.fetchModel(this.$route.params.modelId);
    },
    methods: {
        async fetchModel(modelId) {
            try {
                const response = await axios.get(`/api/models/${modelId}`);
                this.model = response.data;
            } catch (error) {
                console.error("Error fetching model:", error);
            }
        },
        generateContract() {},
    },
};
</script>
<style scoped>
.a4-size {
    width: 210mm;
    height: 297mm;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>
