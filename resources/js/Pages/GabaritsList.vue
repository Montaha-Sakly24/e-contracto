<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center pe-2">
                <v-icon icon="mdi-folder"></v-icon>
                Gabarits List
                <v-spacer></v-spacer>
                <create-gabarit></create-gabarit>
            </v-card-title>
            <v-data-table :headers="headers" :items="gabarits">
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>

                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    prepend-icon="mdi-eye"
                                    color="info"
                                    @click="viewGabarit(item)"
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
                                    @click="deleteGabarit(item)"
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
                {{ gabarit.title }}
                <v-icon
                    @click="viewDialog = false"
                    style="position: absolute; top: 10px; right: 10px"
                    >mdi-close
                </v-icon>
            </v-card-title>
            <v-card-text>
                <p><b>Content: </b><pre> {{ gabarit.content }}</pre></p>
            </v-card-text>
        </v-card>
    </v-dialog>
    <v-dialog v-model="updateDialog" max-width="400px">
        <v-card>
            <v-card-title>{{ gabarit.title }}</v-card-title>
            <v-card-text>
                <p>Title</p>
                <v-text-field
                    v-model="newGabarit.title"
                    variant="outlined"
                    required
                ></v-text-field>
                <p>Content</p>
                <v-textarea
                    v-model="newGabarit.content"
                    variant="outlined"
                    required
                ></v-textarea>
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" variant="flat" @click="updateGabarit">
                    Save changes
                </v-btn>
                <v-btn color="error" @click="cancelUpdate">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    </v-container>
</template>
<script>
import CreateGabarit from "../Components/CreateGabarit.vue";
import axios from "axios";

export default {
    components: {
        CreateGabarit,
    },

data() {
        return {
            gabarit: {},
            gabarits: [],
            viewDialog: false,
            updateDialog: false,
            newGabarit: {
                title: "",
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
        this.fetchGabarits();
    },
    methods: {
        async fetchGabarits() {
            try {
                const response = await axios.get("/api/gabarits");
                this.gabarits = response.data;
            } catch (error) {
                console.error("Error fetching gabarits:", error);
            }
        },

        async viewGabarit(gabarit) {
            try {
                const response = await axios.get(
                    "/api/gabarits/" + gabarit.id
                );
                this.gabarit = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing gabarit:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing gabarit ",
                });
            }
        },

        showUpdateDialog(gabarit) {
            this.gabarit = { ...gabarit };
            this.newGabarit.title = gabarit.title;
            this.newGabarit.content = gabarit.content;
            this.updateDialog = true;
        },
        async updateGabarit(gabarit) {
            try {
                const response = await axios.put(
                    `/api/gabarits/${this.gabarit.id}`,
                    this.newGabarit
                );
                this.gabarits = this.gabarits.map(cat =>
                    cat.id === this.gabarit.id ? { ...cat, ...response.data } : cat
                );
                this.updateDialog = false;
                toast.fire({
                    icon: "success",
                    title: "Gabarit updated successfully ",
                });
            } catch (error) {
                console.error("Error updating gabarit:", error);
                toast.fire({
                    icon: "error",
                    title: "Error updating gabarit ",
                });
            }
        },
        cancelUpdate() {
            this.updateDialog = false;
        },

        deleteGabarit(gabarit) {
            swal.fire({
                title: "Are you sure?",
                text: gabarit.title + " will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .delete("/api/gabarits/" + gabarit.id)
                        .then(() => {
                            toast.fire({
                                icon: "success",
                                title:
                                    gabarit.title +
                                    " has been deleted sucessfully",
                            });
                            this.fetchGabarits();
                        })
                        .catch(() => {
                            toast.fire({
                                icon: "error",
                                title:
                                    gabarit.title +
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
