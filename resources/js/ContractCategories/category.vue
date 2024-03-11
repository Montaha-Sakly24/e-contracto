<template>
    <div>
        <v-container>
            <!-- Bouton pour créer une nouvelle catégorie -->
            <v-btn color="primary" @click="showCreateDialog"
                >Créer une nouvelle catégorie</v-btn
            >

            <!-- Tableau pour afficher les catégories -->
            <v-data-table :headers="headers" :items="categories" item-key="id">
                <template v-slot:items="props">
                    <td>{{ props.item.title }}</td>
                    <td>{{ props.item.description }}</td>
                    <td>
                        <!-- Boutons d'action pour modifier et supprimer une catégorie -->
                        <v-btn
                            color="primary"
                            @click="showEditDialog(props.item)"
                            >Modifier</v-btn
                        >
                        <v-btn color="error" @click="deleteCategory(props.item)"
                            >Supprimer</v-btn
                        >
                    </td>
                </template>
            </v-data-table>
        </v-container>

        <!-- Vuetify Dialog pour la boîte de dialogue de création -->
        <v-dialog v-model="dialog" max-width="500">
            <v-card>
                <v-card-title>Créer une nouvelle catégorie</v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="newCategory.title"
                        label="Titre"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="newCategory.description"
                        label="Description"
                        required
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" @click="createCategory">Créer</v-btn>
                    <v-btn color="error" @click="cancelCreate">Annuler</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            categories: [],
            headers: [
                { text: "Titre", value: "title" },
                { text: "Description", value: "description" },
                { text: "Actions", value: "actions" }, // Ajout de la colonne Actions
            ],
            dialog: false,
            newCategory: {
                title: "",
                description: "",
            },
        };
    },
    mounted() {
        this.fetchCategories();
    },
    methods: {
        // Récupérer la liste des catégories depuis Laravel
        async fetchCategories() {
            try {
                const response = await axios.get("/api/categories");
                this.categories = response.data;
                console.log(this.categories);
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },

        // Afficher une boîte de dialogue pour créer une nouvelle catégorie
        showCreateDialog() {
            // Réinitialiser les champs de la nouvelle catégorie
            this.newCategory.title = "";
            this.newCategory.description = "";
            // Afficher la boîte de dialogue
            this.dialog = true;
        },

        // Afficher une boîte de dialogue pour modifier une catégorie existante
        showEditDialog(category) {
            // Implémentez votre propre logique pour afficher une boîte de dialogue
        },
        // Créer une nouvelle catégorie
        async createCategory() {
            try {
                const response = await axios.post(
                    "/api/categories",
                    this.newCategory
                );
                if (response.status === 201) {
                    this.fetchCategories();
                    this.dialog = false;
                    console.log("Category created successfully");
                }
            } catch (error) {
                console.error("Error creating category:", error);
            }
        },

        // Annuler la création de la catégorie
        cancelCreate() {
            this.dialog = false;
            // Réinitialiser les champs de la nouvelle catégorie
            this.newCategory.title = "";
            this.newCategory.description = "";
        },

        // Supprimer une catégorie
        async deleteCategory(category) {
            try {
                const response = await axios.delete(
                    `/api/categories/${category.id}`
                );
                if (response.status === 200) {
                    this.fetchCategories();
                    console.log("Category deleted successfully");
                }
            } catch (error) {
                console.error("Error deleting category:", error);
            }
        },
    },
};
</script>
