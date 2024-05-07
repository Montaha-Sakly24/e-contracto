<template>
    <div class="signature-wrapper">
        <canvas ref="signaturePad" class="signature-container"></canvas>
        <div class="signature-buttons">
            <button @click="clearSignature" class="button-clear">Clear</button>
            <button @click="saveSignature" class="button-save">Save</button>
        </div>
    </div>
</template>

<script>
import SignaturePad from "signature_pad";

export default {
    mounted() {
        this.signaturePad = new SignaturePad(this.$refs.signaturePad);
    },
    methods: {
        clearSignature() {
            this.signaturePad.clear();
        },
        saveSignature() {
            const signatureData = this.signaturePad.toDataURL();
            this.$emit("signatureSaved", signatureData);
        },
    },
};
</script>
<style scoped>
.signature-wrapper {
    padding: 10px;
    background-color: white;
    border: 1px solid #ccc;
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 250px;
}
.signature-container {
    width: 100%;
    height: 120px;
}

.signature-buttons {
    width: 100%;
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.button-clear,
.button-save {
    flex: 1;
    margin: 0 5px;
}
</style>
