export default {
    data() {
        return {
            innerWidth: window.innerWidth,
            innerHeight: window.innerHeight
        };
    },

    computed: {
        sm({ innerWidth }) {
            return innerWidth >= 640;
        },
        md({ innerWidth }) {
            return innerWidth >= 768;
        },
        lg({ innerWidth }) {
            return innerWidth >= 1024;
        },
        xl({ innerWidth }) {
            return innerWidth >= 1280;
        }
    },

    mounted() {
        window.addEventListener("resize", () => {
            this.innerHeight = window.innerHeight;
            this.innerWidth = window.innerWidth;
        });
    }
};
