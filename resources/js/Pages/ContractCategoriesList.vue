<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center card-title">
                Categories List
                <v-spacer></v-spacer>
                <v-btn color="blue" class="ml-3" @click="showCreateDialog">
                    <font-awesome-icon
                        class="icon-side mr-2"
                        :icon="['fas', 'plus']"
                    />
                    Add New
                </v-btn>
            </v-card-title>

            <v-data-table :headers="headers" :items="categories">
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>

                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    color="blue"
                                    variant="outlined"
                                    @click="viewCategory(item)"
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'eye']"
                                    />
                                    View
                                </v-btn>

                                <v-btn
                                    color="darkblue"
                                    variant="outlined"
                                    @click="showUpdateDialog(item)"
                                >
                                    <font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'pen-alt']"
                                    />
                                    Update
                                </v-btn>

                                <v-btn
                                    color="red"
                                    variant="outlined"
                                    @click="deleteCategory(item)"
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'trash']"
                                    />
                                    Delete
                                </v-btn>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>
    </v-container>
    <v-dialog v-model="createDialog" max-width="400px">
        <v-card>
            <v-card-title>Add New Category </v-card-title>
            <v-card-text>
                <v-text-field
                    v-model="newCategory.title"
                    label="Title"
                    variant="outlined"
                    required
                ></v-text-field>
                <v-textarea
                    v-model="newCategory.description"
                    label="Description"
                    variant="outlined"
                    required
                    auto-grow
                    rows="5"
                ></v-textarea>
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" variant="flat" @click="createCategory">
                    Create
                </v-btn>
                <v-btn color="error" @click="cancelCreate">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog
        v-model="viewDialog"
        persistent
        :scrollable="false"
        :hide-overlay="true"
        no-click-animation
        width="600"
        content-class="custom-vuetify-dialog-add"
    >
        <v-card>
            <v-card-title
                class="title text-uppercase font-weight-bold font-italic title-modal"
            >
                {{ category.title }}

                <v-btn
                    class="btn-close-header"
                    icon
                    @click="viewDialog = false"
                    title="Close"
                    color="blue"
                >
                    <font-awesome-icon :icon="['fas', 'xmark']" />
                </v-btn>
            </v-card-title>
            <v-divider class="line-hr"></v-divider>
            <v-card-text>
                <p><b>Description: </b></p>
                <div>{{ category.description }}</div>
                <br />
                <p><b>List of Models: </b></p>
                <v-list dense>
                    <v-list-item
                        v-for="model in category.models"
                        :key="model.id"
                    >
                        <v-list-item-content>
                            <li>{{ model.title }}</li>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-card-text>
        </v-card>
    </v-dialog>

    <v-dialog v-model="updateDialog" max-width="400px">
        <v-card>
            <v-card-title>{{ category.title }}</v-card-title>
            <v-card-text>
                <p>Title</p>
                <v-text-field
                    v-model="newCategory.title"
                    variant="outlined"
                    required
                ></v-text-field>
                <p>Description</p>
                <v-textarea
                    v-model="newCategory.description"
                    variant="outlined"
                    required
                ></v-textarea>
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" variant="flat" @click="updateCategory">
                    Save changes
                </v-btn>
                <v-btn color="error" @click="cancelUpdate">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            category: {},
            categories: [],
            models: [],
            model: {},
            createDialog: false,
            viewDialog: false,
            updateDialog: false,
            newCategory: {
                title: "",
                description: "",
            },
            headers: [
                {
                    title: "#",
                    align: "start",

                    key: "id",
                },
                { title: "Title", key: "title", sortable: false },

                {
                    title: "Actions",
                    key: "actions",
                    align: "center",
                    sortable: false,
                },
            ],
        };
    },
    mounted() {
        this.fetchCategories();
    },
    watch: {
        viewDialog(val) {
            if (val) {
                this.fetchModelsByCategory(this.category.id);
            }
        },
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

        showCreateDialog() {
            this.newCategory.title = "";
            this.newCategory.description = "";
            this.createDialog = true;
        },

        async createCategory() {
            try {
                const response = await axios.post(
                    "/api/categories",
                    this.newCategory
                );
                console.log(response);
                this.createDialog = false;
                toast.fire({
                    icon: "success",
                    title: "Category created successfully ",
                });
                await this.fetchCategories();
            } catch (error) {
                if (error.response && error.response.status === 400) {
                    if (error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error("Validation error:", error.response.data);
                    }
                } else {
                    console.error("Error creating category:", error);
                    toast.fire({
                        icon: "error",
                        title: "Error creating category ",
                    });
                }
            }
        },
        cancelCreate() {
            this.createDialog = false;
        },

        async viewCategory(category) {
            try {
                const response = await axios.get(
                    "/api/categories/" + category.id
                );
                this.category = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing category:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing category ",
                });
            }
        },

        async fetchModelsByCategory(categoryId) {
            try {
                const response = await axios.get(
                    "/api/categories/" + categoryId
                );
                this.models = response.data;
            } catch (error) {
                console.error("Error fetching models by category:", error);
                toast.fire({
                    icon: "error",
                    title: "Error fetching models by category",
                });
            }
        },

        showUpdateDialog(category) {
            this.category = { ...category };
            this.newCategory.title = category.title;
            this.newCategory.description = category.description;
            this.updateDialog = true;
        },
        async updateCategory(category) {
            try {
                const response = await axios.put(
                    `/api/categories/${this.category.id}`,
                    this.newCategory
                );
                this.categories = this.categories.map((cat) =>
                    cat.id === this.category.id
                        ? { ...cat, ...response.data }
                        : cat
                );
                this.updateDialog = false;
                toast.fire({
                    icon: "success",
                    title: "Category updated successfully ",
                });
            } catch (error) {
                console.error("Error updating category:", error);
                toast.fire({
                    icon: "error",
                    title: "Error updating category ",
                });
            }
        },
        cancelUpdate() {
            this.updateDialog = false;
        },

        deleteCategory(category) {
            swal.fire({
                title: "Are you sure?",
                text: category.title + " will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .delete("/api/categories/" + category.id)
                        .then(() => {
                            toast.fire({
                                icon: "success",
                                title:
                                    category.title +
                                    " has been deleted sucessfully",
                            });
                            this.fetchCategories();
                        })
                        .catch(() => {
                            toast.fire({
                                icon: "error",
                                title:
                                    category.title +
                                    " was unable to be remove, try again later",
                            });
                        });
                }
            });
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

.card-title {
    font-weight: bold;
    text-transform: uppercase;
    color: #1a1ab3;
    font-family: serif;
}
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.dialog-card {
    padding: 20px;
}

.title,
b {
    font-family: "Times New Roman", Times, serif;
}
</style>
