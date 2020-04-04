import { mapGetters } from "vuex";
import { getDistance, convertDistance } from "geolib";

export default {
    computed: {
        ...mapGetters(["userLocation"]),

        distance({ userLocation }) {
            console.log("user location", userLocation);
            if (
                userLocation === undefined ||
                this.lat === undefined ||
                this.lng === undefined
            ) {
                return undefined;
            }

            let locationCoords = {
                latitude: +this.lat.toFixed(8),
                longitude: +this.lng.toFixed(8)
            };

            let userCoords = {
                latitude: +this.userLocation.coords.latitude.toFixed(8),
                longitude: +this.userLocation.coords.longitude.toFixed(8)
            };

            let distanceInMeters = getDistance(userCoords, locationCoords);

            return (
                convertDistance(distanceInMeters, "mi").toFixed(1) +
                " MILES FROM YOU"
            );
        }
    }
};
