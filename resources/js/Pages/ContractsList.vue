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
                                    color="accent"
                                    variant="outlined"
                                    @click.prevent.stop="viewContract(item)"
                                    ><font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'eye']"
                                    />
                                    PDF
                                </v-btn>

                                <v-btn
                                    variant="outlined"
                                    @click="toggleSignDialog(item)"
                                    :disabled="item.status === 'signed'"
                                >
                                    Sign
                                </v-btn>
                                <v-btn
                                    color="darkblueshade"
                                    variant="outlined"
                                    @click="exportContract(item)"
                                    class="export-btn"
                                >
                                    <v-icon class="pr-2">mdi-download</v-icon>
                                    Docx
                                </v-btn>
                                <v-btn
                                    color="red"
                                    variant="outlined"
                                    @click="deleteContract(item)"
                                    ><font-awesome-icon
                                        :icon="['fas', 'trash']"
                                    />
                                </v-btn>
                                <v-btn
                                    color="muted"
                                    variant="outlined"
                                    @click="sendContract(item)"
                                    ><v-icon class="pr-2">mdi-mail</v-icon>
                                    Send
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
            max-width="lg"
        >
            <v-btn
                icon
                @click="viewDialog = false"
                title="Close"
                color="muted"
                class="view-dialog-close-btn"
            >
                <font-awesome-icon :icon="['fas', 'times']" />
            </v-btn>
            <div class="iframe-container">
                <iframe :src="contract" class="view-contract-iframe" />
            </div>
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
        <v-dialog v-model="showSendDialog" persistent max-width="400">
            <v-card class="send-dialog">
                <v-card-title class="send-dialog-title"
                    >Send Contract</v-card-title
                >
                <v-card-text>
                    <v-text-field
                        v-model="recipientEmail"
                        label="Recipient Email"
                        variant="outlined"
                        dense
                        :error-messages="errors.recipient_email"
                    ></v-text-field>
                    <v-text-field
                        v-model="subject"
                        label="Subject"
                        variant="outlined"
                        dense
                        :error-messages="errors.subject"
                    ></v-text-field>
                    <v-textarea
                        v-model="body"
                        label="Body"
                        variant="outlined"
                        dense
                        :error-messages="errors.body"
                    ></v-textarea>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn text @click="showSendDialog = false">Cancel</v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="sendContractWithEmail"
                    >
                        Send
                    </v-btn>
                </v-card-actions>
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
            showSendDialog: false,
            recipientEmail: "",
            subject: "",
            body: "",
            errors: {},
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
                    "/api/contracts/" + contract.id,
                    {
                        responseType: "blob",
                        headers: {
                            Accept: "application/pdf",
                        },
                    }
                );
                const blob = new Blob([response.data], {
                    type: "application/pdf",
                });
                const blobUrl = URL.createObjectURL(blob);
                this.contract = blobUrl;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing contract:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing contract",
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
        async sendContract(contract) {
            try {
                this.showSendDialog = true;
                this.contract = contract;
            } catch (error) {
                console.error("Error sending contract:", error);
            }
        },
        sendContractWithEmail() {
            const senderEmail = localStorage.getItem("userEmail"); //////////
            console.log("Sender's Email:", senderEmail);
            axios
                .post("/api/contracts/send-contract", {
                    contract_id: this.contract.id,
                    recipient_email: this.recipientEmail,
                    subject: this.subject,
                    body: this.body,
                    sender_email: senderEmail, /////////////
                })
                .then((response) => {
                    console.log(response.data.message);
                    toast.fire({
                        icon: "success",
                        title: "Contract sent successfully",
                    });
                    this.errors = {};
                })
                .catch((error) => {
                    console.error("Error sending contract:", error);
                    if (error.response && error.response.status === 422) {
                        // If validation error, parse the error response and set the errors object
                        const errorData = error.response.data;
                        if (errorData.errors) {
                            // If there are errors for specific fields
                            this.errors = errorData.errors;
                        } else if (errorData.error) {
                            // If there is a general error message
                            this.errors = { general: [errorData.error] };
                        }
                    } else {
                        console.error("Error sending contract:", error);
                        toast.fire({
                            icon: "error",
                            title: "Error sending contract",
                        });
                    }
                })
                .finally(() => {
                    this.showSendDialog = false;
                    this.recipientEmail = "";
                    this.subject = "";
                    this.body = "";
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

.title,
b {
    font-family: "Times New Roman", Times, serif;
}
.dialog-container {
    position: relative;
}

.dialog-close-btn {
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

.iframe-container {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

.view-contract-iframe {
    width: 100%;
    max-width: 900px;
    height: 90vh;
}
.view-dialog-close-btn {
    position: absolute;
    top: 0px;
    right: 70px;
    z-index: 10;
    border-radius: 8px;
    height: 37px !important;
    width: 40px !important;
}
.send-dialog {
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.send-dialog-title {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}
</style>
