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
        <v-card>
            <v-card-title
                class="text-h6 font-weight-regular justify-space-between"
            >
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
                            v-model="newModel.content"
                            label="Description"
                            variant="outlined"
                            required
                        ></v-textarea>
                    </v-card-text>
                </v-window-item>
                <v-window-item :value="2">
                    <v-card-text>
                        <!-- Placeholder for step 2 content -->
                    </v-card-text>
                </v-window-item>
                <v-window-item :value="3">
                    <!-- Display list of categories -->
                    <v-list>
                        <v-list-title>Categories List</v-list-title>
                        <v-list-item
                            v-for="category in categories"
                            :key="category.id"
                            @click="selectedCategory = category"
                        >
                            <v-list-item-title>
                                {{ category.name }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-window-item>
            </v-window>
            <v-divider></v-divider>
            <v-card-actions>
                <v-btn
                    v-if="step > 1"
                    color="error"
                    variant="text"
                    @click="step--"
                >
                    Back
                </v-btn>
                <v-btn
                    v-if="step == 1"
                    color="error"
                    variant="text"
                    @click="cancelCreate"
                >
                    Cancel
                </v-btn>
                <v-btn
                    v-if="step < 3"
                    color="info"
                    variant="flat"
                    @click="step++"
                >
                    Next
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn
                    v-if="step == 3"
                    color="error"
                    variant="text"
                    @click="cancelCreate"
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
        </v-card>
    </v-dialog>
</template>
<script>
import axios from "axios";

export default {
    data() {
        return {
            createDialog: false,
            models: [],
            step: 1,
            newModel: {
                title: "",
                content: "",
            },
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
        this.fetchCategories();
    },
    methods: {
        async fetchCategories() {
            try {
                const response = await axios.get("/api/categories");
                this.categories = response.data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        async showCreateDialog() {
            this.step = 1;
            this.newModel.title = "";
            this.newModel.content = "";
            this.createDialog = true;
        },
        async createModel() {
            // Étapes pour créer un modèle de contrat
            try {
                // Effectuer différentes actions en fonction de l'étape
                switch (this.step) {
                    case 1:
                        // Étape 1 : Vérifier si le titre et la description sont saisis
                        if (!this.newModel.title || !this.newModel.content) {
                            return;
                        }
                        this.step++; // Passer à l'étape suivante
                        break;
                    case 2:
                        // Étape 2 : Effectuer des actions nécessaires
                        // Par exemple, récupérer les gabarits
                        // const gabaritsResponse = await axios.get("/api/gabarits");
                        // this.gabarits = gabaritsResponse.data;
                        this.step++; // Passer à l'étape suivante
                        break;
                    case 3:
                        this.fetchCategories();
                        // Étape 3 : Créer le modèle de contrat avec les données saisies
                        const newModelData = {
                            title: this.newModel.title,
                            content: this.newModel.content,
                            category_id: this.selectedCategory
                                ? this.selectedCategory.id
                                : null, // Inclure l'ID de la catégorie sélectionnée
                        };
                        // Envoyer une requête POST pour créer le modèle de contrat
                        const response = await axios.post(
                            "/api/models",
                            newModelData
                        );
                        console.log("Model created:", response.data);
                        // Réinitialiser les valeurs du formulaire
                        this.newModel.title = "";
                        this.newModel.content = "";
                        this.selectedCategory = null;
                        // Fermer le dialogue de création
                        this.createDialog = false;
                        // Afficher un message de succès
                        this.$toast.success("Model created successfully");
                        break;
                    default:
                        break;
                }
            } catch (error) {
                console.error("Error creating model:", error);
                this.$toast.error("Error creating model");
            }
        },
        cancelCreate() {
            this.createDialog = false;
        },
    },
};
</script>
