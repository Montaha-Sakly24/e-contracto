<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center card-title">
                <v-icon icon="mdi-account-group pr-3"></v-icon>
                Contract Seekers List

                <v-spacer></v-spacer>
            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="users"
                :sort-by="[{ key: 'id', order: 'asc' }]"
            >
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ item.phone }}</td>
                        <td>{{ item.address }}</td>

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
                    </tr>
                </template>
            </v-data-table>
        </v-card>
    </v-container>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            user: {},
            users: [],
            toggle: false,

            headers: [
                {
                    title: "#",
                    align: "start",
                    key: "id",
                    sortable: false,
                },
                { title: "Name", key: "name", sortable: false },
                { title: "Email", key: "email", sortable: false },
                { title: "Phone", key: "phone", sortable: false },
                { title: "Address", key: "address", sortable: false },
                { title: "Status", key: "status", sortable: false },
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
