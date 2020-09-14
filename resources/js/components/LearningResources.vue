<template>
    <div
        class="fixed inset-0 md:right-0 md:left-auto transition md:w-4/12 md:min-w-84 xl:w-5/12 overflow-auto pt-32 md:pt-0 md:max-w-base max-w-full bg-green pt-12"
        v-click-outside="goBack"
    >
        <page-section>
            <h1 class="text-4xl lg:text-8xl font-display uppercase">
                Resources
            </h1>

            <p v-if="resources.length === 0">
                No resources available right now. Check back later!
            </p>

            <div
                v-for="(resource, i) in resources"
                :key="resource.id"
                class="mb-16"
            >
                <div
                    class="max-w-sm text-xs mt-2 trix"
                    v-html="resource.content"
                ></div>

                <div v-if="i !== 0" class="border-t py-10"></div>
            </div>
        </page-section>

        <clickable
            @click="goBack"
            class="hidden md:block md:fixed top-0 right-0 rounded-full mr-5 mt-5 z-10"
        >
            <close-icon class="w-8 h-8 lg:w-5 lg:h-5 text-black" />
        </clickable>
    </div>
</template>

<style></style>

<script>
import pageSection from "./PageSection";
import learningResources from "../mixins/learningResources";
import header40 from "./Header40";
import header25 from "./Header25";
import clickable from "./Clickable";
import goBack from "../mixins/handleBack";
import closeIcon from "./CloseIcon";

export default {
    mixins: [learningResources],

    components: {
        pageSection,
        header40,
        header25,
        clickable,
        closeIcon
    },

    data() {
        return {
            canClickOutside: false
        };
    },

    methods: {
        goBack() {
            if (this.canClickOutside) {
            }
            this.$router.push("/");
        },
        setCanClickOutside() {
            setTimeout(() => {
                this.canClickOutside = true;
            }, 300);
        },
        getYoutubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);

            return match && match[2].length === 11 ? match[2] : null;
        }
    },

    computed: {
        videos({ resources }) {
            return resources.filter(r => r.type === "Video");
        },
        lessons({ resources }) {
            return resources.filter(r => r.type === "Lesson Plan");
        },
        links({ resources }) {
            return resources.filter(r => r.type === "Link");
        }
    },

    mounted() {
        this.setCanClickOutside();
    }
};
</script>
