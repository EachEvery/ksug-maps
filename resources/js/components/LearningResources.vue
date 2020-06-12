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

            <div v-if="lessons.length" class="mb-12 mt-5">
                <h1 class="text-lg lg:text-3xl font-display uppercase">
                    Lesson Plans
                </h1>

                <div>
                    <div
                        v-for="lesson in lessons"
                        :key="lesson.id"
                        class="mt-8"
                    >
                        <h3 class="uppercase font-display text-base lg:text-lg">
                            {{ lesson.label }}
                        </h3>

                        <div
                            class="max-w-sm text-xs mt-2"
                            v-html="lesson.content"
                        ></div>

                        <a
                            :href="lesson.url"
                            class="w-full bg-white flex items-center h-12 justify-center mt-4"
                        >
                            <span class="font-mono uppercase"
                                >Download Plan</span
                            >
                        </a>
                    </div>
                </div>
            </div>

            <div v-if="videos.length" class="mb-12">
                <h1 class="text-lg lg:text-3xl font-display uppercase">
                    Videos
                </h1>

                <div
                    v-for="video in videos"
                    :key="video.id"
                    class="flex flex-col mb-16"
                >
                    <div class="responsive-iframe mb-3">
                        <iframe
                            :src="
                                `//www.youtube.com/embed/${getYoutubeId(
                                    video.url
                                )}`
                            "
                            frameborder="0"
                            allowfullscreen
                        />
                    </div>

                    <h3
                        class="uppercase font-bold font-display text-base lg:text-lg"
                    >
                        {{ video.label }}
                    </h3>

                    <div
                        class="max-w-sm text-xs mt-2"
                        v-html="video.content"
                    ></div>
                </div>
            </div>

            <div v-if="links.length" class="mb-12">
                <h1 class="text-lg lg:text-3xl font-display uppercase">
                    Helpful Links
                </h1>

                <div
                    class="flex flex-col links font-mono font-bold text-sm mt-6"
                >
                    <a
                        v-for="link in links"
                        :key="link.id"
                        :href="link.url"
                        class="mb-3 hover:opacity-50"
                        target="_blank"
                        >{{ link.label }} &rarr;</a
                    >
                </div>
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
