<template>
    <v-card-title class="text-h6 font-weight-regular justify-space-between">
        {{ currentTitle }}
    </v-card-title>
    <v-window v-model="step">
        <v-window-item :value="1">
            <v-card-text>
                <v-text-field
                    v-model="newModel.title"
                    label="Title"
                    variant="outlined"
                    required
                ></v-text-field>
                <v-textarea
                    v-model="newModel.description"
                    label="Description"
                    variant="outlined"
                    required
                ></v-textarea>
            </v-card-text>
        </v-window-item>
        <v-window-item :value="2">
            <v-card-text>
                <v-row>
                    <v-col class="py-11">
                        <v-sheet
                            class="mx-auto"
                            max-width="500"
                            rounded="xl"
                            border
                            ><div class="pa-4">
                                <v-liste-title> Gabarits List</v-liste-title>
                                <v-responsive class="overflow-y-auto">
                                    <v-list>
                                        <v-list-item
                                            v-for="gabarit in gabarits"
                                            :key="gabarit.id"
                                            @click="appendContent(gabarit)"
                                        >
                                            <v-list-item-content>
                                                {{ gabarit.content }}
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list>
                                </v-responsive>
                            </div>
                        </v-sheet>
                    </v-col>
                    <v-col>
                        <v-container class="d-flex justify-center mt-5">
                            <v-card class="a4-size" elevation="2"
                                ><v-textarea
                                    v-model="newModel.content"
                                    variant="outlined"
                                    required
                                ></v-textarea>
                            </v-card>
                        </v-container>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-window-item>
        <v-window-item :value="3">
            <!-- Display list of categories -->

            <v-list>
                <v-list-title>Categories List</v-list-title>
                <v-card-text>
                    <v-select
                        v-model="selectedCategory"
                        :items="categories"
                        item-text="title"
                        item-value="id"
                        label="Select a Category"
                        return-object
                        @change="handleCategoryChange"
                        variant="solo"
                        chips
                        color="blue"
                        required
                    ></v-select>
                </v-card-text>
            </v-list>
        </v-window-item>
    </v-window>

    <v-card-actions>
        <v-btn v-if="step > 1" color="error" variant="text" @click="step--">
            Back
        </v-btn>
        <v-btn
            v-if="step == 1"
            color="error"
            variant="text"
            @click="resetModel"
        >
            Cancel
        </v-btn>
        <v-btn v-if="step < 3" color="info" variant="flat" @click="step++">
            Next
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
            v-if="step == 3"
            color="error"
            variant="text"
            @click="resetModel"
        >
            Cancel
        </v-btn>
        <v-btn
            v-if="step == 3"
            color="info"
            variant="flat"
            @click="createModel"
        >
            Create
        </v-btn>
    </v-card-actions>
</template>
<script>
import axios from "axios";

import { useRouter } from "vue-router";
export default {
    data() {
        return {
            router: useRouter(),
            models: [],
            step: 1,
            newModel: {
                title: "",
                description: "",
                content: "",
            },
            gabarits: [],
            gabarit: {},
            categories: [],
            selectedCategory: null,
        };
    },
    computed: {
        currentTitle() {
            switch (this.step) {
                case 1:
                    return "1. Enter the title and description of the new contract model";
                case 2:
                    return "2. Select the template(s) to model the contract model";
                default:
                    return "3. Assign the contract model to a category";
            }
        },
    },
    mounted() {
        this.fetchGabarits();
        this.fetchCategories();
    },
    methods: {
        async fetchGabarits() {
            try {
                const response = await axios.get("/api/gabarits");
                this.gabarits = response.data;
            } catch (error) {
                console.error("Error fetching gabarits:", error);
            }
        },
        async fetchCategories() {
            try {
                const response = await axios.get("/api/categories");
                this.categories = response.data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        appendContent(gabarit) {
            this.newModel.content = gabarit.content;
        },
        async createModel() {
            if (
                !this.newModel.title ||
                !this.newModel.description ||
                !this.newModel.content
            ) {
                alert(
                    "All fields, including a category selection, must be filled !"
                );
                return;
            }
            const newModelData = {
                title: this.newModel.title,
                description: this.newModel.description,
                content: this.newModel.content,
                category_id: this.selectedCategory
                    ? this.selectedCategory.id
                    : null,
            };
            try {
                const response = await axios.post("/api/models", newModelData);
                console.log("Model created:", response.data);
                this.$toast.success("Model created successfully");
                this.resetModel();
            } catch (error) {
                console.error("Error creating model:", error);
                this.$toast.error("Error creating model");
            }
        },
        resetModel() {
            this.router.push({ name: "Contract Models " });
        },
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
.mx-auto {
    margin-left: auto;
    margin-right: auto;
}
</style>
