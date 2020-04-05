import { mapGetters } from "vuex";
import { getDistance, convertDistance } from "geolib";

export default {
    methods: {
        getLocationDistance(lat, lng) {
            if (this.userLocation === undefined) {
                return 0;
            }

            let locationCoords = {
                latitude: +lat.toFixed(8),
                longitude: +lng.toFixed(8),
            };

            let userCoords = {
                latitude: +this.userLocation.coords.latitude.toFixed(8),
                longitude: +this.userLocation.coords.longitude.toFixed(8),
            };

            let distanceInMeters = getDistance(userCoords, locationCoords);

            return convertDistance(distanceInMeters, "mi");
        },
    },

    computed: {
        ...mapGetters(["userLocation"]),

        distance({ userLocation }) {
            if (
                userLocation === undefined ||
                this.lat === undefined ||
                this.lng === undefined
            ) {
                return undefined;
            }

            return (
                this.getLocationDistance(this.lat, this.lng).toFixed(1) +
                " MILES FROM YOU"
            );
        },
    },
};
