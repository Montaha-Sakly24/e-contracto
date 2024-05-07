<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center card-title">
                <v-icon icon="mdi-account-group pr-3"></v-icon>
                Admins List

                <v-spacer></v-spacer>
                <v-btn color="blue" class="ml-3" @click="showCreateDialog">
                    <font-awesome-icon
                        class="icon-side mr-2"
                        :icon="['fas', 'plus']"
                    />
                    Add New
                </v-btn>
                <v-text-field
                    v-model="searchCompte"
                    class="ml-3"
                    density="compact"
                    label="Search by name, email"
                    prepend-inner-icon="mdi-magnify"
                    variant="solo-filled"
                    flat
                    hide-details
                    single-line
                ></v-text-field>
            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="users"
                :sort-by="[{ key: 'id', order: 'asc' }]"
                :search="searchCompte"
            >
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>
                            <span
                                :class="{
                                    'text-success': item.status === 1,
                                    'text-error': item.status !== 1,
                                }"
                            >
                                {{
                                    item.status === 1
                                        ? "Activated"
                                        : "Deactivated"
                                }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    color="blue"
                                    variant="outlined"
                                    @click="viewAdmin(item)"
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'eye']"
                                    />
                                    View
                                </v-btn>

                                <v-btn
                                    color="red"
                                    variant="outlined"
                                    @click="deleteAdmin(item)"
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'trash']"
                                    />
                                    Delete
                                </v-btn>

                                <v-btn
                                    variant="outlined"
                                    :color="
                                        item.status === 1 ? 'error' : 'success'
                                    "
                                    @click="changeAccountStatus(item)"
                                >
                                    <v-icon
                                        >{{
                                            item.status === 1
                                                ? "mdi-close-box"
                                                : "mdi-checkbox-marked"
                                        }}
                                    </v-icon>
                                    {{
                                        item.status === 1
                                            ? "Deactivate"
                                            : "Activate"
                                    }}
                                </v-btn>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>
    </v-container>
    <v-dialog v-model="createDialog" max-width="750px">
        <v-card>
            <v-card-title
                class="title text-uppercase font-weight-bold font-italic title-modal"
                >Add New Admin
            </v-card-title>
            <v-divider class="line-hr"></v-divider>
            <v-card-text>
                <v-row>
                    <v-col cols="6">
                        <v-text-field
                            v-model="newAdmin.name"
                            label="Name"
                            variant="outlined"
                            :rules="ruleTitle"
                        ></v-text-field>
                        <v-text-field
                            v-model="newAdmin.phone"
                            label="Phone"
                            variant="outlined"
                        ></v-text-field>
                        <v-text-field
                            v-model="newAdmin.password"
                            label="Password"
                            variant="outlined"
                            :type="isPasswordVisible ? 'text' : 'password'"
                            :append-inner-icon="
                                isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            @click:append-inner="
                                isPasswordVisible = !isPasswordVisible
                            "
                        ></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            v-model="newAdmin.email"
                            label="Email"
                            variant="outlined"
                        ></v-text-field>
                        <v-text-field
                            v-model="newAdmin.address"
                            label="Address"
                            variant="outlined"
                        ></v-text-field>
                        <v-text-field
                            v-model="newAdmin.c_password"
                            label="Confirm Password"
                            variant="outlined"
                            :type="
                                isConfirmPasswordVisible ? 'text' : 'password'
                            "
                            :append-inner-icon="
                                isConfirmPasswordVisible
                                    ? 'mdi-eye'
                                    : 'mdi-eye-off'
                            "
                            @click:append-inner="
                                isConfirmPasswordVisible =
                                    !isConfirmPasswordVisible
                            "
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-divider class="line-hr-footer"></v-divider>
            <v-card-actions class="actions-msg-btn-modal">
                <v-spacer></v-spacer>
                <v-btn variant="outlined" @click="cancelCreate">Cancel</v-btn>
                <v-btn
                    color="#1e0c8c"
                    variant="elevated"
                    @click="createAdminAccount"
                >
                    Create
                </v-btn>
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
                ><v-avatar size="65">
                    <img :src="'/storage/' + user.avatar" alt="User's Avatar" />
                </v-avatar>
                {{ user.name }}
                Profil
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
                <p><b>Name: </b> {{ user.name }}</p>
                <p><b>Email: </b>{{ user.email }}</p>
                <p><b>Phone: </b>{{ user.phone }}</p>
                <p><b>Address: </b>{{ user.address }}</p>
                <p>
                    <b>Status: </b
                    ><span
                        :style="{
                            color: user.status === 1 ? 'green' : 'red',
                        }"
                    >
                        {{ user.status === 1 ? "Activated" : "Deactivated" }}
                    </span>
                </p>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            searchCompte: "",
            user: {},
            users: [],
            toggle: false,
            viewDialog: false,
            createDialog: false,
            isPasswordVisible: false,
            isConfirmPasswordVisible: false,
            headers: [
                {
                    title: "#",
                    align: "start",
                    key: "id",
                },
                { title: "Name", key: "name", sortable: false },
                { title: "Email", key: "email", sortable: false },
                { title: "Status", key: "status", sortable: false },
                { title: "Actions", key: "actions", sortable: false },
            ],
            newAdmin: {
                name: "",
                email: "",
                phone: "",
                address: "",
                password: "",
                c_password: "",
            },
        };
    },
    computed: {
        ruleTitle() {
            return [(v) => !!v || "Title is required."];
        },
    },
    mounted() {
        this.fetchAdmins();
    },
    methods: {
        async fetchAdmins() {
            try {
                const response = await axios.get("/api/users/admin");
                this.users = response.data;
            } catch (error) {
                console.error("Error fetching admins:", error);
            }
        },
        showCreateDialog() {
            this.newAdmin.name = "";
            this.newAdmin.email = "";
            this.newAdmin.address = "";
            this.newAdmin.phone = "";
            this.newAdmin.password = "";
            this.newAdmin.c_password = "";
            this.createDialog = true;
        },
        async createAdminAccount() {
            try {
                const response = await axios.post("/api/users", this.newAdmin);
                console.log(response);
                this.createDialog = false;
                toast.fire({
                    icon: "success",
                    title: "Admin account created successfully ",
                });
                await this.fetchAdmins();
            } catch (error) {
                if (error.response && error.response.status === 400) {
                    if (error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error("Validation error:", error.response.data);
                    }
                } else {
                    console.error("Error creating admin account:", error);
                    toast.fire({
                        icon: "error",
                        title: "Error creating admin account ",
                    });
                }
            }
        },
        cancelCreate() {
            this.createDialog = false;
        },

        async viewAdmin(user) {
            try {
                const response = await axios.get("/api/users/" + user.id);
                this.user = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing admin:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing admin  ",
                });
            }
        },

        deleteAdmin(user) {
            swal.fire({
                title: "Are you sure?",
                text: user.name + " will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .delete("/api/users/" + user.id)
                        .then(() => {
                            toast.fire({
                                icon: "success",
                                title:
                                    user.name + " has been deleted sucessfully",
                            });
                            this.fetchAdmins();
                        })
                        .catch(() => {
                            toast.fire({
                                icon: "error",
                                title:
                                    user.name +
                                    " was unable to be remove, try again later",
                            });
                        });
                }
            });
        },
        changeAccountStatus(user) {
            try {
                axios
                    .put("/api/users/" + user.id)
                    .then((response) => {
                        this.user = response.data;
                        this.fetchAdmins();
                        toast.fire({
                            icon: "success",
                            title: user.name + " status updated successfully",
                        });
                    })
                    .catch((error) => {
                        console.error("Error updating admin status:", error);
                        toast.fire({
                            icon: "error",
                            title: "Failed to update admin status",
                        });
                    });
            } catch (error) {
                console.error("Error", error);
            }
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
}

.title,
b {
    font-family: "Times New Roman", Times, serif;
}
</style>
