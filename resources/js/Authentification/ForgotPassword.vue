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
            <v-form @submit.prevent="forgotPassword">
                <div class="d-flex justify-center mb-6">
                    <v-avatar size="70" class="mx-auto border">
                        <v-icon dark size="42">mdi-lock-outline</v-icon>
                    </v-avatar>
                </div>
                <div class="text-h6 text-center mb-4">Password Forgotten ?</div>
                <p class="text-medium-emphasis text-center mb-6">
                    Enter your email address and we'll send you a link to
                    recover your account.
                </p>
                <v-text-field
                    v-model="email"
                    density="compact"
                    placeholder="Email address"
                    prepend-inner-icon="mdi-email-outline"
                    variant="outlined"
                    clearable
                    :error-messages="errors.email ? errors.email : []"
                    :outlined="errors.email"
                ></v-text-field>

                <v-btn
                    type="submit"
                    block
                    class="mb-8"
                    color="blue"
                    variant="tonal"
                    @click="forgotPassword"
                >
                    Reset Password
                </v-btn>

                <div class="text-center mb-4">--------ou--------</div>
                <v-card-text class="text-center">
                    <router-link class="text-info" to="/register">
                        Sign Up
                    </router-link>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text class="text-center">
                    <router-link
                        class="text-blue text-decoration-none"
                        to="/login"
                    >
                        Return to Log In page
                        <v-icon class="ml-2">mdi-chevron-right</v-icon>
                    </router-link>
                </v-card-text>
            </v-form>
        </v-card>
    </div>
</template>

<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import axios from "axios";

export default {
    data() {
        return {
            email: "",
            errors: {},
        };
    },
    methods: {
        async forgotPassword() {
            try {
                await axios.post("/api/forgotpassword", { email: this.email });
                // Check if the response contains an error message related to superadministrator role

                toast.fire({
                    icon: "info",
                    title: "Notification:",
                    text: "We have sent an email to you .Check your email",
                });
            } catch (error) {
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.error ===
                        "Superadministrators cannot reset their passwords."
                ) {
                    alert("Superadministrators cannot reset their passwords.");
                } else if (
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
