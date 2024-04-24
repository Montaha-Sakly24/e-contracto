<template>
    <div>
        <canvas ref="signaturePad" width="400" height="200"></canvas>
        <button @click="clearSignature">Clear</button>
        <button @click="saveSignature">Save</button>
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
