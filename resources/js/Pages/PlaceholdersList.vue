<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center card-title">
                Placeholders List
                <v-spacer></v-spacer>
                <v-btn color="blue" class="ml-3" @click="showCreateDialog">
                    <font-awesome-icon
                        class="icon-side mr-2"
                        :icon="['fas', 'plus']"
                    />
                    Add New
                </v-btn>
                <v-text-field
                    v-model="search"
                    class="ml-3"
                    density="compact"
                    label="Search"
                    prepend-inner-icon="mdi-magnify"
                    variant="solo-filled"
                    flat
                    hide-details
                    single-line
                ></v-text-field>
            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="placeholders"
                :search="search"
            >
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.identification }}</td>

                        <td>
                            <div class="action-buttons">
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
                                    @click="deletePlaceholder(item)"
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
            <v-card-title>Add New Placeholder</v-card-title>
            <v-card-text>
                <v-text-field
                    v-model="newPlaceholder.name"
                    label="Name"
                    variant="outlined"
                    required
                    @input="updateIdentification"
                ></v-text-field>
                <v-text-field
                    v-model="newPlaceholder.identification"
                    label="Identification"
                    variant="outlined"
                    required
                    auto-grow
                    rows="5"
                    disabled
                ></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" variant="flat" @click="createPlaceholder">
                    Create
                </v-btn>
                <v-btn color="error" @click="cancelCreate">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="updateDialog" max-width="400px">
        <v-card>
            <v-card-title>Update Placeholder </v-card-title>
            <v-card-text>
                <p>Name</p>
                <v-text-field
                    v-model="newPlaceholder.name"
                    variant="outlined"
                    required
                    @input="updateIdentification"
                ></v-text-field>
                <p>Identification</p>
                <v-text-field
                    v-model="newPlaceholder.identification"
                    variant="outlined"
                    required
                    disabled
                ></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" variant="flat" @click="updatePlaceholder">
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
            search: "",
            placeholder: {},
            placeholders: [],
            createDialog: false,
            updateDialog: false,
            newPlaceholder: {
                name: "",
                identification: "",
            },
            headers: [
                {
                    title: "#",
                    align: "start",

                    key: "id",
                },
                { title: "Name", key: "name", sortable: false },
                {
                    title: "Identification",
                    key: "identification",
                    sortable: false,
                },
                {
                    title: "Actions",
                    key: "actions",
                    align: "center",
                    sortable: false,
                },
            ],
        };
    },
    watch: {
        "newPlaceholder.name"(newValue) {
            this.newPlaceholder.identification = `[${newValue.toUpperCase()}]`;
        },
    },
    mounted() {
        this.fetchPlaceholders();
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

        showCreateDialog() {
            this.newPlaceholder.name = "";
            this.newPlaceholder.identification = "";
            this.createDialog = true;
        },

        async createPlaceholder() {
            try {
                const response = await axios.post(
                    "/api/placeholders",
                    this.newPlaceholder
                );
                console.log(response);
                this.createDialog = false;
                toast.fire({
                    icon: "success",
                    title: "Placeholder created successfully ",
                });
                await this.fetchPlaceholders();
            } catch (error) {
                if (error.response && error.response.status === 400) {
                    if (error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error("Validation error:", error.response.data);
                    }
                } else {
                    console.error("Error creating placeholder:", error);
                    toast.fire({
                        icon: "error",
                        title: "Error creating placeholder ",
                    });
                }
            }
        },
        cancelCreate() {
            this.createDialog = false;
        },
        updateIdentification() {
            const formattedIdentification = this.newPlaceholder.name
                .replace(/([A-Z])/g, "_$1")
                .toUpperCase()
                .replace(/^_/, "");
            this.newPlaceholder.identification = `[${formattedIdentification}]`;
        },

        showUpdateDialog(placeholder) {
            this.placeholder = { ...placeholder };
            this.newPlaceholder.name = placeholder.name;
            this.updateIdentification();
            this.updateDialog = true;
        },

        async updatePlaceholder() {
            try {
                const response = await axios.put(
                    `/api/placeholders/${this.placeholder.id}`,
                    this.newPlaceholder
                );
                this.placeholders = this.placeholders.map((cat) =>
                    cat.id === this.placeholder.id
                        ? { ...cat, ...response.data }
                        : cat
                );
                this.updateIdentification();
                this.updateDialog = false;
                toast.fire({
                    icon: "success",
                    title: "Placeholder updated successfully",
                });
            } catch (error) {
                console.error("Error updating placeholder:", error);
                toast.fire({
                    icon: "error",
                    title: "Error updating placeholder",
                });
            }
        },

        cancelUpdate() {
            this.updateDialog = false;
        },

        deletePlaceholder(placeholder) {
            swal.fire({
                title: "Are you sure?",
                text: placeholder.name + " will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .delete("/api/placeholders/" + placeholder.id)
                        .then(() => {
                            toast.fire({
                                icon: "success",
                                title:
                                    placeholder.name +
                                    " has been deleted sucessfully",
                            });
                            this.fetchPlaceholders();
                        })
                        .catch(() => {
                            toast.fire({
                                icon: "error",
                                title:
                                    placeholder.name +
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
