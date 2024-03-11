<template>
    <!-- Load Vuetify Icon and Styles -->
    <link
        href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css"
        rel="stylesheet"
    />

    <div>
        <v-img
            class="mx-auto my-6"
            max-width="228"
            src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
        ></v-img>

        <p class="text-danger" v-if="error">{{ error }}</p>

        <v-card
            class="mx-auto pa-12 pb-8"
            elevation="8"
            max-width="448"
            rounded="lg"
        >
            <v-form @submit.prevent="login">
                <div class="text-subtitle-1 text-medium-emphasis">Account</div>

                <v-text-field
                    density="compact"
                    placeholder="Email address"
                    prepend-inner-icon="mdi-email-outline"
                    variant="outlined"
                    v-model="form.email"
                ></v-text-field>

                <div
                    class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
                >
                    Password

                    <a
                        class="text-caption text-decoration-none text-blue"
                        href="#"
                        rel="noopener noreferrer"
                        target="_blank"
                    >
                        Forgot login password?</a
                    >
                </div>

                <v-text-field
                    :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="visible ? 'text' : 'password'"
                    density="compact"
                    placeholder="Enter your password"
                    prepend-inner-icon="mdi-lock-outline"
                    variant="outlined"
                    @click:append-inner="visible = !visible"
                    v-model="form.password"
                ></v-text-field>

                <v-btn
                    type="submit"
                    block
                    class="mb-8"
                    color="blue"
                    size="large"
                    variant="tonal"
                >
                    Log In
                </v-btn>

                <v-card-text class="text-center">
                    <a
                        class="text-blue text-decoration-none"
                        href="/register"
                        rel="noopener noreferrer"
                    >
                        Sign up now <v-icon icon="mdi-chevron-right"></v-icon>
                    </a>
                </v-card-text>
            </v-form>
        </v-card>
    </div>
</template>
<script>
import Button from "../components/UI/SaveButton.vue";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
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

        let error = ref("");

        const login = async () => {
            await axios.post("/api/login", form).then((res) => {
                if (res.data.success) {
                    store.dispatch("setToken", res.data.data.token);
                    router.push({ name: "Dashboard" });
                    console.log("User logged in successfully");
                } else {
                    error.value = res.data.message;
                }
            });
        };

        return {
            form,
            login,
            error,
        };
    },
};
</script>
