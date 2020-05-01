<template>
    <div
        v-esc="closeStory"
        v-click-outside="handleClickOutside"
        class="fixed inset-0 md:right-0 md:left-auto bg-white transition pt-8 md:pt-0 md:w-84 xl:w-5/12 overflow-auto shadow-lg flex-grow-0 story md:min-w-24rem"
        style="max-width: 45rem;"
        :style="{
            'background-color': story.color,
            '--currentColor': story.color,
            '--transparentColor': transparentColor
        }"
    >
        <clickable
            class="absolute top-0 py-2 bg-black p-4 w-full text-white font-mono text-center font-bold w-full"
            @click="back"
            v-if="tourActive"
        >
            &larr; BACK TO TOUR
        </clickable>

        <div
            class="xl:px-24 px-4 md:px-8 pb-48"
            :class="{ 'mt-8': tourActive }"
        >
            <div class="pt-5 mb-12 md:mb-24 lg:pt-12">
                <h1
                    class="font-display font-black text-5xl lg:text-5xl xl:text-8xl uppercase tracking-loose leading-none flex-grow pr-24 mb-5 md:mb-3"
                >
                    {{ story.place.name }}
                </h1>

                <h2 class="font-sans text-black text-lg md:text-md mb-2">
                    {{ firstLast }}
                </h2>
                <h3
                    class="font-mono text-base tracking-tight mb-4 font-bold uppercase md:text-2xs lg:text-base"
                >
                    {{ story.role }} &middot; {{ story.day }}
                </h3>

                <div v-if="isAdmin" class="flex mt-3">
                    <a
                        :href="story.admin_url"
                        target="_blank"
                        class="underline inline-block mr-2"
                        >Edit Story</a
                    >

                    <toggle-story-featured :init-story="story" />
                </div>
            </div>

            <quote-icon class="h-6 w-6 mb-6 text-black" />

            <p
                v-html="story.content"
                class="leading-loose relative overflow-hidden transition"
                :style="contentStyle"
                :class="contentClass"
            ></p>

            <clickable
                class="pb-4 border-b border-dotted border-black flex w-full"
                :class="{ 'mt-12': showingFull }"
                style="border-radius: 0"
                @click="handleReadFullButtonClick"
            >
                <span
                    class="uppercase text-left font-mono font-black flex-grow"
                    >{{
                        showingFull ? "Show Less" : "Read Audio Transcript"
                    }}</span
                >

                <chevron-up-icon
                    class="w-5 h-5 transition"
                    :style="
                        `transform: rotate(${showingFull ? '0' : '180'}deg)`
                    "
                ></chevron-up-icon>
            </clickable>

            <a
                v-if="story.full_story_link && !tourActive"
                :href="story.full_story_link"
                class="font-bold w-full h-16 flex justify-center bg-white mt-16"
                target="_blank"
            >
                <span
                    class="self-center text-sm xl:text-base font-mono font-bold"
                    >VISIT FULL ORAL HISTORY →</span
                >
            </a>

            <router-link
                class="bg-black font-mono text-white uppercase h-12 flex justify-center items-center mt-24 cursor-pointed mb-16"
                to="/about#give-feedback"
                >Give Us Your Feedback</router-link
            >

            <clickable
                @click="closeStory"
                v-if="!tourActive"
                class="fixed top-0 right-0 rounded-full mr-5 mt-5 z-10"
            >
                <close-icon class="w-8 h-8 lg:w-5 lg:h-5 text-black" />
            </clickable>
        </div>

        <audio-player
            ref="audioPlayer"
            class="fixed bottom-0 right-0 md:w-84 xl:w-5/12 transition"
            style="max-width: 45rem"
            :style="audioPlayerStyle"
            :src="audioSource"
            :label="story.subject"
        />
    </div>
</template>
<style lang="scss" scoped>
.gradient {
    &::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 10rem;
        background: linear-gradient(
            to bottom,
            var(--transparentColor),
            var(--currentColor) 75%
        );
    }
}
</style>
<script>
import audioPlayer from "./AudioPlayer";
import closeIcon from "./CloseIcon";
import clickable from "./Clickable";
import quoteIcon from "./QuoteIcon";
import chevronUpIcon from "./ChevronUpIcon";
import toggleStoryFeatured from "./ToggleStoryFeatured";
import handleBack from "../mixins/handleBack";

import { mapState, mapGetters } from "vuex";

import $ from "jquery";

export default {
    mixins: [handleBack],

    metaInfo() {
        return {
            title: this.story.subject,
            titleTemplate: "%s | Mapping May 4"
        };
    },

    data() {
        return {
            state: "default",
            lastState: "default"
        };
    },

    components: {
        audioPlayer,
        closeIcon,
        clickable,
        quoteIcon,
        chevronUpIcon,
        toggleStoryFeatured
    },

    methods: {
        handleReadFullButtonClick() {
            this.state = this.state === "showAll" ? this.lastState : "showAll";
        },

        handleClickOutside(e) {
            if (e.target.tagName !== "svg" && e.target.tagName !== "path") {
                this.closeStory();
            }
        },

        closeStory() {
            this.state = "default";

            setTimeout(() => {
                this.$refs.audioPlayer.controlAudio("pause");
                this.back();
            }, 310);
        }
    },

    computed: {
        ...mapState(["stories", "places", "tourActive"]),

        ...mapGetters(["isAdmin"]),

        audioSource({ story }) {
            return story.audio_media.length > 0
                ? story.audio_media[0].url
                : story.audio;
        },

        firstLast({ story }) {
            let parts = story.subject.split(",");

            if (parts.length < 2) {
                return story.subject;
            } else {
                return `${parts[1].trim()} ${parts[0].trim()}`;
            }
        },

        transparentColor({ story }) {
            let hex = story.color;
            let c = hex.substring(1).split("");

            if (c.length == 3) {
                c = [c[0], c[0], c[1], c[1], c[2], c[2]];
            }

            c = "0x" + c.join("");

            return (
                "rgba(" +
                [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(",") +
                ",0)"
            );
        },

        showingFull({ state }) {
            return state === "showAll";
        },

        contentStyle({ showingFull }) {
            return {
                "max-height": showingFull ? "none" : "30rem"
            };
        },

        contentClass({ showingFull }) {
            return {
                gradient: !showingFull
            };
        },

        audioPlayerStyle({ story, state }) {
            let showPlayer = ["showAll", "showPlayer"].includes(state);

            return {
                transform: `translateY(${showPlayer ? "0" : "100%"})`,
                opacity: showPlayer ? "1" : "0"
            };
        },
        location({ story }) {
            return story.place;
        },

        defaultBackRoute({ location }) {
            return `/places/${location.slug}/`;
        },

        story({ stories }) {
            return stories.find(s => +s.id === +this.$route.params.story);
        },

        locationHtml({ story }) {
            let words = story.place.name.split(" ");

            if (words.length === 2) {
                return `${words[0]}<br />${words[1]}`;
            }

            return story.place.name;
        }
    },
    watch: {
        state(val, old) {
            this.lastState = old;
        }
    },
    mounted() {
        console.log(this.story);

        if (!this.tourActive) {
            this.$store.commit("setMapCenter", [
                +this.location.lat,
                +this.location.long,
                16
            ]);
        }

        setTimeout(() => {
            this.state = "showPlayer";
        }, 500);
    }
};
</script>
