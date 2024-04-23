<template>
    <v-row>
        <v-col cols="12">
            <v-card>
                <v-card-title class="text-high-emphasis font-weight-bold"
                    >Change Password</v-card-title
                >
                <v-form>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    variant="outlined"
                                    v-model="form.currentPassword"
                                    :type="
                                        isCurrentPasswordVisible
                                            ? 'text'
                                            : 'password'
                                    "
                                    :append-icon="
                                        isCurrentPasswordVisible
                                            ? 'mdi-eye'
                                            : 'mdi-eye-off'
                                    "
                                    label="Current Password"
                                    @click:append="
                                        isCurrentPasswordVisible =
                                            !isCurrentPasswordVisible
                                    "
                                    outlined
                                    :error-messages="errors['current_password']"
                                />
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    variant="outlined"
                                    v-model="form.newPassword"
                                    :type="
                                        isNewPasswordVisible
                                            ? 'text'
                                            : 'password'
                                    "
                                    :append-icon="
                                        isNewPasswordVisible
                                            ? 'mdi-eye'
                                            : 'mdi-eye-off'
                                    "
                                    label="New Password"
                                    @click:append="
                                        isNewPasswordVisible =
                                            !isNewPasswordVisible
                                    "
                                    outlined
                                    :error-messages="errors['new_password']"
                                />
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field
                                    variant="outlined"
                                    v-model="form.confirmPassword"
                                    :type="
                                        isConfirmPasswordVisible
                                            ? 'text'
                                            : 'password'
                                    "
                                    :append-icon="
                                        isConfirmPasswordVisible
                                            ? 'mdi-eye'
                                            : 'mdi-eye-off'
                                    "
                                    label="Confirm New Password"
                                    @click:append="
                                        isConfirmPasswordVisible =
                                            !isConfirmPasswordVisible
                                    "
                                    outlined
                                    :error-messages="errors['c_new_password']"
                                />
                            </v-col>
                        </v-row>
                        <p class="text-medium-emphasis" style="font-size: 20px">
                            Password Requirement: Minimum 8 characters
                        </p>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="info"
                            variant="flat"
                            @click="changePassword"
                            >Save Changes</v-btn
                        >
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const isCurrentPasswordVisible = ref(false);
const isNewPasswordVisible = ref(false);
const isConfirmPasswordVisible = ref(false);

const form = ref({
    currentPassword: "",
    newPassword: "",
    confirmPassword: "",
});

const errors = ref({});

const resetForm = () => {
    form.value.currentPassword = "";
    form.value.newPassword = "";
    form.value.confirmPassword = "";
    errors.value = {};
};

const changePassword = async () => {
    try {
        const response = await axios.post(
            "/api/profile/",
            {
                current_password: form.value.currentPassword,
                new_password: form.value.newPassword,
                c_new_password: form.value.confirmPassword,
            },
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            }
        );
        toast.fire({
            icon: "success",
            title: "Password changed successfully ",
        });
        console.log(response.data);
        resetForm();
    } catch (error) {
        if (error.response && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            console.error("An unexpected error occurred:", error);
        }
    }
};
</script>
