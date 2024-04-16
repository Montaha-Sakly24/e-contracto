<template>
    <link
        href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
        rel="stylesheet"
    />

    <div>
        <v-card
            v-if="!registrationSuccess"
            class="mx-auto pa-12 pb-8"
            elevation="8"
            max-width="448"
            rounded="lg"
            ><v-img
                class="mx-auto my-6"
                max-width="150"
                src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
            ></v-img>
            <v-form @submit.prevent="signUp">
                <div class="text-subtitle-1 text-medium-emphasis">
                    Create an Account
                </div>

                <v-text-field
                    v-model="form.name"
                    density="compact"
                    placeholder="Name"
                    prepend-inner-icon="mdi-account-outline"
                    variant="outlined"
                    clearable
                    :error-messages="errors.name ? [errors.name[0]] : []"
                    :outlined="errors.name"
                ></v-text-field>

                <v-text-field
                    v-model="form.phone"
                    density="compact"
                    placeholder="Phone"
                    prepend-inner-icon="mdi-phone"
                    variant="outlined"
                    clearable
                    :error-messages="errors.phone ? [errors.phone[0]] : []"
                    :outlined="errors.phone"
                ></v-text-field>

                <v-text-field
                    v-model="form.address"
                    density="compact"
                    placeholder="Address"
                    prepend-inner-icon="mdi-map-marker"
                    variant="outlined"
                    clearable
                    :error-messages="errors.address ? [errors.address[0]] : []"
                    :outlined="errors.address"
                ></v-text-field>

                <v-text-field
                    v-model="form.email"
                    density="compact"
                    placeholder="Email address"
                    prepend-inner-icon="mdi-email-outline"
                    variant="outlined"
                    clearable
                    :error-messages="errors.email ? [errors.email[0]] : []"
                    :outlined="errors.email"
                ></v-text-field>

                <v-text-field
                    v-model="form.password"
                    prepend-inner-icon="mdi-lock-outline"
                    density="compact"
                    variant="outlined"
                    clearable
                    placeholder="Enter your password"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="
                        isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'
                    "
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                    :error-messages="
                        errors.password ? [errors.password[0]] : []
                    "
                    :outlined="errors.password"
                ></v-text-field>

                <v-text-field
                    v-model="form.c_password"
                    prepend-inner-icon="mdi-lock-outline"
                    density="compact"
                    variant="outlined"
                    clearable
                    placeholder="Confirm your password"
                    :type="isConfirmPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="
                        isConfirmPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'
                    "
                    @click:append-inner="
                        isConfirmPasswordVisible = !isConfirmPasswordVisible
                    "
                    :error-messages="
                        errors.c_password ? [errors.c_password[0]] : []
                    "
                    :outlined="errors.c_password"
                ></v-text-field>

                <v-btn
                    type="submit"
                    block
                    class="mb-8"
                    color="blue"
                    size="large"
                    variant="tonal"
                >
                    Sign Up
                </v-btn>

                <v-card-text class="text-center">
                    <router-link
                        class="text-blue text-decoration-none"
                        to="/login"
                    >
                        Already have an account? Log in
                        <v-icon icon="mdi-chevron-right"></v-icon>
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
    setup() {
        const router = useRouter();
        const store = useStore();

        const isPasswordVisible = ref(false);
        const isConfirmPasswordVisible = ref(false);
        const form = reactive({
            name: "",
            phone: "",
            address: "",
            email: "",
            password: "",
            c_password: "",
        });

        const errors = ref([]);

        const signUp = async () => {
            try {
                const res = await axios.post("/api/register", form);

                if (res.data.success) {
                    store.dispatch("setToken", res.data.data.token);
                    swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registration Successful ! ",
                        text: "Congratulation, your account has been successfully created.",
                        showConfirmButton: false,
                        timer: 2500,
                    });
                    router.push({ name: "Login" });
                }
            } catch (e) {
                errors.value = e.response.data.message;
                swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                    showConfirmButton: false,
                    timer: 1500,
                });
            }
        };

        return {
            isPasswordVisible,
            isConfirmPasswordVisible,
            form,
            errors,
            signUp,
        };
    },
};
</script>

<style scoped>
.error-message {
    color: red;
}
</style>
