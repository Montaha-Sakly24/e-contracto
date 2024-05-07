<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center card-title">
                Models List
                <v-spacer></v-spacer>
                <v-btn color="blue" class="ml-3" @click="showCreateInterface">
                    <font-awesome-icon
                        class="icon-side mr-2"
                        :icon="['fas', 'plus']"
                    />
                    Add New
                </v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="models">
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>

                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    color="blue"
                                    variant="outlined"
                                    @click="viewModel(item)"
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
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'pen-alt']"
                                    />
                                    Update
                                </v-btn>

                                <v-btn
                                    color="red"
                                    variant="outlined"
                                    @click="deleteModel(item)"
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
        <v-dialog v-model="viewDialog" transition="dialog-bottom-transition">
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
                    <b>Description: </b>{{ model.description }} <br /><br />
                    <b>Category:</b> {{ model.category.title }}
                </v-card-text>
                <v-card-text class="a4-size">
                    <div v-html="model.content"></div>
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
                    <v-btn color="info" variant="flat" @click="updateModel">
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
import CreateContractModel from "../Components/CreateContractModel.vue";

export default {
    components: {
        CreateContractModel,
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
        this.fetchModels();
    },
    methods: {
        async fetchModels() {
            try {
                const response = await axios.get("/api/models");
                this.models = response.data;
            } catch (error) {
                console.error("Error fetching models:", error);
            }
        },

        showCreateInterface() {
            this.router.push({ name: "Create Contract Model" });
        },

        async viewModel(model) {
            try {
                const response = await axios.get("/api/models/" + model.id);
                this.model = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing model:", error);
            }
        },

        showUpdateDialog(model) {
            this.model = { ...model };
            this.newModel.title = model.title;
            this.newModel.description = model.description;
            this.newModel.content = model.content;
            this.updateDialog = true;
        },
        async updateModel(model) {
            try {
                const response = await axios.put(
                    `/api/models/${this.model.id}`,
                    this.newModel
                );
                this.models = this.models.map((cat) =>
                    cat.id === this.model.id
                        ? { ...cat, ...response.data }
                        : cat
                );
                this.updateDialog = false;
                toast.fire({
                    icon: "success",
                    title: "Model updated successfully ",
                });
            } catch (error) {
                console.error("Error updating model:", error);
                toast.fire({
                    icon: "error",
                    title: "Error updating model ",
                });
            }
        },
        cancelUpdate() {
            this.updateDialog = false;
        },

        deleteModel(model) {
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
.a4-size {
    align-self: center;
    width: 210mm;
    height: 297mm;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    overflow-y: auto;
}
.title,
b {
    font-family: "Times New Roman", Times, serif;
}
</style>
