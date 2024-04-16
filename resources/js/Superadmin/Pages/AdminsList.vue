<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center pe-2">
                <v-icon icon="mdi-account-group"></v-icon>
                Admins

                <v-spacer></v-spacer>
                <v-btn
                    prepend-icon="mdi-plus-circle"
                    color="info"
                    class="ml-3"
                    @click="showCreateDialog"
                >
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
                <v-btn
                    type="submit"
                    class="ml-1"
                    variant="text"
                    @click="searchAdmin"
                >
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
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
                                {{ item.status === 1 ? "Active" : "Deactive" }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    prepend-icon="mdi-eye"
                                    color="info"
                                    @click="viewAdmin(item)"
                                >
                                    View
                                </v-btn>

                                <v-btn
                                    prepend-icon="mdi-delete"
                                    color="red"
                                    @click="deleteAdmin(item)"
                                >
                                    Delete
                                </v-btn>

                                <v-btn
                                    :color="
                                        item.status === 1 ? 'error' : 'success'
                                    "
                                    @click="
                                        activateDeactivateAdminAccount(item)
                                    "
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
    <v-dialog v-model="createDialog" max-width="700px">
        <v-card>
            <v-card-title>Add New Admin </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="6">
                        <v-text-field
                            v-model="newAdmin.name"
                            label="Name"
                            variant="outlined"
                            required
                            :error-messages="
                                errors && errors.name ? errors.name : []
                            "
                        ></v-text-field>
                        <v-text-field
                            v-model="newAdmin.phone"
                            label="Phone"
                            variant="outlined"
                            required
                            :error-messages="
                                errors && errors.phone ? errors.phone : []
                            "
                        ></v-text-field>
                        <v-text-field
                            v-model="newAdmin.password"
                            label="Password"
                            variant="outlined"
                            required
                            :type="isPasswordVisible ? 'text' : 'password'"
                            :append-inner-icon="
                                isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            @click:append-inner="
                                isPasswordVisible = !isPasswordVisible
                            "
                            :error-messages="
                                errors && errors.password ? errors.password : []
                            "
                        ></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            v-model="newAdmin.email"
                            label="Email"
                            variant="outlined"
                            required
                            :error-messages="
                                errors && errors.email ? errors.email : []
                            "
                        ></v-text-field>
                        <v-text-field
                            v-model="newAdmin.address"
                            label="Address"
                            variant="outlined"
                            required
                            :error-messages="
                                errors && errors.address ? errors.address : []
                            "
                        ></v-text-field>
                        <v-text-field
                            v-model="newAdmin.c_password"
                            label="Confirm Password"
                            variant="outlined"
                            required
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
                            :error-messages="
                                errors && errors.c_password
                                    ? errors.c_password
                                    : []
                            "
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" variant="flat" @click="createAdminAccount"
                    >Create</v-btn
                >
                <v-btn color="error" @click="cancelCreate">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog
        v-model="viewDialog"
        transition="dialog-bottom-transition"
        max-width="570"
    >
        <v-card class="dialog-card" variant="flat">
            <v-card-title
                class="custom-border title text-uppercase font-weight-bold font-italic"
                ><v-avatar size="65">
                    <img :src="'/storage/' + user.avatar" alt="User's Avatar" />
                </v-avatar>
                {{ user.name }}
                Profil
            </v-card-title>
            <v-icon
                @click="viewDialog = false"
                style="position: absolute; top: 5px; right: 5px"
                >mdi-close
            </v-icon>
            <v-card-text class="custom-border">
                <p><b>Name: </b> {{ user.name }}</p>
                <p><b>Email: </b>{{ user.email }}</p>
                <p><b>Phone: </b>{{ user.phone }}</p>
                <p><b>Address: </b>{{ user.address }}</p>
                <p>
                    <b>Status: </b
                    ><span
                        :style="{
                            color: user.status === 1 ? 'green' : 'error',
                        }"
                    >
                        {{ user.status === 1 ? "Active" : "Deactive" }}
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

        async searchAdmin() {
            if (!this.searchCompte.trim()) {
                toast.fire({
                    icon: "info",
                    title: "Please enter a search term",
                });
                await this.fetchAdmins();
                return;
            }
            try {
                const response = await axios.get(
                    `/api/users/search?search=${encodeURIComponent(
                        this.searchCompte
                    )}`
                );

                if (response.data.users) {
                    this.users = response.data.users;
                } else {
                    this.users = response.data;
                }
                if (this.users.length > 0) {
                    toast.fire({
                        icon: "success",
                        title: "Admins found successfully",
                    });
                } else {
                    toast.fire({
                        icon: "info",
                        title: "No admin found",
                    });
                }
            } catch (error) {
                console.error("Error searching admins:", error);
                toast.fire({
                    icon: "error",
                    title: "Error while searching admins",
                });
            }
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
        activateDeactivateAdminAccount(user) {
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
.action-buttons {
    display: flex;
    gap: 8px;
}
.dialog-card {
    padding: 25px;
}

.title,
b {
    font-family: "Times New Roman", Times, serif;
}
.custom-border {
    border: 1px solid #347eba; /* Light grey border */
    padding: 8px;
    margin-bottom: 10px;
}
</style>
