<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" sm="8" md="4">
                <v-card>
                    <v-card-title>Register</v-card-title>
                    <v-card-text>
                        <v-form>
                            <v-text-field
                                label="Nom"
                                v-model="name"
                            ></v-text-field>
                            <v-text-field
                                label="Prénom"
                                v-model="surname"
                            ></v-text-field>
                            <v-text-field
                                label="E-mail"
                                v-model="email"
                            ></v-text-field>
                            <v-text-field
                                label="Mot de passe"
                                type="password"
                                v-model="password"
                            >
                            </v-text-field>
                            <v-text-field
                                label="Confirmer Mot de passe"
                                type="password"
                                v-model="c_password"
                            >
                            </v-text-field>
                            <v-btn type="submit" color="primary">
                                S'inscrire
                            </v-btn>
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
            name: "",
            surname: "",
            email: "",
            password: "",
            c_password: "",
        };
    },
    methods: {},
};
</script>
