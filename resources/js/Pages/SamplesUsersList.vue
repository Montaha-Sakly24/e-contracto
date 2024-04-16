<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center pe-2">
                <v-icon icon="mdi-account-group"></v-icon>
                Samples Users List

                <v-spacer></v-spacer>

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
                    @click="searchUser"
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
                                    @click="viewUser(item)"
                                >
                                    View
                                </v-btn>

                                <v-btn
                                    prepend-icon="mdi-delete"
                                    color="red"
                                    @click="deleteUser(item)"
                                >
                                    Delete
                                </v-btn>

                                <v-btn
                                    :color="
                                        item.status === 1 ? 'error' : 'success'
                                    "
                                    @click="activateDeactivateUserAccount(item)"
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
        };
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        async fetchUsers() {
            try {
                const response = await axios.get("/api/users");
                this.users = response.data;
            } catch (error) {
                console.error("Error fetching users:", error);
            }
        },

        async searchUser() {
            if (!this.searchCompte.trim()) {
                toast.fire({
                    icon: "info",
                    title: "Please enter a search term",
                });
                await this.fetchUsers();
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
                        title: "Sample user found successfully",
                    });
                } else {
                    toast.fire({
                        icon: "info",
                        title: "No sample user found",
                    });
                }
            } catch (error) {
                console.error("Error searching samples users:", error);
                toast.fire({
                    icon: "error",
                    title: "Error while searching samples users",
                });
            }
        },

        async viewUser(user) {
            try {
                const response = await axios.get("/api/users/" + user.id);
                this.user = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing user:", error);
            }
        },

        deleteUser(user) {
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
                            this.fetchUsers();
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
        activateDeactivateUserAccount(user) {
            try {
                axios
                    .put("/api/users/" + user.id)
                    .then((response) => {
                        this.user = response.data;
                        this.fetchUsers();
                        toast.fire({
                            icon: "success",
                            title: user.name + " status updated successfully",
                        });
                    })
                    .catch((error) => {
                        console.error("Error updating user status:", error);
                        toast.fire({
                            icon: "error",
                            title: "Failed to update " + user.name + " status",
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
    border: 1px solid #032846;
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
