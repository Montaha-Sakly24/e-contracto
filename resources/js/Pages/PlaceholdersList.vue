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
    <v-dialog v-model="createDialog" max-width="450px">
        <v-card>
            <v-card-title
                class="title text-uppercase font-weight-bold font-italic title-modal"
            >
                Add New Placeholder
            </v-card-title>
            <v-divider class="line-hr"></v-divider>

            <v-card-text>
                <v-text-field
                    v-model="newPlaceholder.name"
                    label="Name"
                    variant="outlined"
                    required
                    @input="updateIdentification"
                    :error-messages="errors['name']"
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
            <v-divider class="line-hr-footer"></v-divider>
            <v-card-actions class="actions-msg-btn-modal">
                <v-spacer></v-spacer>
                <v-btn variant="outlined" @click="cancelCreate">Cancel</v-btn>
                <v-btn
                    color="#1e0c8c"
                    variant="elevated"
                    @click="createPlaceholder"
                >
                    Create
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="updateDialog" max-width="450px">
        <v-card>
            <v-card-title
                class="title text-uppercase font-weight-bold font-italic title-modal"
            >
                Update Placeholder
            </v-card-title>
            <v-divider class="line-hr"></v-divider>

            <v-card-text>
                <p>Name</p>
                <v-text-field
                    v-model="newPlaceholder.name"
                    variant="outlined"
                    required
                    @input="updateIdentification"
                    :error-messages="errors['name']"
                ></v-text-field>
                <p>Identification</p>
                <v-text-field
                    v-model="newPlaceholder.identification"
                    variant="outlined"
                    required
                    disabled
                ></v-text-field>
            </v-card-text>
            <v-divider class="line-hr-footer"></v-divider>
            <v-card-actions class="actions-msg-btn-modal">
                <v-spacer></v-spacer>
                <v-btn variant="outlined" @click="cancelUpdate">Cancel</v-btn>
                <v-btn
                    color="#1e0c8c"
                    variant="elevated"
                    @click="updatePlaceholder"
                >
                    Save changes
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
            errors: {},
        };
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
            this.errors = {};
            this.createDialog = true;
        },

        async createPlaceholder() {
            try {
                this.newPlaceholder.identification = `[${this.newPlaceholder.name}]`;

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
                if (error.response && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    this.errors = {};

                    if (errors.name) {
                        this.errors.name = errors.name[0];
                    }
                } else {
                    console.error("An unexpected error occurred:", error);
                }
            }
        },
        cancelCreate() {
            this.createDialog = false;
        },
        updateIdentification() {
            this.newPlaceholder.identification = `[${this.newPlaceholder.name}]`;
        },

        showUpdateDialog(placeholder) {
            this.placeholder = { ...placeholder };
            this.newPlaceholder.name = placeholder.name;
            this.errors = {};
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
                if (error.response && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    this.errors = {};

                    if (errors.name) {
                        this.errors.name = errors.name[0];
                    }
                } else {
                    console.error("An unexpected error occurred:", error);
                }
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
