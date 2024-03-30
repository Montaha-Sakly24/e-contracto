<template>
    <link
        href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
        rel="stylesheet"
    />

    <div>
        <v-card
            class="mx-auto pa-12 pb-8"
            elevation="8"
            max-width="400"
            rounded="lg"
        >
            <v-form @submit.prevent="resetPassword">
                <div class="d-flex justify-center mb-6">
                    <v-avatar size="70" class="mx-auto border">
                        <v-icon dark size="42">mdi-lock-outline</v-icon>
                    </v-avatar>
                </div>
                <div class="text-h6 text-center mb-4">
                    Create a strong password
                </div>
                <p class="text-medium-emphasis text-center mb-6">
                    Your password must contain at least 8 characters.
                </p>
                <v-text-field
                    v-model="form.email"
                    density="compact"
                    placeholder="Email address"
                    prepend-inner-icon="mdi-email-outline"
                    variant="outlined"
                    clearable
                    :error-messages="errors.email ? errors.email : []"
                    :outlined="errors.email"
                ></v-text-field>

                <v-text-field
                    v-model="form.newPassword"
                    prepend-inner-icon="mdi-lock-outline"
                    density="compact"
                    variant="outlined"
                    clearable
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="
                        isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'
                    "
                    placeholder="Enter your new password"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                    :error-messages="errors.password ? errors.password : []"
                    :outlined="errors.password"
                ></v-text-field>

                <v-text-field
                    v-model="form.c_newPassword"
                    prepend-inner-icon="mdi-lock-outline"
                    density="compact"
                    variant="outlined"
                    clearable
                    placeholder="Confirm your new password"
                    :type="isConfirmPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="
                        isConfirmPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'
                    "
                    @click:append-inner="
                        isConfirmPasswordVisible = !isConfirmPasswordVisible
                    "
                    :error-messages="errors.c_password ? errors.c_password : []"
                    :outlined="errors.c_password"
                ></v-text-field>

                <v-btn
                    type="submit"
                    block
                    class="mb-8"
                    color="blue"
                    variant="tonal"
                >
                    Save Password
                </v-btn>
            </v-form>
        </v-card>
    </div>
</template>

<script>
import { useRouter } from "vue-router";

import axios from "axios";

export default {
    data() {
        return {
            form: {
                email: "",
                newPassword: "",
                c_newPassword: "",
            },
            isPasswordVisible: false,
            isConfirmPasswordVisible: false,
            router: useRouter(),
            errors: {},
        };
    },
    methods: {
        async resetPassword() {
            try {
                await axios.post("/api/resetpassword", {
                    email: this.form.email,
                    token: this.$route.query.token,
                    password: this.form.newPassword,
                    c_password: this.form.c_newPassword,
                });
                swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Password Changed ! ",
                    text: "Your password has been changed successfully.",
                    text: "Use your new password to log in.",
                    showConfirmButton: false,
                    timer: 2500,
                });
                this.$router.push("/login");
            } catch (error) {
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.errors
                ) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error(error);
                }
            }
        },
    },
};
</script>
