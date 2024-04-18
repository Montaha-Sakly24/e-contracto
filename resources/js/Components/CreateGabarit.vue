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
                <v-sheet class="mx-auto" max-width="400" rounded="xl" border>
                    <div class="pa-4">
                        <v-list-title>Placeholders:</v-list-title>
                        <v-responsive class="overflow-y-auto" max-height="280">
                            <v-chip-group
                                v-for="placeholder in placeholders"
                                :key="placeholder.id"
                                @click="appendContent(placeholder)"
                            >
                                <v-chip>
                                    {{ placeholder.name }}
                                </v-chip>
                            </v-chip-group>
                        </v-responsive>
                    </div>
                </v-sheet>
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
                        >
                        </v-textarea>
                    </v-card-text>
                    <customized-button></customized-button>
                    <v-color-picker hide-canvas></v-color-picker>
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
import CustomizedButton from "../Components/CustomizedButton.vue";
export default {
    components: {
        CustomizedButton,
    },
    data() {
        return {
            createDialog: false,
            drawer: false,
            placeholders: [],
            placeholder: {},
            gabarits: [],
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
        async showCreateDialog() {
            this.gabarit.title = "";
            this.gabarit.content = "";
            this.createDialog = true;
        },
        appendContent(placeholder) {
            this.gabarit.content += ` ${placeholder.identification}`;
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
