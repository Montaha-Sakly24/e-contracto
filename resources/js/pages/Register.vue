<template>
    <!-- Load Vuetify Icon -->
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

        <p
            class="error-message text-center"
            v-for="error in errors"
            :key="error"
        >
            <span v-for="err in error" :key="err">{{ err }}</span>
        </p>
        <v-card
            class="mx-auto pa-12 pb-8"
            elevation="8"
            max-width="448"
            rounded="lg"
        >
            <form @submit.prevent="signUp">
                <div class="text-subtitle-1 text-medium-emphasis">
                    Create an Account
                </div>

                <v-text-field
                    density="compact"
                    placeholder="First Name"
                    prepend-inner-icon="mdi-account-outline"
                    variant="outlined"
                    v-model="form.name"
                ></v-text-field>

                <!--
                <v-text-field
                    density="compact"
                    placeholder="Last Name"
                    prepend-inner-icon="mdi-account-outline"
                    variant="outlined"
                    v-model="form.surname"
                ></v-text-field>
                -->

                <v-text-field
                    density="compact"
                    placeholder="Email address"
                    prepend-inner-icon="mdi-email-outline"
                    variant="outlined"
                    v-model="form.email"
                ></v-text-field>

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
                <v-text-field
                    :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="visible ? 'text' : 'password'"
                    density="compact"
                    placeholder="Confirm password"
                    prepend-inner-icon="mdi-lock-outline"
                    variant="outlined"
                    @click:append-inner="visible = !visible"
                    v-model="form.c_password"
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
                    <a
                        class="text-blue text-decoration-none"
                        href="/login"
                        rel="noopener noreferrer"
                    >
                        Already have an account? Log in
                        <v-icon icon="mdi-chevron-right"></v-icon>
                    </a>
                </v-card-text>
            </form>
        </v-card>
    </div>
</template>
<script>
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
            name: "",
            email: "",
            password: "",
            c_password: "",
        });

        let errors = ref([]);

        const signUp = async () => {
            await axios
                .post("/api/register", form)
                .then((res) => {
                    if (res.data.success) {
                        store.dispatch("setToken", res.data.data.token);
                        router.push({ name: "Login" });
                    }
                })
                .catch((e) => {
                    errors.value = e.response.data.message;
                });
        };

        return {
            form,
            signUp,
            errors,
        };
    },
};
</script>
<style scoped>
.error-message {
    color: red;
}
</style>
