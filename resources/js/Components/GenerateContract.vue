<template>
    <v-container fluid>
        <v-row>
            <!-- Dynamic Form Column -->
            <v-col>
                <v-card outlined>
                    <v-card-text>
                        <v-form ref="form" v-model="valid">
                            <v-container>
                                <v-row>
                                    <!-- Generate a text field for each placeholder -->
                                    <v-text-field
                                        v-for="(value, key) in formFields"
                                        :key="key"
                                        v-model="formFields[key]"
                                        :label="`Enter value for ${key}`"
                                        required
                                    ></v-text-field>
                                </v-row>
                            </v-container>
                        </v-form>
                    </v-card-text>
                    <v-btn @click="saveContract">Save Contract</v-btn>
                </v-card>
            </v-col>

            <!-- Preview Column -->
            <v-col>
                <v-card class="a4-size" outlined>
                    <v-card-title>
                        Preview
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-card-text
                        v-html="renderedContent"
                        class="preview-content"
                    ></v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from "axios";

export default {
    props: {
        modelId: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            model: null,
            formFields: {},
            valid: true,
        };
    },
    computed: {
        renderedContent() {
            if (!this.model) return "";
            let content = this.model.content;
            Object.keys(this.formFields).forEach((key) => {
                const placeholder = new RegExp(`\\[${key}\\]`, "g");
                content = content.replace(
                    placeholder,
                    this.formFields[key] || ""
                );
            });
            return content;
        },
    },
    mounted() {
        this.fetchModel();
    },
    methods: {
        async fetchModel() {
            try {
                const response = await axios.get(`/api/models/${this.modelId}`);
                this.model = response.data;
                this.initializeFormFields();
            } catch (error) {
                console.error("Error fetching model:", error);
            }
        },
        initializeFormFields() {
            const placeholderRegex = /\[([^\]]+)\]/g;
            let match;
            while ((match = placeholderRegex.exec(this.model.content))) {
                this.formFields[match[1]] = "";
            }
        },
        saveContract() {
            if (this.valid) {
                console.log("Contract data to save:", this.formFields);
                // Perform save operation here
            }
        },
    },
};
</script>

<style scoped>
.a4-size {
    width: 210mm;
    height: 297mm;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.preview-content {
    max-height: 297mm;
    overflow-y: auto;
}

.v-container {
    padding: 20px;
}

.v-form {
    max-width: 500px;
    margin: 20px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.v-text-field {
    margin-bottom: 24px;
}

.v-btn {
    margin-top: 20px;
    width: 100%;
    padding: 10px 0;
    font-size: 1.1em;
}
</style>
