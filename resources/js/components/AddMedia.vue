<template>
    <div
        class="flex flex-col xl:flex-row font-mono bg-gray-100 border-l border-r py-12 px-6 items-start"
    >
        <camera-icon class="mt-1 object-contain w-16 h-8 mr-6 mb-5 mb-5 " />

        <div class="flex flex-col">
            <p style="max-width: 20rem;" class="mb-4 text-xs font-bold">
                Add a video or photograph (new or old) of your story at this
                location. Due to browser restrictions, we only accept MP4 video
                files.
            </p>

            <input
                class="hidden"
                type="file"
                ref="fileInput"
                maxlength="1"
                @change="handleFileChange"
                accept="image/*, video/mp4"
            />

            <div class="h-24 flex flex-col items-stretch justify-center -mb-4">
                <div class="flex justify-between items-center" v-if="file">
                    <span
                        class="bg-white p-2 text-2xs shadow-inner border border-dotted border-gray-600 flex-grow mr-5"
                        >{{ fileName }}</span
                    >

                    <button
                        class="text-md font-bold"
                        aria-label="Remove file"
                        @click.stop="handleClearButtonClick"
                    >
                        &times;
                    </button>
                </div>

                <clickable
                    v-if="file === undefined"
                    class="text-center text-xs py-3 border-black border px-4 text-black font-mono uppercase self-start cursor-pointer"
                    @click.prevent="handleUploadClick"
                    >Upload a Photo or Video</clickable
                >

                <!-- <span
                    class="italic text-2xs mt-2 opacity-50"
                    style="max-width: 15rem"
                    ></span
                > -->
            </div>
        </div>
    </div>
</template>
<script>
import cameraIcon from "./CameraIcon";
import clickable from "./Clickable";
import _ from "lodash";

export default {
    components: {
        cameraIcon,
        clickable
    },

    data() {
        return {
            file: undefined
        };
    },

    computed: {
        fileName({ file }) {
            return _.truncate(file.name, { length: "25" });
        },
        hasFile({ file }) {
            return file !== undefined;
        }
    },

    methods: {
        clearFile() {
            this.$refs.fileInput.value = null;
            this.file = undefined;
        },

        handleClearButtonClick() {
            this.clearFile();
        },

        handleUploadClick() {
            this.$refs.fileInput.click();
        },

        handleFileChange(e) {
            this.file = e.target.files[0];

            if (this.file.size / 1000000 > 50) {
                alert("Please select a file smaller than 50MB");
                this.clearFile();
            }
        }
    }
};
</script>
