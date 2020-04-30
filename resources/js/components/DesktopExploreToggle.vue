<template>
    <div
        style="width: 100vh; transform: rotate(90deg); left: 0; transform-origin: left top;"
        class="bg-white absolute flex justify-between top-0 left-0 border-b shadow  px-16 py-3  transition items-center cursor-pointer"
        v-on="$listeners"
    >
        <div class="flex items-center" :class="{ 'opacity-25': open }">
            <up-arrow
                class="w-10 h-10 transition"
                style="transform: rotate(-90deg)"
                :style="{ transform: `rotate(${open ? '0' : '-180'}deg)` }"
            />

            <h1
                class="font-display font-black text-5xl ml-4"
                style="transform: rotate(180deg)"
            >
                EXPLORE
            </h1>
        </div>
        <span
            :class="{ 'opacity-25': open }"
            class="text-xs uppercase cursor-pointer font-mono"
            style="transform: rotate(180deg)"
        >
            explore tours &amp; stories
        </span>
    </div>
</template>

<script>
import upArrow from "./UpArrow";

export default {
    components: {
        upArrow
    },
    props: {
        open: Boolean
    }
};
</script>
