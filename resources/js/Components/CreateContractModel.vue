<template>
    <v-card-title class="font-italic title-modal text-h6 justify-space-between">
        {{ currentTitle }}
    </v-card-title>
    <v-divider class="line-hr"></v-divider>

    <v-window v-model="step">
        <v-window-item :value="1">
            <v-card-text>
                <v-text-field
                    v-model="newModel.title"
                    label="Title"
                    variant="outlined"
                    required
                    :error-messages="errors.title ? [errors.title] : []"
                ></v-text-field>
                <v-textarea
                    v-model="newModel.description"
                    label="Description"
                    variant="outlined"
                    required
                    :error-messages="
                        errors.description ? [errors.description] : []
                    "
                ></v-textarea>
            </v-card-text>
        </v-window-item>
        <v-window-item :value="2">
            <v-row>
                <v-col cols="3" class="sidebar">
                    <v-card class="pa-4" outlined>
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
                                        <v-card-text v-model="newModel.content">
                                            <div v-html="gabarit.content"></div>
                                            <span
                                                v-if="errors.content"
                                                class="error-message"
                                                >{{ errors.content[0] }}</span
                                            >
                                        </v-card-text>
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
                        <div
                            v-for="(item, index) in droppedItems"
                            :key="index"
                            class="template-item"
                            @dblclick="toggleRemoveButton(index)"
                        >
                            <div v-html="item.content"></div>
                            <v-btn
                                v-if="showRemoveButton[index]"
                                @click="removeTemplate(index)"
                                >Remove</v-btn
                            >
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
                        variant="solo"
                        chips
                        color="blue"
                        required
                        :error-messages="
                            errors.category_id ? [errors.category_id] : []
                        "
                    ></v-select>
                </v-card-text>
            </v-list>
        </v-window-item>
    </v-window>
    <v-card-actions>
        <v-btn v-if="step > 1" color="error" variant="text" @click="step--"
            >Back</v-btn
        >
        <v-btn v-if="step == 1" variant="outlined" @click="resetModel"
            >Cancel</v-btn
        >
        <v-btn
            v-if="step < 3"
            color="#1e0c8c"
            variant="elevated"
            @click="step++"
            >Next</v-btn
        >
        <v-spacer></v-spacer>
        <v-btn v-if="step == 3" variant="outlined" @click="resetModel"
            >Cancel</v-btn
        >
        <v-btn
            v-if="step == 3"
            color="#1e0c8c"
            variant="elevated"
            @click="createModel"
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
            errors: {},
            showRemoveButton: [],
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
            this.newModel.content += gabaritData.content + "\n";
        },
        toggleRemoveButton(index) {
            this.showRemoveButton[index] = !this.showRemoveButton[index];
        },
        removeTemplate(index) {
            this.droppedItems.splice(index, 1);

            this.updateContent();
        },
        updateContent() {
            this.newModel.content = this.droppedItems
                .map((item) => item.content)
                .join("\n");
        },
        async createModel() {
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
                const response = await axios.post("/api/models", newModelData, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
                console.log("Model created:", response.data);
                toast.fire({
                    icon: "success",
                    title: " Model created successfully",
                });
                if (localStorage.getItem("role") == "Superadmin") {
                    this.router.push({ name: "SuperadminContractModels" });
                } else {
                    this.router.push({ name: "AdminContractModels" });
                }
            } catch (error) {
                console.error("Error creating model:", error);
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.errors
                ) {
                    this.errors = error.response.data.errors;
                } else {
                    toast.fire({
                        icon: "error",
                        title: " Error creating model",
                    });
                }
            }
        },
        resetModel() {
            this.newModel.title = "";
            this.newModel.description = "";
            this.newModel.content = "";
            this.selectedCategory = null;
            this.errors = {};
            if (localStorage.getItem("role") == "Superadmin") {
                this.router.push({ name: "SuperadminContractModels" });
            } else {
                this.router.push({ name: "AdminContractModels" });
            }
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
.template-item {
    margin-bottom: 12px;
}
</style>
