<template>
    <div>
        <!-- Delete Account -->
        <v-card>
            <v-card-title>Delete Account</v-card-title>
            <v-card-text>
                <div>
                    <p>
                        This will permanently delete your account and all
                        associated data. You will not be able to recover your
                        account or any information associated with it.
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

        <!-- Delete Account Confirmation Dialog -->
        <v-dialog v-model="dialogVisible" max-width="350">
            <v-card>
                <v-card-title>Delete Account</v-card-title>
                <v-card-text>
                    <p>Are you sure?</p>
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
    </div>
</template>

<script>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

export default {
    setup() {
        const isAccountDeleted = ref(false);
        const dialogVisible = ref(false);
        const isAccountDeletedSure = ref(false);
        const router = useRouter();

        const showDeleteDialog = () => {
            dialogVisible.value = true;
        };

        const deleteProfile = async () => {
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
                    localStorage.clear();
                    router.push({ name: "Login" });
                } else {
                    throw new Error("Failed to delete profile");
                }
            } catch (error) {
                console.error("Error deleting profile:", error);
                alert(error.message);
            }
        };

        const cancelDelete = () => {
            isAccountDeleted.value = false;
            isAccountDeletedSure.value = false;
            dialogVisible.value = false;
        };

        return {
            isAccountDeleted,
            dialogVisible,
            isAccountDeletedSure,
            showDeleteDialog,
            deleteProfile,
            cancelDelete,
        };
    },
};
</script>
