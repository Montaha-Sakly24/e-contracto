<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center pe-2">
                <v-icon icon="mdi-folder"></v-icon>
                Categories List
                <v-spacer></v-spacer>
                <v-btn
                    prepend-icon="mdi-plus-circle"
                    color="info"
                    class="ml-3"
                    @click="showCreateDialog"
                >
                    Add New
                </v-btn>
            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="categories"
            >
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>

                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    prepend-icon="mdi-eye"
                                    color="info"
                                    @click="viewCategory(item)"
                                >
                                    View
                                </v-btn>

                                <v-btn
                                    prepend-icon="mdi-pencil"
                                    color="warning"
                                    @click="showUpdateDialog(item)"
                                >
                                    Update
                                </v-btn>

                                <v-btn
                                    prepend-icon="mdi-delete"
                                    color="red"
                                    @click="deleteCategory(item)"
                                >
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
        transition="dialog-bottom-transition"
        max-width="793"
    >
        <v-card class="dialog-card" variant="flat">
            <v-card-title
                class="title text-uppercase font-weight-bold font-italic"
            >
                {{ category.title }}
                <v-icon
                    @click="viewDialog = false"
                    style="position: absolute; top: 10px; right: 10px"
                    >mdi-close
                </v-icon>
            </v-card-title>
            <v-card-text>
                <p><b>Description: </b><pre> {{ category.description }}</pre></p>
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
                this.categories = this.categories.map(cat =>
                    cat.id === this.category.id ? { ...cat, ...response.data } : cat
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
