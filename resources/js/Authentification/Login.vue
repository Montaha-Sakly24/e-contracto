<template>
    <!-- Load Vuetify Icon and Styles -->
    <link
        href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
        rel="stylesheet"
    />

    <div>
        <v-card
            class="mx-auto pa-12 pb-8"
            elevation="8"
            max-width="450"
            rounded="lg"
            ><v-img
                class="mx-auto my-6"
                max-width="150"
                src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
            ></v-img>
            <v-card-text class="pt-2 text-medium-emphasis">
                <h5 class="text-h5 mb-1">Welcome to E-Contracto 👋🏻</h5>
                <p class="mb-0">
                    Please sign-in to your account and start the adventure
                </p>
            </v-card-text>

            <v-card-text>
                <v-form @submit.prevent="login">
                    <div class="text-subtitle-1 text-medium-emphasis">
                        Account
                    </div>
                    <v-text-field
                        v-model="form.email"
                        density="compact"
                        placeholder="Email address"
                        prepend-inner-icon="mdi-email-outline"
                        variant="outlined"
                        clearable
                        :error-messages="errors.email ? errors.email : []"
                    ></v-text-field>

                    <div
                        class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
                    >
                        Password
                        <router-link
                            class="text-caption text-decoration-none text-blue"
                            to="/forgotpassword"
                        >
                            Forgot password?
                        </router-link>
                    </div>

                    <v-text-field
                        v-model="form.password"
                        density="compact"
                        placeholder="Enter your password"
                        prepend-inner-icon="mdi-lock-outline"
                        variant="outlined"
                        clearable
                        :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="visible ? 'text' : 'password'"
                        @click:append-inner="visible = !visible"
                        :error-messages="errors.password ? errors.password : []"
                    ></v-text-field>

                    <v-btn
                        type="submit"
                        block
                        class="mb-8"
                        color="blue"
                        variant="tonal"
                    >
                        Login
                    </v-btn>

                    <v-card-text class="text-center">
                        <router-link
                            class="text-blue text-decoration-none"
                            to="/register"
                        >
                            Sign up now
                            <v-icon icon="mdi-chevron-right"></v-icon>
                        </router-link>
                    </v-card-text>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import axios from "axios";
export default {
    data: () => ({
        visible: false,
    }),
    setup() {
        const router = useRouter();
        const store = useStore();

        let form = reactive({
            email: "",
            password: "",
        });
        let errors = reactive({
            email: "",
            password: "",
        });

        let error = ref("");

        const login = async () => {
            try {
                const response = await axios.post("/api/login", form);
                if (response.data.success) {
                    store.dispatch("setToken", response.data.data.token);
                    const role = response.data.data.role;
                    if (role.includes("Superadmin")) {
                        router.push({ name: "Dashboard" });
                    } else if (role.includes("Admin")) {
                        router.push({ name: "Home" });
                    } else if (role.includes("Simple User")) {
                        router.push({ name: "Profile" });
                    }

                    swal.fire({
                        position: "top",
                        icon: "success",
                        title: "Welcome ! ",
                        text: "You are now logged in successfully ",
                        showConfirmButton: false,
                        timer: 1400,
                    });
                } else {
                    if (response.status === 403) {
                        error.value =
                            "Your account is deactivated. Please contact the administrator!";
                        swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Your account is deactivated. Please contact the administrator!",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    } else {
                        swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Invalid email address or password.",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        error.value =
                            response.data.message ||
                            "Invalid email address or password.";
                    }
                }
            } catch (error) {
                swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Invalid email address or password",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        };

        return {
            form,
            login,
            error,
            errors,
        };
    },
};
</script>
