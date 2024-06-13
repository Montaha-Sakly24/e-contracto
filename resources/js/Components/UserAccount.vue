<template>
    <v-row>
        <v-col cols="12">
            <v-card title="Account Details">
                <v-card-text class="d-flex">
                    <v-avatar rounded="lg" size="100" class="me-6">
                        <img
                            :src="'/storage/' + account.avatar"
                            alt="User's Avatar"
                        />
                    </v-avatar>
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
                                    :error-messages="errors.name"
                                />
                            </v-col>

                            <v-col md="6" cols="12">
                                <v-text-field
                                    v-model="account.phone"
                                    label="Phone"
                                    variant="outlined"
                                    :error-messages="errors.phone"
                                />
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="account.email"
                                    label="E-mail"
                                    type="email"
                                    variant="outlined"
                                    readonly
                                />
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="account.address"
                                    label="Address"
                                    variant="outlined"
                                    :error-messages="errors.address"
                                />
                            </v-col>

                            <v-col cols="12" class="d-flex flex-wrap gap-4">
                                <v-btn
                                    color="info"
                                    variant="flat"
                                    @click="updateProfile"
                                >
                                    Save Changes
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
import axios from "axios";

export default {
    data() {
        return {
            account: {
                avatar: "",
                name: "",
                phone: "",
                email: "",
                address: "",
            },
            errors: {
                name: [],
                phone: [],
                address: [],
            },
        };
    },
    mounted() {
        this.fetchProfile();
    },
    methods: {
        async fetchProfile() {
            try {
                const response = await axios.get("/api/profile", {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
                this.account = response.data;
                console.log("Profile loaded:", this.account);
            } catch (error) {
                console.error("Error fetching profile information:", error);
            }
        },

        async updateProfile() {
            try {
                const response = await axios.put("/api/profile", this.account, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
                console.log("Profile updated successfully:", response.data);
                this.account = response.data;
                toast.fire({
                    icon: "success",
                    title: "Profile updated successfully",
                });
                this.fetchProfile();
            } catch (error) {
                console.error("Error updating profile:", error);
                this.handleErrors(error.response.data);
            }
        },
        handleErrors(errors) {
            this.errors = errors.errors || {};
        },
    },
};
</script>
