<template>
    <v-btn
        prepend-icon="mdi-file-document"
        color="info"
        class="ml-3"
        @click="showCreateDialog"
    >
        Add New
    </v-btn>
    <v-dialog
        v-model="createDialog"
        width="1200"
        transition="dialog-bottom-transition"
    >
        <v-row>
            <v-col>
                <v-card>
                    <v-list-title>Placeholders:</v-list-title>
                    <draggable
                        v-model="placeholders"
                        :item-key="generateItemKey"
                    >
                        <template #item="{ element: placeholder, index }">
                            <v-list-item @dragstart="onDragStart(index)">
                                {{ placeholder.name }}
                            </v-list-item>
                        </template>
                    </draggable>
                </v-card>
            </v-col>
            <v-col>
                <v-card width="793">
                    <v-card-title>Add New Gabarit</v-card-title>
                    <v-card-text>
                        <v-text-field
                            v-model="gabarit.title"
                            label="Title"
                            variant="outlined"
                            required
                        ></v-text-field>
                        <v-textarea
                            v-model="gabarit.content"
                            ref="content"
                            label="Content"
                            variant="outlined"
                            required
                            @drop.prevent="onDrop"
                            @dragover.prevent
                        >
                        </v-textarea>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="info"
                            variant="flat"
                            @click="createGabarit"
                            >Create</v-btn
                        >
                        <v-btn color="error" @click="cancelCreate"
                            >Cancel</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-dialog>
</template>

<script>
import axios from "axios";
import draggable from "vuedraggable";

export default {
    components: {
        draggable,
    },
    data() {
        return {
            createDialog: false,
            drawer: false,
            placeholders: [],
            gabarit: [],
            draggedPlaceholder: null,
            gabarit: {
                title: "",
                content: "",
            },
        };
    },
    mounted() {
        this.fetchPlaceholders();
        this.fetchGabarits();
    },
    methods: {
        async fetchPlaceholders() {
            try {
                const response = await axios.get("/api/placeholders");
                this.placeholders = response.data;
            } catch (error) {
                console.error("Error fetching placeholders:", error);
            }
        },
        async fetchGabarits() {
            const response = await axios.get("/api/gabarits");
            this.gabarits = response.data;
        },
        onDragStart(index) {
            // Store the dragged placeholder
            this.draggedPlaceholder = this.placeholders[index];
        },
        onDrop(event) {
            // Insert the dragged placeholder into the content
            if (this.draggedPlaceholder) {
                const startPosition = event.target.selectionStart;
                const endPosition = event.target.selectionEnd;
                const content = this.$refs.content;
                const textBefore = content.value.substring(0, startPosition);
                const textAfter = content.value.substring(endPosition);
                const placeholderText = `{${this.draggedPlaceholder.name}}`;
                content.value = `${textBefore}${placeholderText}${textAfter}`;
                this.draggedPlaceholder = null;
            }
        },
        generateItemKey(index) {
            const placeholder = this.placeholders[index];
            return placeholder ? placeholder.id : index; // Use index as fallback if id is undefined
        },
        async showCreateDialog() {
            this.gabarit.title = "";
            this.gabarit.content = "";
            this.createDialog = true;
        },
        async createGabarit() {
            const response = await axios.post("/api/gabarits", this.gabarit);
            console.log(response);
            this.createDialog = false;
            toast.fire({
                icon: "success",
                title: "Gabarit created successfully ",
            });
            await this.fetchGabarits();
        },
        cancelCreate() {
            this.createDialog = false;
        },
    },
};
</script>

<style scoped>
.page-container {
    background-color: #f2f2f2;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    padding: 20px;
}
.page-content {
    margin-top: 64px;
    transition: margin-left 0.3s;
}
.content-shifted {
    margin-left: 130px;
}
</style>
