<template>
    <v-row>
        <v-col cols="12">
            <v-card title="Account Details">
                <v-card-text class="d-flex">
                    <v-avatar
                        rounded="lg"
                        size="100"
                        class="me-6"
                        :image="account.avatarImg"
                    />
                </v-card-text>

                <v-divider />

                <v-card-text>
                    <v-form class="mt-6">
                        <v-row>
                            <v-col md="6" cols="12">
                                <v-text-field
                                    v-model="account.name"
                                    label="Name"
                                    variant="outlined"
                                />
                            </v-col>

                            <v-col md="6" cols="12">
                                <v-text-field
                                    v-model="account.phone"
                                    label="Phone"
                                    variant="outlined"
                                />
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="account.email"
                                    label="E-mail"
                                    type="email"
                                    variant="outlined"
                                />
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="account.address"
                                    label="Address"
                                    variant="outlined"
                                />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12">
            <v-card title="Delete Account">
                <v-card-text>
                    <v-btn color="error" class="mt-3"> Delete Account </v-btn>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import avatar from "../../pictures/avatar.png";
import axios from "axios";

export default {
    data() {
        return {
            account: {
                avatarImg: avatar,
                name: "",
                phone: "",
                email: "",
                address: "",
            },
            refInputEl: null,
        };
    },
    mounted() {},
    methods: {
        async fetchProfileInformation() {
            try {
                const response = await axios.get("/api/profile/" + user.id);
                const userData = response.data;
                this.account.name = userData.name;
                this.account.phone = userData.phone;
                this.account.email = userData.email;
                this.account.address = userData.address;
                console.log(this.account);
            } catch (error) {
                console.error("Error fetching profile information:", error);
            }
        },
    },
};
</script>
