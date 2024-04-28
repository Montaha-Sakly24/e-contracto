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
                <v-textarea
                    v-model="newModel.content"
                    label="Content"
                    variant="outlined"
                    required
                ></v-textarea>
            </v-card-text>
        </v-window-item>
        <v-window-item :value="2">
            <v-row>
                <v-col cols="3" class="sidebar">
                    <v-card class="pa-4" outlined>
                        <div class="d-flex justify-between align-center mb-4">
                            <v-btn @click="all">Tous</v-btn>
                            <v-btn @click="none">Aucun</v-btn>
                        </div>
                        <v-expansion-panels>
                            <v-expansion-panel
                                v-for="(gabarit, index) in gabarits"
                                :key="gabarit.id"
                            >
                                <v-expansion-panel-header
                                    @click="togglePanel(index)"
                                    draggable="true"
                                    @dragstart="onDragStart($event, index)"
                                >
                                    <div
                                        class="d-flex justify-between align-center"
                                    >
                                        <span class="article-title">{{
                                            gabarit.title
                                        }}</span>
                                        <v-icon v-if="!panel[index]"
                                            >mdi-chevron-down</v-icon
                                        >
                                        <v-icon v-else>mdi-chevron-up</v-icon>
                                    </div>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content v-if="panel[index]">
                                    <v-card>
                                        <v-card-text>{{
                                            gabarit.content
                                        }}</v-card-text>
                                    </v-card>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card>
                </v-col>
                <v-col cols="8" class="text-area">
                    <v-card
                        class="pa-16"
                        outlined
                        @dragover.prevent="allowDrop"
                        @drop="onDrop"
                    >
                        <div v-for="(item, index) in droppedItems" :key="index">
                            <h2>{{ item.title }}</h2>
                            <p>{{ item.content }}</p>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </v-window-item>

        <v-window-item :value="3">
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
        <v-btn v-if="step > 1" color="error" variant="text" @click="step--"
            >Back</v-btn
        >
        <v-btn v-if="step == 1" color="error" variant="text" @click="resetModel"
            >Cancel</v-btn
        >
        <v-btn v-if="step < 3" color="info" variant="flat" @click="step++"
            >Next</v-btn
        >
        <v-spacer></v-spacer>
        <v-btn v-if="step == 3" color="error" variant="text" @click="resetModel"
            >Cancel</v-btn
        >
        <v-btn v-if="step == 3" color="info" variant="flat" @click="createModel"
            >Create</v-btn
        >
    </v-card-actions>
</template>
<script>
import axios from "axios";
import Draggable from "vuedraggable";
import { useRouter } from "vue-router";

export default {
    components: {
        Draggable,
    },
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
            items: [],
            panel: [],
            articleContents: [],
            droppedItems: [],
            draggedArticle: { title: "", content: "" },
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
                this.panel = new Array(this.gabarits.length).fill(false);
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
        togglePanel(index) {
            this.panel[index] = !this.panel[index];
        },
        all() {
            this.panel = Array(this.items.length).fill(true);
        },
        none() {
            this.panel = Array(this.items.length).fill(false);
        },
        allowDrop(event) {
            event.preventDefault();
        },
        onDragStart(event, index) {
            const gabaritToDrag = this.gabarits[index];
            event.dataTransfer.setData(
                "application/json",
                JSON.stringify(gabaritToDrag)
            );
        },

        onDrop(event) {
            event.preventDefault();
            const gabaritData = JSON.parse(
                event.dataTransfer.getData("application/json")
            );
            this.droppedItems.push(gabaritData);
        },

        async createModel() {
            if (!this.newModel.title || !this.newModel.description) {
                alert(
                    "All fields, including a category selection, must be filled!"
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
                gabarits: this.droppedItems.map((item) => item.id),
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
            this.router.push({ name: "Contract Models" });
        },
    },
};
</script>

<style scoped>
.mx-auto {
    margin-left: auto;
    margin-right: auto;
}
.article {
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
    cursor: pointer;
}
.article-title {
    font-size: 18px;
    font-weight: bold;
    margin: 10px 5px 18px 10px;
}
.sidebar {
    margin-left: 30px;
}
.text-area {
    margin-left: 40px;
}
</style>
