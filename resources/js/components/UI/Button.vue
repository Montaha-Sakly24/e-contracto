<template>
    <v-btn
        :class="{
            'bg-active': loading,
            'active:bg-active': loading,
            'dark:bg-cc-10': loading,
            'text-white': loading,
            'dark:text-emerald-200': loading,
            'hover:bg-emerald-600': !loading,
            'dark:hover:bg-cc-12': !loading,
            'cursor-pointer': !disabled,
            'transition duration-150': !disabled,
        }"
        :disabled="disabled"
    >
        <template v-if="!slotted">
            <v-icon
                v-if="loading"
                class="inline w-4 h-4 mr-3 text-white animate-spin fill-gray-400"
            >
                mdi-loading
            </v-icon>
            {{ loading ? loadingTitle || "Processing..." : title }}
        </template>
        <slot v-else />
    </v-btn>
</template>

<script>
export default {
    props: {
        loading: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        title: {
            type: String,
            default: "Submit",
        },
        loadingTitle: {
            type: String,
            default: "",
        },
        slotted: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
