<template>
    <v-container class="page_container" fluid>
        <v-row>
            <v-col cols="12" md="4">
                <v-form ref="form" v-model="valid" class="form-design">
                    <v-container>
                        <v-row v-if="model && model.gabarits.length > 0">
                            <v-col
                                v-for="(field, index) in paginatedFields"
                                :key="index"
                                cols="12"
                            >
                                <v-text-field
                                    :label="`Enter value for ${field.name}`"
                                    v-model="formFields[field.name]"
                                    :rules="[rules.required]"
                                    required
                                    variant="outlined"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <div class="button-group">
                        <v-btn
                            v-if="
                                currentPage === 1 ||
                                currentPage * fieldsPerPage >= totalFields
                            "
                            @click="cancelGenerate"
                            >Cancel</v-btn
                        >
                        <v-btn v-if="currentPage > 1" @click="previousPage"
                            >Back</v-btn
                        >
                        <v-btn
                            v-if="currentPage * fieldsPerPage < totalFields"
                            @click="nextPage"
                            >Next</v-btn
                        >
                        <v-btn v-else color="blue" @click="finalizeSaveContract"
                            >Save Contract</v-btn
                        >
                    </div>
                </v-form>
            </v-col>
            <v-col cols="12" md="8">
                <v-card class="a4-size" outlined v-if="model">
                    <v-card-text>
                        <div v-html="previewContent"></div
                    ></v-card-text>
                </v-card>
                <v-alert v-if="error" type="error">{{ error }}</v-alert>
            </v-col>
        </v-row>
        <v-dialog v-model="dialog" persistent max-width="500px">
            <v-card class="custom-dialog">
                <v-card-title class="custom-dialog-title"
                    >Enter a title for the contract</v-card-title
                >
                <v-card-text>
                    <v-text-field
                        v-model="contractTitle"
                        label="Contract Title"
                        :rules="[rules.required]"
                        variant="outlined"
                        dense
                        ref="titleField"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey darken-1" text @click="dialog = false"
                        >Cancel</v-btn
                    >
                    <v-btn color="blue darken-1" text @click="saveContract"
                        >Save</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import axios from "axios";
import { useRouter } from "vue-router";

export default {
    data() {
        return {
            router: useRouter(),
            model: null,
            error: null,
            formFields: {},
            currentPage: 1,
            fieldsPerPage: 5,
            totalFields: 0,
            rules: {
                required: (value) => !!value || "This field is required.",
            },
            dialog: false,
            contractTitle: "",
        };
    },
    props: {
        modelId: {
            type: String,
            required: true,
        },
    },
    computed: {
        paginatedFields() {
            let allPlaceholders = this.model.gabarits.flatMap(
                (gabarit) => gabarit.placeholders
            );
            this.totalFields = allPlaceholders.length;
            const start = (this.currentPage - 1) * this.fieldsPerPage;
            const end = start + this.fieldsPerPage;
            return allPlaceholders.slice(start, end);
        },
        previewContent() {
            if (!this.model || !this.model.content) return "";
            let preview = this.model.content;
            const placeholders = preview.match(/\[[^\]]+\]/g) || [];
            placeholders.forEach((placeholder) => {
                const key = placeholder.replace(/[\[\]]/g, "");
                if (this.formFields[key]) {
                    const regex = new RegExp("\\[" + key + "\\]", "g");
                    preview = preview.replace(regex, this.formFields[key]);
                }
            });
            console.log("Preview Content:", preview);
            return preview;
        },
    },

    mounted() {
        this.fetchModel();
    },
    methods: {
        async fetchModel() {
            try {
                const response = await axios.get(
                    `/api/models/${this.modelId}/details`
                );
                this.model = response.data;
                this.initializeFormFields();
                console.log("model details: ", this.model);
            } catch (error) {
                this.error = "Error fetching model: " + error.message;
                console.error("Error fetching model:", error);
            }
        },
        initializeFormFields() {
            this.formFields = {};
            this.model.gabarits.forEach((gabarit) => {
                gabarit.placeholders.forEach((placeholder) => {
                    this.formFields[placeholder.name] = "";
                    console.log(
                        "Initialized form field for placeholder:",
                        placeholder.name
                    );
                });
            });
        },
        nextPage() {
            if (this.currentPage * this.fieldsPerPage < this.totalFields) {
                this.currentPage++;
            } else {
            }
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        finalizeSaveContract() {
            const isValid = this.$refs.form.validate();
            if (!this.valid) {
                console.log("Please complete all required fields.");
                return;
            }
            this.dialog = true;
        },
        async saveContract() {
            if (!this.$refs.titleField.validate()) {
                console.log("Please enter a title for the contract.");
                return;
            }
            if (!this.contractTitle) {
                console.log("Please enter a title for the contract.");
                return;
            }
            try {
                const contractData = {
                    title: this.contractTitle,
                    content: this.previewContent,
                    model_id: this.modelId,
                };
                const response = await axios.post(
                    "/api/contracts/",
                    contractData,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "token"
                            )}`,
                        },
                    }
                );
                console.log("Contract saved:", response.data);
                this.router.push({ name: "Contracts" });
                this.dialog = false;
                this.contractTitle = "";
            } catch (error) {
                console.error("Error saving contract:", error.response.data);
                this.error =
                    "Failed to save contract: " + error.response.data.message;
                this.dialog = false;
            }
        },
        cancelGenerate() {
            this.router.push({ name: "Home" });
        },
    },
};
</script>

<style scoped>
.a4-size {
    width: 100%;
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    padding: 20px;
    transition: box-shadow 0.3s ease-in-out;
}
.form-design {
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
}

.button-group {
    display: flex;
    justify-content: space-between;
    padding-top: 20px;
}

.page_container {
    padding: 20px;
    background-color: #eeeeee;
    height: 100vh;
}

.v-btn {
    margin: 0 4px;
}

@media (max-width: 768px) {
    .button-group {
        flex-direction: column;
    }
    .v-btn {
        width: 100%;
        margin-top: 10px;
    }
}
.custom-dialog {
    border-radius: 40px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.6);
}

.custom-dialog-title {
    font-size: 18px;
    padding: 16px;
    color: rgb(5, 5, 188);
}
</style>
