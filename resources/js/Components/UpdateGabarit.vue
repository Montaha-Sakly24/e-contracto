<template>
    <v-btn color="darkblue" variant="outlined" @click="showUpdateDialog(item)">
        <font-awesome-icon class="pr-2" :icon="['fas', 'pen-alt']" /> Update
    </v-btn>
    <v-dialog
        v-model="updateDialog"
        persistent
        :scrollable="false"
        :hide-overlay="true"
        no-click-animation
        width="1600"
        content-class="custom-vuetify-dialog-add"
    >
        <v-card>
            <v-card-title class="text-h6 title-modal" v-if="gabarit">
                <v-label class="label-header">
                    Update {{ gabarit.title }}
                </v-label>
            </v-card-title>
            <v-divider class="line-hr"></v-divider>
            <v-card-text class="body-card">
                <v-form class="form-add" ref="formAddBien">
                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                                class="mt-3"
                                label="Title"
                                variant="outlined"
                                color="blue"
                                v-model="gabarit.title"
                                ref="title"
                                persistent-placeholder
                                clearable
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="6">
                            <v-row>
                                <v-col
                                    cols="3"
                                    v-for="placeholder in getListPlaceholders"
                                    :key="placeholder.id"
                                    :title="placeholder.name"
                                    @click="appendContent(placeholder)"
                                    class="filed-class d-flex"
                                >
                                    <div class="icon-field">☰</div>
                                    <div class="value-field">
                                        {{ placeholder.name }}
                                    </div>
                                    <div class="icon-copy">
                                        <font-awesome-icon
                                            :icon="['fas', 'copy']"
                                        /></div
                                ></v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="6">
                            <Editor
                                api-key="u3wugla2xzhrdg6u3ikszuh08rf6kydmvb1dlnl5dn3npntm"
                                :init="computedDataTinymce"
                                v-model="gabarit.content"
                                :id="'myEditor'"
                            />
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
            <!-- <div v-if="error" class="message-error-modal">
                <ul v-if="Array.isArray(error)" class="mb-0">
                    <li v-for="(e, index) in error" :key="index">
                        {{ e }}
                    </li>
                </ul>
                <div class="message-error-modal" v-else>{{ error }}</div>
            </div> -->
            <v-divider class="line-hr-footer"></v-divider>
            <v-card-actions class="actions-msg-btn-modal">
                <v-spacer></v-spacer>
                <v-btn variant="outlined" @click="cancelUpdate"> Cancel </v-btn>
                <v-btn
                    color="#1e0c8c"
                    type="submit"
                    variant="elevated"
                    @click="funUpdateGabarit"
                >
                    Save Changes
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import Editor from "@tinymce/tinymce-vue";
import { mapActions, mapGetters } from "vuex";

export default {
    components: {
        Editor,
    },
    props: {
        item: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            updateDialog: false,
            gabarit: {
                title: "",
                content: "",
                placeholders: [],
            },
        };
    },
    computed: {
        ...mapGetters(["getListPlaceholders", "getOneGabarit"]),
        computedDataTinymce() {
            return {
                toolbar_mode: "sliding",
                height: "700px",
                ai_request: (request, respondWith) =>
                    respondWith.string(() =>
                        Promise.reject("See docs to implement AI Assistant")
                    ),
            };
        },
    },
    mounted() {
        this.getAllPlaceholders();
    },
    methods: {
        ...mapActions([
            "getAllPlaceholders",
            "updateGabarit",
            "getGabaritById",
        ]),
        async showUpdateDialog() {
            const response = await this.getGabaritById(this.item);
            if (response) {
                this.gabarit = { ...this.getOneGabarit };
                this.gabarit.placeholders = [];
                this.updateDialog = true;
            }
        },
        appendContent(placeholder) {
            const editor = tinymce.get("myEditor");
            if (editor) {
                const selection = editor.selection;
                const content = selection.getContent();
                const node = editor.selection.getNode();
                const isTextNode = node && node.nodeType === 3;

                if (isTextNode) {
                    selection.setContent(
                        content + ` ${placeholder.identification}`
                    );
                } else {
                    selection.setContent(`${placeholder.identification} `);
                }
            }
        },
        extractCodesFromString(text) {
            const regex = /\[(.*?)\]/g;
            const matches = [];
            let match;
            while ((match = regex.exec(text)) !== null) {
                matches.push(match[1]);
            }

            return matches;
        },
        async funUpdateGabarit() {
            const placeholders = this.extractCodesFromString(
                this.gabarit.content
            );

            placeholders.forEach((element) => {
                const item = this.getListPlaceholders.find(
                    (item) => item.identification === `[${element}]`
                );
                if (item) {
                    this.gabarit.placeholders.push(item.id);
                }
            });
            const response = this.updateGabarit(this.gabarit);
            if (response) {
                this.cancelUpdate();
            }
        },
        cancelUpdate() {
            this.gabarit = {
                title: "",
                content: "",
                placeholders: [],
            };
            this.updateDialog = false;
        },
    },
};
</script>

<style scoped>
.page-container {
    background-color: #f2f2f2;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    padding: 20px;
}
.page-content {
    margin-top: 64px;
    transition: margin-left 0.3s;
}
.content-shifted {
    margin-left: 130px;
}
</style>
<style lang="scss">
.v-dialog.custom-vuetify-dialog-add.v-dialog--active.v-dialog--persistent.v-dialog--scrollable {
    top: 0 !important;
    position: absolute !important;
}

.v-overlay__content.custom-vuetify-dialog-add {
    top: 0 !important;
}
.title-modal {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: blue !important;
    .label-header {
        font-weight: 700;
        font-size: 20px;
        line-height: 24px;
        font-family: "Montserrat", sans-serif;
        margin-bottom: 0px;
        color: blue !important;
        text-transform: uppercase;
    }
    .btn-close-header {
        border-radius: 8px;
        height: 30px !important;
        width: 30px !important;
    }
}

.filed-class {
    border: 1px solid #6c757d4d;
    background-color: #ebebeb5c;
    border-radius: 4px;
    position: relative;
    padding: 5px;
    width: 211px;
    margin: 4px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    font-family: "Montserrat", sans-serif;
    color: #2a2a2a;
    margin-right: 0px;
}

.icon-field {
    position: absolute;
    left: 6px;
    width: 25px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}
.icon-copy {
    position: absolute;
    right: 6px;
    width: 25px;
    top: 30%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}
.value-field {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    margin-left: 25px;
}
</style>
