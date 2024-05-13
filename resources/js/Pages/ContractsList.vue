<template>
    <v-container class="page-container" fluid>
        <v-card>
            <v-card-title class="d-flex align-center card-title">
                Contracts List
            </v-card-title>
            <v-data-table :headers="headers" :items="contracts">
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>

                        <td>
                            <span
                                :class="{
                                    'text-orange': item.status === 'unsigned',
                                    'text-success': item.status !== 'unsigned',
                                }"
                            >
                                {{ item.status }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    color="blue"
                                    variant="outlined"
                                    @click="viewContract(item)"
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'eye']"
                                    />
                                    View
                                </v-btn>

                                <v-btn
                                    color="red"
                                    variant="outlined"
                                    @click="deleteContract(item)"
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'trash']"
                                    />
                                    Delete
                                </v-btn>

                                <v-btn
                                    variant="outlined"
                                    @click="toggleSignDialog(item)"
                                    :disabled="item.status === 'signed'"
                                >
                                    Sign
                                </v-btn>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog
            v-model="viewDialog"
            persistent
            :scrollable="false"
            :hide-overlay="true"
            no-click-animation
            width="800"
            content-class="custom-vuetify-dialog-add"
        >
            <v-btn
                icon
                @click="viewDialog = false"
                title="Close"
                color="muted"
                class="dialog-close-btn"
            >
                <font-awesome-icon :icon="['fas', 'xmark']" />
            </v-btn>
            <v-btn
                color="darkblueshade"
                @click="exportContract(contract)"
                class="export-btn"
            >
                <v-icon>mdi-download</v-icon> Export
            </v-btn>

            <v-card class="dialog-card">
                <v-card-text>
                    <div v-html="contract.content"></div>

                    <img
                        v-if="contract.signature"
                        :src="contract.signature"
                        alt="Signature"
                        class="signature-image"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="showSignDialog"
            persistent
            :scrollable="false"
            :hide-overlay="true"
            no-click-animation
            width="800"
            content-class="custom-vuetify-dialog-add"
        >
            <v-btn
                icon
                @click="showSignDialog = false"
                title="Close"
                color="muted"
                class="dialog-close-btn"
            >
                <font-awesome-icon :icon="['fas', 'xmark']" />
            </v-btn>

            <v-card class="dialog-card">
                <v-card-text>
                    <div v-html="contract.content"></div>
                </v-card-text>
                <div class="signature-section">
                    <signature-contract
                        @signatureSaved="onSignatureSaved"
                    ></signature-contract>
                </div>
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
            showSignDialog: false,
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
                const response = await axios.get("/api/contracts/user", {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
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
                .open(url, "_parent")

                .addEventListener(
                    "error",
                    () => {
                        alert(
                            "Failed to export contract. Please try again later."
                        );
                    },
                    false
                );
        },
        toggleSignDialog(contract) {
            this.contract = contract;
            this.showSignDialog = true;
            alert("The contract can only be signed once.");
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
                toast.fire({
                    icon: "success",
                    title: contract.title + " has been signed sucessfully",
                });
                this.showSignDialog = false;
                this.fetchContracts();
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

.title,
b {
    font-family: "Times New Roman", Times, serif;
}
.dialog-container {
    position: relative;
}

.dialog-close-btn,
.export-btn {
    position: absolute;
    top: 10px;
    z-index: 10;
}

.dialog-close-btn {
    right: 10px;
    border-radius: 8px;
    height: 37px !important;
    width: 40px !important;
}

.export-btn {
    right: 60px;
}

.dialog-card {
    padding: 20px;
    margin-top: 55px;
}
.signature-section {
    display: flex;
    justify-content: end;
}
.signature-image {
    max-width: 200px;
    height: auto;
}
</style>
