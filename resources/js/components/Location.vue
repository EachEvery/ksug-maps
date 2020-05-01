<template>
    <div
        v-click-outside="goBack"
        v-esc="goBack"
        :class="containerClass"
        class="fixed inset-0 md:right-0 md:left-auto bg-tan-100 transition pt-8 md:pt-0 md:w-84 xl:w-5/12 md:overflow-auto md:min-w-24rem"
        ref="container"
        style="max-width: 45rem; "
    >
        <router-link
            :to="chevronLink"
            class="absolute top-0 inset-x-0 h-32"
        ></router-link>

        <div class="px-5 xl:px-8 md:mt-5">
            <h4 class="font-mono text-md font-bold uppercase mb-3">
                Location &middot; {{ storyCount }}
            </h4>

            <div class="mb-3">
                <router-link
                    :to="chevronLink"
                    class="flex cursor-pointer md:cursor-default"
                >
                    <h1
                        class="font-display font-black text-3xl lg:text-5xl xl:text-8xl uppercase tracking-loose leading-none flex-grow pr-10 md:h-auto"
                        :class="{
                            'text-2xl': location.name.length > 21,
                            'text-4xl': location.name.length < 21
                        }"
                    >
                        {{ location.name }}
                    </h1>

                    <chevron-up-icon
                        class="w-8 h-8 mt-1 text-black transition md:hidden"
                        :style="chevronStyle"
                    />
                </router-link>

                <a
                    v-if="isAdmin"
                    :href="location.admin_url"
                    target="_blank"
                    class="underline inline-block mt-3"
                    >Edit Place</a
                >
            </div>
        </div>

        <div v-if="location.photos.length === 1">
            <clickable
                @click="handleImageClick(photo.url)"
                v-for="(photo, i) in location.photos"
                :key="i"
                class="w-full"
            >
                <img
                    :src="photo.url"
                    :alt="photo.custom_properties.alt_text"
                    class="transition w-full"
                    style="height: 25rem; object-fit: cover;"
                    :class="getImageClass(photo)"
                    @load="setLoaded(photo)"
                />

                <p
                    class="font-mono mt-4 text-xs md:text-base max-w-md text-left mx-8"
                >
                    {{ photo.custom_properties.photo_caption }}
                </p>

                <portal to="end-of-document">
                    <a
                        :href="photo.url"
                        title
                        class="lightbox"
                        v-if="photo.url !== null"
                    >
                        <img
                            :src="photo.url"
                            :alt="photo.custom_properties.alt_text"
                        />
                    </a>
                </portal>
            </clickable>
        </div>

        <div
            v-if="location.photos.length > 1"
            class="md:px-5 xl:px-8 -mb-8 mt-8"
        >
            <portal-target name="photo-arrows"></portal-target>
        </div>

        <scroll-container
            buttons-portal="photo-arrows"
            v-if="location.photos.length > 1"
            class="md:px-5 xl:px-8 mb-8 mt-6"
        >
            <clickable
                @click="handleImageClick(photo.url)"
                v-for="(photo, i) in location.photos"
                :key="i"
                class="text-left mr-4 flex-shrink-0"
                style="height: 20rem; "
            >
                <img
                    :src="photo.url"
                    :alt="photo.custom_properties.alt_text"
                    class="transition"
                    style="height: 13rem; "
                    :class="getImageClass(photo)"
                    @load="setLoaded(photo)"
                />

                <p class="font-mono max-w-xs mt-2 text-xs">
                    {{ photo.custom_properties.photo_caption }}
                </p>

                <portal to="end-of-document">
                    <a
                        :href="photo.url"
                        title
                        class="lightbox"
                        v-if="photo.url !== null"
                    >
                        <img
                            :src="photo.url"
                            :alt="photo.custom_properties.alt_text"
                        />
                    </a>
                </portal>
            </clickable>
        </scroll-container>

        <scroll-container
            class="md:flex-wrap xl:grid md:px-5 xl:px-8 pb-6 grid-columns-2 grid-gap grid-gap-4 md:pb-24 mb-24 mt-12 md:overflow-hidden"
        >
            <story-card
                :story="story"
                v-for="(story, i) in location.stories"
                :key="story.id"
                :class="{ 'xl:translate-y-5': isEven(i) }"
                class="mr-4 md:mr-0 w-72 md:w-full h-48vh md:mb-5 xl:mb-2 flex-retain"
                :style="{ color: story.color }"
                style="max-height: 25rem"
            />
        </scroll-container>

        <div
            class="xl:px-24 px-8 py-24 relative bg-black text-white"
            v-if="location.approved_comments.length > 0"
        >
            <h3 class="font-display uppercase text-3xl mb-12">
                Stories &amp; Comments
            </h3>

            <div
                v-for="comment in location.approved_comments"
                :key="comment.id"
                class="my-10"
                :id="`comment-${comment.id}`"
            >
                <h4 class="text-xl uppercase font-medium mb-3 font-display">
                    {{ comment.author }}
                </h4>

                <portal to="end-of-document" v-if="comment.media_is_image">
                    <a :href="comment.media_url" class="lightbox">
                        <img :src="comment.media_url" />
                    </a>
                </portal>

                <span
                    class="whitespace-pre-line leading-normal font-mono"
                    v-html="comment.text"
                ></span>

                <clickable
                    @click="handleImageClick(comment.media_url)"
                    class="w-full mt-4"
                >
                    <img
                        v-if="comment.media_is_image"
                        :src="comment.media_url"
                        class="border border-white max-w-md"
                    />
                </clickable>

                <video
                    v-if="!comment.media_is_image && comment.has_media"
                    controls
                    class="max-w-md p-2 border border-white w-full"
                >
                    <source
                        :src="comment.media_url"
                        :type="comment.comment_media.mime_type"
                    />
                    Your browser does not support the video file type
                    {{ comment.comment_media.mime_type }}.
                </video>

                <span class="block mt-4 opacity-75 font-mono text-xs">{{
                    comment.frontend_date
                }}</span>
            </div>
        </div>
        <div
            class="xl:px-24 px-8 pt-12 border-t border-dotted pb-48 relative bg-white"
        >
            <h3
                class="font-display uppercase text-2xl mb-8"
                style="font-weight: 500;"
            >
                Share Your Story or Reflection
            </h3>

            <comment-form
                @comment-created="handleCommentCreated"
                :post-url="`/places/${location.slug}/comments`"
            />

            <div
                class="absolute inset-0 bg-white transition xl:px-24 pt-24 px-8 flex flex-col"
                :style="confirmationStyle"
            >
                <h3 class="font-display uppercase text-2xl mb-8">
                    Thanks for Your Story
                </h3>

                <p class="leading-normal gradient">
                    Your submission is under review and will show up under the
                    comments for this story once it is approved.
                </p>

                <clickable
                    class="w-full mt-10 text-center py-3 px-2 border border-black uppercase"
                    @click="addAnotherComment"
                    >Add Another Comment</clickable
                >
            </div>

            <div class="bg-gray-300 p-6 mt-12 font-mono md:p-12">
                <div class="flex items-center mb-4">
                    <img src="/images/voicemail.png" class="h-5 mr-2" />
                    <h3 class="uppercase">Leave a Voicemail</h3>
                </div>
                <p class="leading-normal text-sm">
                    Tell your story by leaving a voicemail with your name and
                    message at the number below:
                    <br />
                    <br />
                    <a
                        :href="`tel:${phoneNumber}`"
                        class="text-black underline text-md"
                        >{{ phoneNumber }}</a
                    >
                </p>
            </div>

            <router-link
                class="bg-black font-mono text-white uppercase h-12 flex justify-center items-center mt-24 cursor-pointed mb-16"
                to="/about#give-feedback"
                >Give Us Your Feedback</router-link
            >

            <clickable
                @click="back"
                class="hidden md:block fixed top-0 right-0 rounded-full mr-5 mt-5 z-10"
            >
                <close-icon class="w-8 h-8 lg:w-5 lg:h-5 text-black" />
            </clickable>
        </div>
    </div>
