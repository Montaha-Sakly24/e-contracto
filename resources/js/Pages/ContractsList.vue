<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center pe-2">
                <v-icon icon="mdi-folder"></v-icon>
                Contracts List

            </v-card-title>
            <v-data-table :headers="headers" :items="models">
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>

                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    prepend-icon="mdi-eye"
                                    color="info"
                                    @click="viewContract(item)"
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
                                    @click="deleteContract(item)"
                                >
                                    Delete
                                </v-btn>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog
        v-model="viewDialog"
        transition="dialog-bottom-transition"
        max-width="793"
    >
        <v-card class="dialog-card" variant="flat">
            <v-card-title
                class="title text-uppercase font-weight-bold font-italic"
            >
                {{ model.title }}
                <v-icon
                    @click="viewDialog = false"
                    style="position: absolute; top: 10px; right: 10px"
                    >mdi-close
                </v-icon>
            </v-card-title>
            <v-card-text>
                <p><b>Description: </b><pre> {{ model.description }}</pre></p>
                <p><b>Content: </b><pre> {{ model.content }}</pre></p>
            </v-card-text>
        </v-card>
    </v-dialog>
    <v-dialog v-model="updateDialog" max-width="400px">
        <v-card>
            <v-card-title>{{ model.title }}</v-card-title>
            <v-card-text>
                <p>Title</p>
                <v-text-field
                    v-model="newModel.title"
                    variant="outlined"
                    required
                ></v-text-field>
                <p>Content</p>
                <v-textarea
                    v-model="newModel.description"
                    variant="outlined"
                    required
                ></v-textarea>
                <v-textarea
                    v-model="newModel.content"
                    variant="outlined"
                    required
                ></v-textarea>
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" variant="flat" @click="updateContract">
                    Save changes
                </v-btn>
                <v-btn color="error" @click="cancelUpdate">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    </v-container>
</template>

<script>
import axios from "axios";
import { useRouter } from "vue-router";


export default {
    components: {

    },
data() {
        return {
            router: useRouter(),
            model: {},
            models: [],
            viewDialog: false,
            updateDialog: false,
            newModel: {
                title: "",
                description: "",
                content: "",
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
        this.fetchContracts();
    },
    methods: {
        async fetchContracts() {
            try {
                const response = await axios.get("/api/models");
                this.models = response.data;
            } catch (error) {
                console.error("Error fetching contracts:", error);
            }
        },
        async viewContract(model) {
            try {
                const response = await axios.get(
                    "/api/models/" + model.id
                );
                this.model = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing contract:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing contract ",
                });
            }
        },

        showUpdateDialog(model) {
            this.model = { ...model };
            this.newModel.title = model.title;
            this.newModel.description = model.description;
            this.newModel.content = model.content;
            this.updateDialog = true;
        },
        async updateContract(model) {
            try {
                const response = await axios.put(
                    `/api/models/${this.model.id}`,
                    this.newModel
                );
                this.models = this.models.map(cat =>
                    cat.id === this.model.id ? { ...cat, ...response.data } : cat
                );
                this.updateDialog = false;
                toast.fire({
                    icon: "success",
                    title: "Contract updated successfully ",
                });
            } catch (error) {
                console.error("Error updating contract:", error);
                toast.fire({
                    icon: "error",
                    title: "Error updating contract ",
                });
            }
        },
        cancelUpdate() {
            this.updateDialog = false;
        },

        deleteContract(model) {
            swal.fire({
                title: "Are you sure?",
                text: model.title + " will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .delete("/api/models/" + model.id)
                        .then(() => {
                            toast.fire({
                                icon: "success",
                                title:
                                    model.title +
                                    " has been deleted sucessfully",
                            });
                            this.fetchModels();
                        })
                        .catch(() => {
                            toast.fire({
                                icon: "error",
                                title:
                                    model.title +
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

