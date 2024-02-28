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

        <v-card
            class="mx-auto pa-12 pb-8"
            elevation="8"
            max-width="448"
            rounded="lg"
        >
            <div class="text-subtitle-1 text-medium-emphasis">
                Create an Account
            </div>

            <v-text-field
                density="compact"
                placeholder="First Name"
                prepend-inner-icon="mdi-account-outline"
                variant="outlined"
                v-model="name"
            ></v-text-field>

            <v-text-field
                density="compact"
                placeholder="Last Name"
                prepend-inner-icon="mdi-account-outline"
                variant="outlined"
                v-model="surname"
            ></v-text-field>

            <v-text-field
                density="compact"
                placeholder="Email address"
                prepend-inner-icon="mdi-email-outline"
                variant="outlined"
                v-model="email"
            ></v-text-field>

            <v-text-field
                :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                :type="visible ? 'text' : 'password'"
                density="compact"
                placeholder="Enter your password"
                prepend-inner-icon="mdi-lock-outline"
                variant="outlined"
                @click:append-inner="visible = !visible"
                v-model="password"
            ></v-text-field>
            <v-text-field
                :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                :type="visible ? 'text' : 'password'"
                density="compact"
                placeholder="Confirm password"
                prepend-inner-icon="mdi-lock-outline"
                variant="outlined"
                @click:append-inner="visible = !visible"
                v-model="c_password"
            ></v-text-field>

            <v-btn
                type="submit"
                @click="signUp"
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
                    href="#"
                    rel="noopener noreferrer"
                    target="_blank"
                >
                    Already have an account? Log in
                    <v-icon icon="mdi-chevron-right"></v-icon>
                </a>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
export default {
    data: () => ({
        visible: false,
    }),
    return: {
        name: "",
        surname: "",
        email: "",
        password: "",
        c_password: "",
    },
};
</script>
