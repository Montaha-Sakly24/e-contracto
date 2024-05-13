<template>
    <div class="content-container">
        <div class="background-image"></div>
        <v-row class="pt-4 pb-4">
            <v-col cols="3">
                <v-card class="ml-3 pl-2" color="#3a7a9f">
                    <v-list dense class="category-list">
                        <v-list-title
                            >Filter Contract Models By Categories</v-list-title
                        >
                        <v-list-item-group
                            v-model="selectedCategories"
                            multiple
                        >
                            <v-list-item
                                v-for="category in categories"
                                :key="category.id"
                                class="category-item"
                            >
                                <v-list-item-action>
                                    <v-checkbox
                                        :input-value="
                                            selectedCategories.includes(
                                                category.id
                                            )
                                        "
                                        @change="toggleCategory(category.id)"
                                        class="checkbox-container"
                                    >
                                    </v-checkbox
                                    ><v-list-item-content>
                                        <v-list-item-title
                                            class="title-container"
                                        >
                                            {{ category.title }}
                                        </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-col>
            <v-col cols="9">
                <v-window
                    :value="window"
                    :show-arrows="true"
                    :key="models.length"
                >
                    <v-window-item
                        v-for="(modelChunk, index) in chunkedModels"
                        :key="index"
                    >
                        <v-row>
                            <v-col
                                cols="12"
                                sm="4"
                                v-for="model in modelChunk"
                                :key="model.id"
                            >
                                <v-hover v-slot="{ isHovering, props }">
                                    <v-card class="fixed-size" v-bind="props">
                                        <v-img
                                            height="150px"
                                            :src="contractModelImage"
                                            cover
                                        ></v-img>

                                        <v-card-title>{{
                                            model.title
                                        }}</v-card-title>
                                        <v-card-text>{{
                                            model.description
                                        }}</v-card-text>
                                        <v-overlay
                                            :model-value="isHovering"
                                            class="align-center justify-center"
                                            scrim="#036358"
                                            contained
                                        >
                                            <div class="mt-15">
                                                <v-btn
                                                    variant="flat"
                                                    @click="viewModel(model)"
                                                    >View Model</v-btn
                                                >
                                            </div>
                                        </v-overlay>
                                    </v-card>
                                </v-hover>
                            </v-col>
                        </v-row>
                    </v-window-item>
                </v-window>
            </v-col>
        </v-row>
    </div>
    <v-dialog
        v-model="viewDialog"
        transition="dialog-bottom-transition"
        max-width="unset"
    >
        <v-card class="dialog-container">
            <v-icon
                style="position: absolute; top: 10px; right: 10px"
                @click="viewDialog = false"
                >mdi-close</v-icon
            >
            <v-card-text class="a4-size">
                <div v-html="model.content"></div>
            </v-card-text>
            <v-btn
                color="orange"
                class="generate-btn mt-3"
                @click="showGeneratContractInterface"
            >
                Generate
            </v-btn>
        </v-card>
    </v-dialog>
</template>

<script>
import axios from "axios";
import { useRouter } from "vue-router";
import model from "./model.png";

export default {
    data() {
        return {
            contractModelImage: model,
            router: useRouter(),
            viewDialog: false,
            model: {},
            models: [],
            categories: [],
            selectedCategories: [],
        };
    },

    mounted() {
        this.fetchModels();
        this.fetchCategories();
    },

    computed: {
        chunkedModels() {
            const chunkSize = 6;
            return this.models.reduce((resultArray, item, index) => {
                const chunkIndex = Math.floor(index / chunkSize);

                if (!resultArray[chunkIndex]) {
                    resultArray[chunkIndex] = [];
                }

                resultArray[chunkIndex].push(item);

                return resultArray;
            }, []);
        },
    },

    watch: {
        selectedCategories: {
            handler: "filterModels",
            deep: true,
        },
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

        async fetchCategories() {
            try {
                const response = await axios.get("/api/categories");
                this.categories = response.data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },

        toggleCategory(categoryId) {
            const index = this.selectedCategories.indexOf(categoryId);
            if (index > -1) {
                this.selectedCategories.splice(index, 1);
            } else {
                this.selectedCategories.push(categoryId);
            }
            this.filterModels();
        },

        filterModels() {
            if (this.selectedCategories.length === 0) {
                this.fetchModels();
            } else {
                const selectedCategoryIds = this.selectedCategories;
                axios
                    .post(
                        "/api/models/filter",
                        { categories: selectedCategoryIds },
                        {
                            headers: { "Content-Type": "application/json" },
                        }
                    )
                    .then((response) => {
                        this.models = response.data;
                    })
                    .catch((error) => {
                        console.error("Error filtering models:", error);
                    });
            }
        },
        async viewModel(model) {
            try {
                const response = await axios.get("/api/models/" + model.id);
                this.model = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing model:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing model ",
                });
            }
        },

        showGeneratContractInterface() {
            if (!this.model || !this.model.id) {
                console.error(
                    "Cannot navigate to 'generateContract' without a modelId."
                );
                return;
            }
            this.router.push({
                name: "GenerateContract",
                params: { modelId: this.model.id.toString() },
            });
        },
    },
};
</script>
<style scoped>
.content-container {
    background-color: rgba(255, 251, 251, 0.373);
    display: flex;
    flex-direction: column;
    height: 100%;
}
.background-image {
    background-image: url("img.jpeg");
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    position: absolute;
    width: 100%;
    height: 65vh;
    opacity: 0.9;
    position: relative;
}
.category-list {
    max-width: 300px;
}
.category-item {
    display: flex;
    align-items: center;
}
.checkbox-container {
    display: flex;
    justify-content: center;
    flex: none;
}

.title-container {
    flex-grow: 1;
    display: flex;
    align-items: center;
    justify-content: start;
}

.fixed-size {
    color: white;
    background-color: #3a7a9f;
    height: 330px;
    width: 280px;
    border: 1px solid;
}
.dialog-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    margin: auto;
}

.a4-size {
    width: 210mm;
    height: 297mm;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    overflow-y: auto;
}
.generate-btn {
    align-self: end;
}
.v-dialog {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
