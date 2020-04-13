<template>
    <scroll-container
        class="lg:flex-col lg:overflow-y-scroll lg:h-screen lg:pb-55vh tour-scroll-container"
        ref="container"
        @scroll="handleScroll"
    >
        <slot />
    </scroll-container>
</template>

<script>
import scrollContainer from "./ScrollContainer";
import windowDimensions from "../mixins/windowDimensions";
import $ from "jquery";
import { mapState } from "vuex";

export default {
    mixins: [windowDimensions],

    components: {
        scrollContainer
    },

    computed: {
        ...mapState(["scrollPosition"]),
        scrollOffset({ lg }) {
            return lg ? 128 : 100;
        }
    },

    data() {
        return {
            activeStep: undefined
        };
    },

    watch: {
        activeStep(newVal, oldVal) {
            if (newVal !== oldVal && newVal !== undefined) {
                this.$emit("scroll-element-changed", this.activeStep);
            }
        }
    },

    mounted() {
        let scrollpos = this.scrollPosition;

        setTimeout(() => {
            if (this.lg) {
                $(this.$refs.container.$el).scrollTop(this.scrollPosition);
            } else {
                $(this.$refs.container.$el).scrollLeft(this.scrollPosition);
            }
        }, 100);
    },

    methods: {
        scrollToFirstStep() {
            let children = $(this.$refs.container.$el)
                .find(".vertical-scroll-container > div")
                .toArray();

            this.scrollToElement(children[1]);
        },
        handleScroll(e) {
            let children = $(this.$refs.container.$el)
                .find(".vertical-scroll-container > div")
                .toArray();

            let scrollPos = e.target[this.lg ? "scrollTop" : "scrollLeft"];
            let startProp = this.lg ? "offsetTop" : "offsetLeft";

            this.$store.commit("setScrollPosition", scrollPos);

            this.activeStep = children.find((c, i) => {
                let offset = c[startProp] - this.scrollOffset - 10;
                let range = this.lg ? $(c).outerHeight() : $(c).outerWidth();

                return offset < scrollPos && offset + range > scrollPos;
            });
        },

        scrollToElement(el) {
            let prop = this.lg ? "scrollTop" : "scrollLeft";
            let elProp = this.lg ? "offsetTop" : "offsetLeft";

            $(this.$refs.container.$el).animate(
                {
                    [prop]: el[elProp] - this.scrollOffset
                },
                600
            );
        },

        scrollToStart() {
            let prop = this.lg ? "scrollTop" : "scrollLeft";

            $(this.$refs.container.$el).animate(
                {
                    [prop]: 0
                },
                1600
            );
        }
    }
};
</script>
