<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" sm="8" md="4">
                <v-card>
                    <v-card-title>Login</v-card-title>
                    <v-card-text>
                        <v-form @submit.prevent="login">
                            <v-text-field
                                label="E-mail"
                                v-model="email"
                            ></v-text-field>
                            <v-text-field
                                label="Mot de passe"
                                v-model="password"
                                type="password"
                            ></v-text-field>
                            <v-btn type="submit" color="primary"
                                >Se connecter</v-btn
                            >
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
        };
    },
    methods: {
        login() {
            // logique de connexion ici
        },
    },
};
</script>
