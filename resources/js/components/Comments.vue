<template>
    <div>
        <div
            class="xl:px-24 px-8 py-24 relative bg-black text-white"
            v-if="comments.length > 0"
        >
            <h3 class="font-display uppercase text-3xl mb-12">
                Stories &amp; Comments
            </h3>

            <div
                v-for="comment in comments"
                :key="comment.id"
                class="my-10"
                :id="`comment-${comment.id}`"
            >
                <h4 class="text-xl uppercase font-medium mb-3 font-display">
                    {{ comment.author }}
                    <span v-if="comment.role">
                        &middot; {{ comment.role }}</span
                    >
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
                :morph="morph"
                :id="id"
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

            <div
                class="bg-gray-300 p-6 mt-12 font-mono md:p-12 border border-gray-500"
            >
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
                class="border border-black font-mono uppercase h-12 flex justify-center items-center mt-5 cursor-pointed mb-16"
                to="/about#give-feedback"
                >Give Us Your Feedback</router-link
            >
        </div>
    </div>
</template>
<script>
import commentForm from "./CommentForm";
import clickable from "./Clickable";

export default {
    props: {
        comments: Array,
        morph: String,
        id: Number
    },
    components: {
        commentForm,
        clickable
    },

    data() {
        return {
            state: "default"
        };
    },

    methods: {
        handleCommentCreated(comment) {
            this.state = "showCommentConfirmation";
        },
        addAnotherComment() {
            this.state = this.lastState;
        }
    },
    computed: {
        phoneNumber() {
            return window.phoneNumber;
        },

        confirmationStyle({ state }) {
            let showIt = state === "showCommentConfirmation";

            return {
                visibility: showIt ? "visible" : "hidden",
                opacity: showIt ? 1 : 0
            };
        }
    }
};
</script>
