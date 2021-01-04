<template>
    <div
        style="width: 100vh; transform: rotate(90deg); left: 0; transform-origin: left top; min-width: 576px;"
        class="bg-white absolute flex justify-between top-0 left-0 border-b shadow  px-16 py-3  transition items-center cursor-pointer"
        v-on="$listeners"
    >
        <div class="flex items-center" :class="{ 'opacity-25': open }">
            <div
                class="inline-flex  justify-center items-center w-10 h-10 rounded-full bg-black relative"
            >
                <up-arrow
                    class="w-6 h-6 transition text-white"
                    style="transform: rotate(-90deg)"
                    :style="{ transform: `rotate(${open ? '0' : '-180'}deg)` }"
                />

                <div
                    class="absolute inset-0 flex items-center justify-center"
                    style="transform:rotate(-90deg) translateY(2.5rem)"
                >
                    <span class="font-mono uppercase">{{
                        open ? "Close" : "Open"
                    }}</span>
                </div>
            </div>

            <h1
                class="uppercase font-display text-2xl leading-none self-center select-none ml-16 "
                style="transform: rotate(180deg)"
            >
                Explore Tours and Stories
            </h1>
        </div>
        <!-- <span
            :class="{ 'opacity-25': open }"
            class="text-xs uppercase cursor-pointer font-mono"
            style="transform: rotate(180deg)"
        >
            explore tours &amp; stories
        </span> -->
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
