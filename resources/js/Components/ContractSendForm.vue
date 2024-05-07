<template>
    <v-container>
        <v-form @submit.prevent="sendContract">
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="recipientEmail"
                        label="Recipient's Email"
                        required
                    ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                    <v-select
                        v-model="contract_id"
                        :items="contracts"
                        label="Select Contract"
                        item-text="title"
                        item-value="id"
                        required
                    ></v-select>
                </v-col>
            </v-row>
            <v-btn type="submit" color="primary">Send Contract</v-btn>
        </v-form>
    </v-container>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            recipientEmail: "",
            contract_id: null,
            contracts: [],
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
        async sendContract() {
            try {
                const response = await axios.post(
                    "/api/contracts/send-contract",
                    {
                        recipientEmail: this.recipientEmail,
                        contract_id: this.contract_id,
                    }
                );
                this.$snackbar.success("Contract sent successfully.");
            } catch (error) {
                console.error("Error sending contract:", error);
                let errorMessage =
                    "Failed to send contract. Please try again later.";
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.message
                ) {
                    errorMessage = error.response.data.message;
                }
                this.$snackbar.error(errorMessage);
            }
        },
    },
};
</script>

<style scoped>
/* Add your component styles here */
</style>
