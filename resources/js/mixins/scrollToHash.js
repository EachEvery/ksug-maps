import $ from "jquery";

export default {
    mounted() {
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
        }, 500);
    }
};
