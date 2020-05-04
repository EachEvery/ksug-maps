<template>
    <ul
        class="flex flex-col text-center font-display text-lg md:text-3xl max-w-xs mx-auto leading-tight mt-8 md:mt-12"
    >
        <li class="mb-3 md:mb-6 shrink-when-active" @click="$emit('close')">
            <router-link to="/">MAP</router-link>
        </li>

        <li class="mb-4 md:mb-6 shrink-when-active">
            <router-link to="/about#about-may-4">ABOUT MAY 4</router-link>
        </li>
        <li class="mb-4 md:mb-6 shrink-when-active">
            <router-link to="/explore">EXPLORE TOURS &amp; STORIES</router-link>
        </li>
        <li class="mb-4 md:mb-6 shrink-when-active">
            <router-link to="/about/#about-the-project"
                >ABOUT THIS PROJECT</router-link
            >
        </li>
        <li class="mb-4 md:mb-6 shrink-when-active">
            <router-link to="/about#how-to-use"
                >HOW TO USE THIS SITE</router-link
            >
        </li>
        <li class="mb-4 md:mb-6 shrink-when-active">
            <router-link to="/about#about-us">ABOUT US</router-link>
        </li>
        <li class="mb-4 md:mb-6 shrink-when-active">
            <router-link to="/about#give-feedback">GIVE FEEDBACK</router-link>
        </li>

        <div
            class="border-b border-black block h-1 w-full opacity-25 mb-4 md:mb-12 mt-5"
        ></div>

        <li class="mb-10 shrink-when-active">
            <a
                href="https://mailchi.mp/531c4843f76c/mappingmay4"
                target="_blank"
                >SIGN UP FOR UPDATES</a
            >
        </li>
    </ul>
</template>
<script>
export default {
    props: {
        isLocation: Boolean
    },
    computed: {
        isMap({ isLocation, $route }) {
            return isLocation || $route.path === "/";
        }
    }
};
</script>
