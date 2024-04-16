<template>
    <v-row>
        <v-col cols="12">
            <v-card title="Account Details">
                <v-card-text class="d-flex">
                    <v-avatar :src="account.avatarImg" rounded="lg" size="100" class="me-6" />
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
                            <v-col cols="12" class="d-flex flex-wrap gap-4">
                                <v-btn
                                    color="info"
                                    variant="flat"
                                    @click="updateProfile"
                                >
                                    Update Profile
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
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
            userId: localStorage.getItem('userId'), // Assuming the user ID is stored in local storage
        };
    },
    mounted() {
        this.fetchProfileInformation();
    },
    methods: {
        async fetchProfileInformation() {
            try {
                const response = await axios.get("/api/profile/" + this.userId);
                this.account = {...this.account, ...response.data};
            } catch (error) {
                console.error("Error fetching profile information:", error);
            }
        },
        async updateProfile() {
            try {
                const response = await axios.put("/api/profile/" + this.userId, this.account);
                console.log("Profile updated successfully:", response.data);
                this.account = {...this.account, ...response.data}; // Reflect updated data
            } catch (error) {
                console.error("Error updating profile:", error);
            }
        },
    },
};
</script>
