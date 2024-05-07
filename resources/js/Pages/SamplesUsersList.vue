<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center card-title">
                <v-icon icon="mdi-account-group pr-3"></v-icon>
                Contract Seekers List

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
                                    @click="viewUser(item)"
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'eye']"
                                    />
                                    View
                                </v-btn>

                                <v-btn
                                    color="red"
                                    variant="outlined"
                                    @click="deleteUser(item)"
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
            headers: [
                {
                    title: "#",
                    align: "start",
                    key: "id",
                },
                { title: "Name", key: "name", sortable: false },
                { title: "Email", key: "email", sortable: false },
                { title: "Status", key: "status", sortable: false },
                {
                    title: "Actions",
                    key: "actions",
                    sortable: false,
                },
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
        changeAccountStatus(user) {
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
