<template>
    <v-card-title
        class="title text-uppercase font-weight-bold font-italic title-modal"
    >
        Update Model
    </v-card-title>
    <v-divider class="line-hr"></v-divider>
    <v-row>
        <v-col>
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
                <v-textarea
                    v-model="newModel.content"
                    label="Content"
                    variant="outlined"
                    required
                >
                </v-textarea>
            </v-card-text>
        </v-col>
        <v-col>
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
        </v-col>
    </v-row>
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
                            <div class="d-flex justify-between align-center">
                                <span class="article-title">{{
                                    gabarit.title
                                }}</span>
                                <v-icon v-if="!panel[index]"
                                    >mdi-chevron-down</v-icon
                                >
                                <v-icon v-else>mdi-chevron-up</v-icon>
                            </div>
                        </v-expansion-panel-header>
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
    <v-divider class="line-hr-footer"></v-divider>
    <v-card-actions class="actions-msg-btn-modal">
        <v-spacer></v-spacer>
        <v-btn variant="outlined" @click="resetModel">Cancel</v-btn>
        <v-btn color="#1e0c8c" variant="elevated" @click="updateModel">
            Save Changes
        </v-btn>
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

            newModel: {
                id: null,
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
        previewContent() {
            return this.newModel.content;
        },
    },

    mounted() {
        this.fetchGabarits();
        this.fetchCategories();
        const modelId = this.$route.params.modelId;
        this.fetchModelData(modelId);
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
        async fetchModelData(modelId) {
            try {
                const response = await axios.get(`/api/models/${modelId}`);
                const modelData = response.data;
                this.newModel = {
                    id: modelData.id,
                    title: modelData.title,
                    description: modelData.description,
                    content: modelData.content,
                };
                console.log("Content of newModel:", this.newModel.content);
                this.selectedCategory = this.categories.find(
                    (cat) => cat.id === modelData.category_id
                );
                if (modelData.gabarits && modelData.gabarits.length > 0) {
                    this.droppedItems = modelData.gabarits.map((gabaritId) =>
                        this.gabarits.find((gab) => gab.id === gabaritId)
                    );
                }
            } catch (error) {
                console.error("Error fetching model data:", error);
            }
        },
        async updateModel() {
            const updatedModelData = {
                title: this.newModel.title,
                description: this.newModel.description,
                content: this.newModel.content,
                category_id: this.selectedCategory
                    ? this.selectedCategory.id
                    : null,
                gabarits: this.droppedItems.map((item) => item.id),
            };
            try {
                const response = await axios.put(
                    `/api/models/${this.newModel.id}`,
                    updatedModelData
                );
                console.log("Model updated:", response.data);
                toast.fire({
                    icon: "success",
                    title: "Model updated successfully",
                });
                if (localStorage.getItem("role") == "Superadmin") {
                    this.router.push({ name: "SuperadminContractModels" });
                } else {
                    this.router.push({ name: "AdminContractModels" });
                }
            } catch (error) {
                console.error("Error updating model:", error);
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.errors
                ) {
                    this.errors = error.response.data.errors;
                } else {
                    toast.fire({
                        icon: "error",
                        title: "Error updating model",
                    });
                }
            }
        },
        resetModel() {
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
/* .mx-auto {
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
} */
.template-item {
    margin-bottom: 12px;
}
</style>
