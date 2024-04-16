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
                                    v-model="currentPassword"
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
                                />
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    variant="outlined"
                                    v-model="newPassword"
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
                                />
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field
                                    variant="outlined"
                                    v-model="confirmPassword"
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
                                />
                            </v-col>
                        </v-row>
                        <p class="text-medium-emphasis" style="font-size: 20px">
                            Password Requirement: Minimum 8 characters
                        </p>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="info" variant="flat">Save Changes</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script setup>
import { ref } from "vue";

const isCurrentPasswordVisible = ref(false);
const isNewPasswordVisible = ref(false);
const isConfirmPasswordVisible = ref(false);
</script>
