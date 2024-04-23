<template>
    <v-row>
        <v-col cols="12">
            <!-- Delete Account -->
            <v-card title="Delete Account">
                <v-card-text>
                    <div>
                        <p>
                            This will permanently delete your account and all
                            associated data. You will not be able to recover
                            your account or any information associated with it.
                        </p>
                        <v-checkbox
                            v-model="isAccountDeleted"
                            label="I have read and understand the effects of account deletion"
                        />
                    </div>

                    <v-btn
                        :disabled="!isAccountDeleted"
                        color="error"
                        class="mt-3"
                        @click="showDeleteDialog"
                    >
                        Delete Account
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <v-dialog v-model="dialogVisible" max-width="350">
        <v-card>
            <v-card-title>Delete Account</v-card-title>
            <v-card-text>
                <p>Are you sure ?</p>
                <v-checkbox
                    v-model="isAccountDeletedSure"
                    label="I confirm my account deletion"
                />
            </v-card-text>
            <v-card-actions>
                <v-btn
                    color="info"
                    variant="flat"
                    :disabled="!isAccountDeletedSure"
                    @click="deleteProfile"
                >
                    Delete
                </v-btn>
                <v-btn color="error" @click="cancelDelete">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { useRouter } from "vue-router";
export default {
    data() {
        return {
            isAccountDeleted: false,
            dialogVisible: false,
            isAccountDeletedSure: false,
            router: useRouter(),
        };
    },
    methods: {
        showDeleteDialog() {
            this.dialogVisible = true;
        },
        async deleteProfile() {
            try {
                const response = await axios.delete("/api/profile/", {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });

                if (response.status === 200) {
                    console.log("Profile deleted successfully:", response.data);
                    // Rediriger vers la page de connexion
                    this.$router.push({ name: "Login" });
                } else {
                    // Afficher un message d'erreur en cas d'échec de la suppression
                    alert("Failed to delete the account.");
                }

                // Réinitialiser l'état
                this.isAccountDeleted = false;
                this.isAccountDeletedSure = false;
                this.dialogVisible = false;
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    console.error("Unauthorized: Please log in again.");
                    // Rediriger vers la page de connexion
                    this.$router.push({ name: "Login" });
                } else {
                    console.error("Error deleting profile:", error);
                    // Afficher un message d'erreur générique en cas d'erreur de la requête
                    alert("Failed to delete the account. Please try again.");
                }
            }
        },

        cancelDelete() {
            // Reset fields and close dialog
            this.isAccountDeleted = false;
            this.isAccountDeletedSure = false;
            this.dialogVisible = false;
        },
    },
};
</script>
