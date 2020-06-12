import Axios from "axios";

export default {
    data() {
        return {
            resources: []
        };
    },

    async mounted() {
        let { data } = await Axios.get("/learning-resources");

        this.resources = data;

        console.log("resources", this.resources);
    }
};
