<template>
    <div class="page-container">
        <v-card>
            <v-card-title class="d-flex align-center card-title">
                Gabarits List
                <v-spacer></v-spacer>
                <create-gabarit></create-gabarit>
            </v-card-title>
            <v-data-table :headers="headers" :items="getListGabarit">
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>
                        <td>
                            <div class="action-buttons">
                                <v-btn
                                    color="blue"
                                    variant="outlined"
                                    @click="viewGabarit(item)"
                                >
                                    <font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'eye']"
                                    />View
                                </v-btn>
                                <update-gabarit
                                    :item="item.id"
                                ></update-gabarit>

                                <v-btn
                                    color="red"
                                    variant="outlined"
                                    @click="deleteGabarit(item)"
                                >
                                    <font-awesome-icon
                                        class="pr-2"
                                        :icon="['fas', 'trash']"
                                    />Delete
                                </v-btn>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog
            v-model="viewDialog"
            persistent
            :scrollable="false"
            :hide-overlay="true"
            no-click-animation
            width="900"
            content-class="custom-vuetify-dialog-add"
        >
            <v-card>
                <v-card-title class="text-h6 title-modal">
                    <v-label class="label-header">View Gabarit </v-label>
                    <v-btn
                        class="btn-close-header"
                        icon
                        @click="cancelView"
                        title="Close"
                        color="blue"
                    >
                        <font-awesome-icon :icon="['fas', 'xmark']" />
                    </v-btn>
                </v-card-title>
                <v-divider class="line-hr"></v-divider>
                <v-card-text class="body-card" v-if="getOneGabarit">
                    <p><strong>Title : </strong> {{ getOneGabarit.title }}</p>
                    <p><strong>Content : </strong></p>
                    <div v-html="getOneGabarit.content"></div>
                </v-card-text>
                <v-divider class="line-hr-footer"></v-divider>
                <v-card-actions class="actions-msg-btn-modal">
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import CreateGabarit from "../Components/CreateGabarit.vue";
import UpdateGabarit from "../Components/UpdateGabarit.vue";

import axios from "axios";
import { mapActions, mapGetters } from "vuex";

export default {
    components: {
        CreateGabarit,
        UpdateGabarit,
    },

    data() {
        return {
            gabarit: {},
            gabarits: [],
            viewDialog: false,
            newGabarit: {
                title: "",
                content: "",
            },
            headers: [
                {
                    title: "#",
                    align: "start",
                    key: "id",
                },
                { title: "Title", key: "title", sortable: false },

                {
                    title: "Actions",
                    key: "actions",
                    align: "center",
                    sortable: false,
                },
            ],
        };
    },
    mounted() {
        this.getAllGabarits();
    },
    computed: {
        ...mapGetters(["getListGabarit", "getOneGabarit"]),
    },
    methods: {
        ...mapActions(["getAllGabarits", "getGabaritById"]),

        async viewGabarit(gabarit) {
            const response = await this.getGabaritById(gabarit.id);
            if (response) {
                this.viewDialog = true;
            } else {
                console.error("Error viewing gabarit:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing gabarit ",
                });
            }
        },

        cancelView() {
            this.viewDialog = false;
        },

        deleteGabarit(gabarit) {
            swal.fire({
                title: "Are you sure?",
                text: gabarit.title + " will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .delete("/api/gabarits/" + gabarit.id)
                        .then(() => {
                            toast.fire({
                                icon: "success",
                                title:
                                    gabarit.title +
                                    " has been deleted sucessfully",
                            });
                            this.getAllGabarits();
                        })
                        .catch(() => {
                            toast.fire({
                                icon: "error",
                                title:
                                    gabarit.title +
                                    " was unable to be remove, try again later",
                            });
                        });
                }
            });
        },
    },
};
</script>

<style lang="scss" scoped>
.page-container {
    background-color: #f2f2f2;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    padding: 20px;
}
.card-component {
    width: 1550px;
    height: 780px;
}
.card-title {
    font-weight: bold;
    text-transform: uppercase;
    color: #1a1ab3;
    font-family: serif;
}
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}
</style>
