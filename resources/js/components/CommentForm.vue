<template>
    <form ref="form" @submit.prevent="handleSubmit">
        <fieldset class="flex-col flex" :disabled="false">
            <div :class="{ 'opacity-25': state === 'loading' }">
                <div class="flex flex-col lg:flex-row">
                    <input
                        type="text"
                        name="comment[author]"
                        class="border-b border-black py-2 font-mono lg:text-xs lg:mr-2 text-black mb-10 lg:mb-8 focus:outline-none transition w-full bg-white rounded-none"
                        placeholder="Your Name (optional)"
                    />

                    <input
                        type="email"
                        name="comment[email]"
                        class="border-b border-black py-2 font-mono lg:text-xs lg:ml-2 text-black mb-10 lg:mb-8 focus:outline-none transition w-full bg-white rounded-none"
                        placeholder="Your Email Address (optional)"
                    />
                </div>

                <select
                    :options="roleOptions"
                    name="comment[role]"
                    class="font-mono p-3 lg:text-xs  mb-8 w-full"
                >
                    <option value=""
                        >Select your relationship to May 4 (optional)</option
                    >
                    <option v-for="(option, i) in roleOptions" :key="i">{{
                        option
                    }}</option>
                </select>

                <input
                    type="hidden"
                    name="comment[commentable_type]"
                    :value="morph"
                />
                <input
                    type="hidden"
                    name="comment[commentable_id]"
                    :value="id"
                />

                <textarea
                    class="border border-black md:text-xs p-2 font-mono text-black focus:outline-none transition w-full bg-white rounded-none shadow-none"
                    name="comment[text]"
                    placeholder="Type your story or reflection here..."
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
                class="w-full text-center py-3 px-2 bg-black text-white font-mono uppercase relative"
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

<style scoped>
select {
    display: block;
    font-weight: 700;
    color: #000;
    line-height: 1.3;
    /* padding: 0.6em 1.4em 0.5em 0.8em; */
    width: 100%;
    border-radius: 0;
    max-width: 100%;
    box-sizing: border-box;
    /* margin: 0; */
    border: 1px solid #000;

    -moz-appearance: none;
    -webkit-appearance: none;
    appearance: none;
    background-color: #fff;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23000%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"),
        linear-gradient(to bottom, #ffffff 0%, #ffffff 100%);
    background-repeat: no-repeat, repeat;
    background-position: right 0.7em top 50%, 0 0;
    background-size: 0.65em auto, 100%;
}
</style>
<script>
import Vapor from "laravel-vapor";
import clickable from "./Clickable";
import axios from "axios";
import addMedia from "./AddMedia";
import { formPost } from "../functions/helpers";
import selectBox from "./SelectBox";

export default {
    components: {
        clickable,
        addMedia,
        selectBox
    },
    props: {
        postUrl: String,
        morph: String,
        id: Number
    },
    data() {
        return {
            state: "default",
            uploadProgress: 0,
            fileKey: undefined,
            role: undefined,
            roleOptions: [
                "Resident of Kent",
                "KSU Student",
                "KSU Faculty",
                "National Guard"
            ]
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
                let { data } = await formPost("/comments", this.$refs.form);

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
