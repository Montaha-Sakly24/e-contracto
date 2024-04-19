<template>
    <v-carousel
        :interval="0"
        hide-delimiter-background
        show-arrows
        :continuous="false"
    >
        <v-carousel-item
            v-for="(modelChunk, index) in chunkedModels"
            :key="index"
            cover
        >
            <v-row>
                <v-col
                    cols="12"
                    sm="4"
                    v-for="model in modelChunk"
                    :key="model.id"
                >
                    <v-hover v-slot="{ isHovering, props }">
                        <v-card class="mx-auto fixed-size" v-bind="props">
                            <v-img
                                height="180px"
                                src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
                                cover
                            ></v-img>
                            <v-card-title>{{ model.title }}</v-card-title>
                            <v-card-text>{{ model.description }}</v-card-text>
                            <v-overlay
                                :model-value="isHovering"
                                class="align-center justify-center"
                                scrim="#036358"
                                contained
                            >
                                <div class="mt-15">
                                    <v-btn
                                        variant="flat"
                                        @click="viewModel(model)"
                                        >See more info</v-btn
                                    >
                                </div>
                            </v-overlay>
                        </v-card>
                    </v-hover>
                </v-col>
            </v-row>
        </v-carousel-item>
    </v-carousel>
    <v-dialog v-model="viewDialog" transition="dialog-bottom-transition">
        <v-card class="dialog-card" variant="flat">
            <v-icon
                @click="viewDialog = false"
                style="position: absolute; top: 10px; right: 10px"
                >mdi-close
            </v-icon>
            <v-card-text class="a4-size" elevation="2">
                {{ model.content }}
            </v-card-text>
            <v-btn>Generate</v-btn>
        </v-card>
    </v-dialog>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            viewDialog: false,
            model: {},
            models: [],
        };
    },
    mounted() {
        this.fetchModels();
    },
    computed: {
        chunkedModels() {
            const chunkSize = 6; // Items per slide
            return this.models.reduce((resultArray, item, index) => {
                const chunkIndex = Math.floor(index / chunkSize);

                if (!resultArray[chunkIndex]) {
                    resultArray[chunkIndex] = []; // Start a new chunk
                }

                resultArray[chunkIndex].push(item);

                return resultArray;
            }, []);
        },
    },
    methods: {
        async fetchModels() {
            try {
                const response = await axios.get("/api/models");
                this.models = response.data;
            } catch (error) {
                console.error("Error fetching models:", error);
            }
        },
        async viewModel(model) {
            try {
                const response = await axios.get("/api/models/" + model.id);
                this.model = response.data;
                this.viewDialog = true;
            } catch (error) {
                console.error("Error viewing model:", error);
                toast.fire({
                    icon: "error",
                    title: "Error viewing model ",
                });
            }
        },
    },
};
</script>
<style scoped>
.fixed-size {
    height: 385px;
    width: 325px;
    overflow: auto;
}
.a4-size {
    width: 210mm;
    height: 297mm;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>
