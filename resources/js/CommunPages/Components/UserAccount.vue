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

                    <!-- 👉 Upload Photo -->
                    <v-form class="d-flex flex-column justify-center gap-5">
                        <v-card-actions>
                            <v-btn
                                color="info"
                                variant="outlined"
                                prepend-icon="mdi-cloud-upload"
                                @click="refInputEl?.click()"
                            >
                                <span class="d-none d-sm-block"
                                    >Upload new photo</span
                                >
                            </v-btn>
                            <input
                                ref="refInputEl"
                                type="file"
                                name="file"
                                accept=".jpeg,.png,.jpg,GIF"
                                hidden
                                @input="changeAvatar"
                            />
                            <v-btn
                                type="reset"
                                color="error"
                                variant="outlined"
                                prepend-icon="mdi-refresh"
                                @click="resetAvatar"
                            >
                                <span class="d-none d-sm-block">Reset</span>
                            </v-btn>
                        </v-card-actions>
                        <p class="text-body-1 mb-0">
                            Allowed JPG, GIF or PNG. Max size of 800K
                        </p>
                    </v-form>
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

                            <!--  Form Actions -->
                            <v-col cols="12" class="d-flex flex-wrap gap-4">
                                <v-btn
                                    color="info"
                                    variant="flat"
                                    @click="showUpdateDialog"
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

    <v-dialog v-model="dialogVisible" max-width="500">
        <v-card>
            <v-card-title>Update Profile</v-card-title>
            <v-card-text>
                <v-text-field
                    v-model="form.name"
                    label="Name"
                    variant="outlined"
                />
                <v-text-field
                    v-model="form.phone"
                    label="Phone"
                    variant="outlined"
                />
                <v-text-field
                    v-model="form.address"
                    label="Address"
                    variant="outlined"
                />
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" variant="flat" @click="updateProfile">
                    Save Changes
                </v-btn>
                <v-btn color="error" @click="cancelUpdate">Reset</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
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
            form: {
                name: "",
                phone: "",

                address: "",
            },
            refInputEl: null,
            dialogVisible: false,

            user: { id: "12" },
        };
    },
    mounted() {
        this.fetchProfileInformation();
    },
    methods: {
        async fetchProfileInformation() {
            try {
                const response = await axios.get(
                    "/api/profile/" + this.user.id
                );
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
        showUpdateDialog() {
            this.form.name = "";
            this.form.phone = "";
            this.form.address = "";
            this.dialogVisible = true;
        },
        async updateProfile() {
            try {
                const response = await axios.put(
                    "/api/profile/" + this.user.id,
                    this.form
                );
                console.log("Profile updated successfully:", response.data);

                this.form.name = response.data.name;
                this.form.phone = response.data.phone;
                this.form.address = response.data.address;
                this.dialogVisible = false;
                await this.fetchProfileInformation();
            } catch (error) {
                console.error("Error updating profile:", error);
            }
        },
        cancelUpdate() {
            this.dialogVisible = false;
        },
    },
};
</script>

/*const changeAvatar = (file) => { // Implement logic to change avatar... };
const resetAvatar = () => { // Implement logic to reset avatar... }; const
resetForm = () => { // Implement logic to reset form... }; const saveChanges =
() => { // Implement logic to save changes... }; // Mock function to simulate
fetching data from the database function fetchDataFromDatabase() {} */
