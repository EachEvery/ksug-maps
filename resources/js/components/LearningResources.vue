<template>
    <div
        v-click-outside="goBack"
        v-esc="goBack"
        class="fixed inset-0 md:right-0 md:left-auto transition md:w-4/12 md:min-w-84 xl:w-5/12 overflow-auto pt-64 md:pt-0 md:max-w-base max-w-full"
    >
        <header-40>Lesson Plans</header-40>

        <div v-for="lesson in lessons" :key="lesson.id"></div>

        <header-40>Videos</header-40>

        <div v-for="video in videos" :key="video.id">
            <iframe
                width="560"
                height="315"
                :src="`//www.youtube.com/embed/${getYoutubeId(video.url)}`"
                frameborder="0"
                allowfullscreen
            />
        </div>

        <header-40>Links</header-40>

        <div class="links">
            <a
                v-for="link in links"
                :key="link.id"
                :href="link.url"
                target="_blank"
                >{{ link.text }}</a
            >
        </div>
    </div>
</template>

<style>
.links {
    column-gap: 20px;
    column-count: 3;
}
</style>

<script>
import pageSection from "./PageSection";

export default {
    components: {
        pageSection
    },
    methods: {
        goBack() {
            this.$router.push("/");
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
    }
};
</script>
