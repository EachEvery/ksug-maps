<template>
    <button
        @click="emitClickEvent"
        class="focus:outline-none shrink-when-active select-none"
    >
        <slot />
    </button>
</template>
<script>
export default {
    methods: {
        emitClickEvent(e) {
            this.$emit("click", e);
        }
    }
};
</script>