</template>

<script>
import chevronUpIcon from "./ChevronUpIcon";
import storyCard from "./StoryCard";
import commentForm from "./CommentForm";
import clickable from "./Clickable";
import scrollContainer from "./ScrollContainer";
import handleBack from "../mixins/handleBack";
import closeIcon from "./CloseIcon";

import { mapState, mapGetters } from "vuex";
import windowDimensions from "../mixins/windowDimensions";

export default {
    mixins: [handleBack, windowDimensions],

    metaInfo() {
        return {
            title: this.location.name,
            titleTemplate: "%s | Mapping May 4"
        };
    },

    components: {
        chevronUpIcon,
        storyCard,
        commentForm,
        clickable,
        scrollContainer,
        closeIcon
    },

    data() {
        return {
            state: "default",
            scrollOverflow: !this.isPreview,
            loadedUrls: []
        };
    },

    methods: {
        getImageClass(url) {
            let isLoaded = this.photoIsLoaded(url);

            return {
                "opacity-0": !isLoaded,
                "translate-y-1": !isLoaded
            };
        },

        photoIsLoaded(url) {
            return this.loadedUrls.find(u => u === url) !== undefined;
        },
        setLoaded(url) {
            this.loadedUrls.push(url);
        },
        handleCommentCreated(comment) {
            this.state = "showCommentConfirmation";
        },
        addAnotherComment() {
            this.state = this.lastState;
        },
        handleImageClick(url) {
            let $lightbox = $(`.lightbox[href="${url}"]`);

            $lightbox.trigger("click");
        },
        isEven(index) {
            return index % 2 > 0;
        },
        goBack(e) {
            if (
                $(e.target).hasClass("fluidbox--initialized") ||
                $(e.target).hasClass("fluidbox__overlay") ||
                $(e.target).hasClass("fluidbox__ghost")
            ) {
                return;
            }

            this.back();
        },
        handleImageLoad() {
            this.state = "loaded";
        }
    },
    mounted() {
        this.$store.commit("setMapCenter", [
            +this.location.lat,
            +this.location.long,
            16
        ]);

        this.$nextTick(() => {
            $(".lightbox").each(function() {
                $(this).fluidbox();
            });

            setTimeout(() => {
                if (!this.$route.hash) {
                    return;
                }

                $(this.$refs.container).animate(
                    {
                        scrollTop: $(this.$route.hash).offset().top - 20
                    },
                    400
                );
            }, 700);
        });
    },
    watch: {
        $route() {
            setTimeout(() => {
                this.scrollOverflow = !this.isPreview;
            }, 400);
        }
    },
    beforeDestroy() {
        $(".lightbox").off();
    },
    computed: {
        ...mapGetters(["isAdmin"]),
        ...mapState(["stories", "places"]),

        defaultBackRoute() {
            return "/";
        },

        phoneNumber() {
            return window.phoneNumber;
        },

        confirmationStyle({ state }) {
            let showIt = state === "showCommentConfirmation";

            return {
                visibility: showIt ? "visible" : "hidden",
                opacity: showIt ? 1 : 0
            };
        },

        chevronLink({ isPreview }) {
            return isPreview
                ? `/places/${this.location.slug}`
                : `/places/${this.location.slug}/preview`;
        },
        chevronStyle({ isPreview }) {
            return {
                transform: isPreview ? "none" : "rotate(-180deg)"
            };
        },
        location({ places }) {
            return places.find(
                item => item.slug === this.$route.params.location
            );
        },

        isPreview() {
            return this.$route.name === "preview";
        },
        containerClass({ isPreview, scrollOverflow }) {
            return {
                "md:translate-y-0": isPreview,
                "translate-location-preview": isPreview,
                "overflow-auto": scrollOverflow,
                "overflow-visible": isPreview
            };
        },
        storyCount({ location }) {
            if (location.stories.length > 1) {
                return `${location.stories.length} stories`;
            }

            return `1 story`;
        },
        locationHtml({ location }) {
            let words = location.name.split(" ");

            if (words.length > 1) {
                return `${words.shift()}<br />${words.join(" ")}`;
            }

            return location.name;
        }
    }
};
</script>
