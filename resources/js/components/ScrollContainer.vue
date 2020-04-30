<template>
    <div
        class="flex overflow-auto flex-no-wrap hide-scrollbars items-start"
        ref="container"
        @scroll="updateScrollPos"
    >
        <div class="w-5 md:hidden opacity-0" style="flex: 0 0 auto;">
            spacer
        </div>

        <slot />

        <div class="w-1 flex-shrink-0 opacity-0" style="flex: 0 0 auto;">
            spacer
        </div>

        <portal v-if="buttonsPortal" :to="buttonsPortal">
            <div class="flex">
                <clickable
                    class="mr-5"
                    @click="scrollLeft"
                    :class="{ 'opacity-25': scrollPos === 0 }"
                >
                    <img
                        src="/images/left-arrow.png"
                        alt="left-arrow"
                        class="h-8"
                    />
                </clickable>

                <clickable
                    @click="scrollRight"
                    :class="{ 'opacity-25': scrollPos === maxScrollPos }"
                >
                    <img
                        src="/images/right-arrow.png"
                        alt="left-arrow"
                        class="h-8"
                    />
                </clickable>
            </div>
        </portal>
    </div>
</template>
<script>
import clickable from "./Clickable";
import $ from "jquery";

export default {
    components: {
        clickable
    },
    props: {
        buttonsPortal: String,
        transitionLength: {
            default: 300
        },
        stepWidth: {
            default: 300
        }
    },
    data() {
        return {
            scrollPos: 0,
            step: 125,
            maxScrollPos: 100000
        };
    },
    methods: {
        scrollLeft() {
            let scrollLeft = `-=${this.stepWidth}`;

            if (this.scrollPos - this.stepWidth < 0) {
                scrollLeft = 0;
            }

            $(this.$refs.container).animate(
                { scrollLeft },
                this.transitionLength
            );
        },
        scrollRight() {
            let scrollLeft = `+=${this.stepWidth}`;

            if (this.scrollPos + this.stepWidth > this.maxScrollPos) {
                scrollLeft = this.maxScrollPos;
            }

            $(this.$refs.container).animate(
                { scrollLeft },
                this.transitionLength
            );
        },

        updateScrollPos(e) {
            this.scrollPos = e.target.scrollLeft;

            this.$emit("scroll", e);
        },
        setMaxScrollLength() {
            setTimeout(() => {
                this.maxScrollPos =
                    this.$refs.container.scrollWidth -
                    this.$refs.container.clientWidth;
            }, 500);
        }
    },
    mounted() {
        this.setMaxScrollLength();

        window.addEventListener("resize", this.setMaxScrollLength);
    },
    destroyed() {
        window.removeEventListener("resize", this.setMaxScrollLength);
    }
};
</script>
