<template>
    <div class="flex justify-between items-center mb-3">
        <h1 class="text-2xl md:text-3xl font-display uppercase">
            <slot />
        </h1>

        <portal-target :name="portalName"></portal-target>
    </div>
</template>
<script>
export default {
    props: {
        portalName: String
    }
};
</script>
