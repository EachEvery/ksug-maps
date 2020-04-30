<template>
    <form ref="form" @submit.prevent="handleSubmit">
        <fieldset class="flex-col flex" :disabled="false">
            <div :class="{ 'opacity-25': state === 'loading' }">
                <input
                    type="text"
                    name="comment[author]"
                    class="border-b border-black py-2 font-mono text-black mb-10 focus:outline-none transition w-full bg-white rounded-none"
                    placeholder="Your Name (optional)"
                />

                <input
                    type="email"
                    name="comment[email]"
                    class="border-b border-black py-2 font-mono text-black mb-10 focus:outline-none transition w-full bg-white rounded-none"
                    placeholder="Your Email Address (optional)"
                />

                <textarea
                    class="border border-black p-2 font-mono text-black mb-10 focus:outline-none transition w-full bg-white rounded-none shadow-none"
                    name="comment[text]"
                    placeholder="Message Text"
                    :required="true"
                    style="min-height: 15.625rem;"
                ></textarea>

                <add-media ref="media" />
            </div>

            <input
                type="hidden"
                name="media_tmp_path"
                :value="fileKey"
                v-if="fileKey"
            />

            <clickable
                class="w-full text-center py-3 px-2 bg-black text-white font-mono uppercase mt-8 relative"
                style="rounded"
            >
                <div
                    class="absolute transition inset-y-0 left-0"
                    style="background: rgba(255, 255, 255, .6);"
                    :style="{ width: uploadProgress + '%' }"
                ></div>
                Submit</clickable
            >
        </fieldset>
    </form>
</template>
<script>
import Vapor from "laravel-vapor";
import clickable from "./Clickable";
import axios from "axios";
import addMedia from "./AddMedia";
import { formPost } from "../functions/helpers";

export default {
    components: {
        clickable,
        addMedia
    },
    props: {
        postUrl: String
    },
    data() {
        return {
            state: "default",
            uploadProgress: 0,
            fileKey: undefined
        };
    },
    methods: {
        async handleSubmit() {
            this.state = "loading";

            let media = this.$refs.media;

            if (media.hasFile) {
                let response = await Vapor.store(
                    media.$refs.fileInput.files[0],
                    {
                        progress: progress => {
                            this.uploadProgress = Math.round(progress * 100);
                        }
                    }
                );

                this.fileKey = response.key;
            }

            setTimeout(async () => {
                let { data } = await formPost(this.postUrl, this.$refs.form);

                media.clearFile();

                this.$emit("comment-created", data);

                this.uploadProgress = 0;
                this.$refs.form.reset();
                this.state = "default";
            }, 50);
        }
    }
};
</script>
