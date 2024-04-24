<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center pe-2">
                <v-icon icon="mdi-folder"></v-icon>
                Contracts List
            </v-card-title>
            <v-data-table :headers="headers" :items="contracts">
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>
                        <td>{{ item.status }}</td>

                        <td>
                            <div class="action-buttons">
                                <v-btn color="info" @click="viewContract(item)">
                                    <v-icon>mdi-eye</v-icon>
                                </v-btn>

                                <v-btn
                                    color="warning"
                                    @click="showUpdateDialog(item)"
                                >
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>

                                <v-btn
                                    color="red"
                                    @click="deleteContract(item)"
                                >
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>

                                <v-btn
                                    color="darkblue"
                                    @click="toggleSignDialog(item)"
                                    :disabled="item.isSigned"
                                >
                                    <v-icon>mdi-edit-document</v-icon>
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
            <v-btn color="neutralgray" @click="exportContract(contract)">
                <v-icon>mdi-download</v-icon> Export
            </v-btn>

            <v-card class="dialog-card" variant="flat">
                <v-card-title
                    class="title text-uppercase font-weight-bold font-italic"
                >
                    {{ contract.title }}
                    <v-icon
                        @click="viewDialog = false"
                        style="position: absolute; top: 10px; right: 10px"
                        >mdi-close
                    </v-icon>
                </v-card-title>
                <v-card-text>
                    <p>{{ contract.content }}</p>
                    <img :src="contract.signature" alt="Signature" />
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="updateDialog" max-width="400px">
            <v-card>
                <v-card-title>{{ contract.title }}</v-card-title>
                <v-card-text>
                    <p>Title</p>
                    <v-text-field
                        v-model="newContract.title"
                        variant="outlined"
                        required
                    ></v-text-field>
                    <p>Content</p>
                    <v-textarea
                        v-model="newContract.content"
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

        <v-dialog v-model="showSignDialog" transition="dialog-bottom-transition"
            ><v-card
                >{{ contract.content }}
                <signature-contract
                    @signatureSaved="onSignatureSaved"
                ></signature-contract>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import axios from "axios";
import { useRouter } from "vue-router";
import SignatureContract from "../Components/SignatureContract.vue";

export default {
    components: {
        SignatureContract,
    },
    data() {
        return {
            router: useRouter(),
            contract: {},
            contracts: [],
            viewDialog: false,
            updateDialog: false,
            showSignDialog: false,
            newContract: {
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
                { title: "Status", key: "status", sortable: false },
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
                const response = await axios.get("/api/contracts");
                this.contracts = response.data;
            } catch (error) {
                console.error("Error fetching contracts:", error);
            }
        },
        async viewContract(contract) {
            try {
                const response = await axios.get(
                    "/api/contracts/" + contract.id
                );
                this.contract = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing contract:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing contract ",
                });
            }
        },

        showUpdateDialog(contract) {
            this.contract = { ...contract };
            this.newContract.title = contract.title;
            this.newContract.content = contract.content;
            this.updateDialog = true;
        },
        async updateContract(contract) {
            try {
                const response = await axios.put(
                    `/api/contracts/${this.contract.id}`,
                    this.newContract
                );
                this.contracts = this.contracts.map((cat) =>
                    cat.id === this.contract.id
                        ? { ...cat, ...response.data }
                        : cat
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

        deleteContract(contract) {
            swal.fire({
                title: "Are you sure?",
                text: contract.title + " will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .delete("/api/contracts/" + contract.id)
                        .then(() => {
                            toast.fire({
                                icon: "success",
                                title:
                                    contract.title +
                                    " has been deleted sucessfully",
                            });
                            this.fetchContracts();
                        })
                        .catch(() => {
                            toast.fire({
                                icon: "error",
                                title:
                                    contract.title +
                                    " was unable to be remove, try again later",
                            });
                        });
                }
            });
        },
        exportContract(contract) {
            const url = `/api/contracts/${contract.id}/export`;
            window
                .open(url, "_blank")
                .addEventListener(
                    "load",
                    () => {
                        // L'API a répondu avec succès
                    },
                    false
                )
                .addEventListener(
                    "error",
                    () => {
                        // Une erreur s'est produite lors de l'exportation
                        alert(
                            "Failed to export contract. Please try again later."
                        );
                    },
                    false
                );
        },
        toggleSignDialog(contract) {
            this.contract = contract;
            this.showSignDialog = !this.showSignDialog;
        },
        onSignatureSaved(signatureData) {
            this.signContract(this.contract, signatureData);
        },
        async signContract(contract, signatureData) {
            try {
                const response = await axios.post(
                    `/api/contracts/${contract.id}/sign`,
                    { signature_data: signatureData }
                );
                console.log(response.data.message);
                contract.status = "signed";
                contract.isSigned = true;
            } catch (error) {
                console.error("Error signing contract:", error);
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
