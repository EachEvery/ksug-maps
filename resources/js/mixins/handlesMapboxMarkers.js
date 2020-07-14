import { mapGetters } from "vuex";
export default {
    data() {
        return {
            mapboxMarkers: []
        };
    },
    methods: {
        getMapboxMarker(location) {
            return this.mapboxMarkers.find(
                m =>
                    +m._lngLat.lng === +location.long &&
                    m._lngLat.lat === +location.lat
            );
        },

        updateActiveMarker(latlngArr) {
            let matchingMarker = this.mapboxMarkers.find(
                m =>
                    +m._lngLat.lng === +latlngArr[1] &&
                    m._lngLat.lat === +latlngArr[0]
            );

            if (matchingMarker) {
                $(matchingMarker._element).addClass("active");
                $(matchingMarker._element).css({
                    opacity: 1
                });
            } else {
                this.updateMarkerElements();
            }
        },

        updateMarkerElements() {
            if (this.isEmbed) {
                let mbm = this.getMapboxMarker(this.currentLocation);

                $(".marker").css({ opacity: 0.2 });

                $(mbm._element).addClass("active");
                $(mbm._element).css({ opacity: 1 });

                return;
            }

            if (this.isTour) {
                $(".marker").css({ opacity: 0 });

                return;
            }

            $(".marker").css({ opacity: 1 });

            if (!this.isLocation) {
                this.resetActiveMarkers();
            }

            $("canvas").css({ opacity: "1" });

            if (!this.isLocation && this.validFilters.length === 0) {
                $("canvas").css({ opacity: "1" });
            } else if (!this.isLocation && this.validFilters.length > 0) {
                this.mapboxMarkers.forEach(marker => {
                    let markerStoriesMatchingAnyFilterSet = marker.place.stories.filter(
                        s => {
                            let passingFilters = this.validFilters.filter(f => {
                                // debugger;

                                if (f.day && f.role) {
                                    return f.day == s.day && f.role === s.role;
                                }

                                if (f.day) {
                                    return f.day.trim() === s.day.trim();
                                }

                                if (f.role) {
                                    return f.role === s.role;
                                }
                            });

                            return passingFilters.length !== 0;
                        }
                    );

                    if (markerStoriesMatchingAnyFilterSet.length === 0) {
                        $(marker._element).css({ opacity: 0.3, border: "0" });
                    } else {
                        $(marker._element).css({
                            opacity: 1,
                            border: "3px solid #F0A38C"
                        });
                    }
                });
            } else {
                $("canvas").css({ opacity: "0.5" });
            }

            if (this.isLocation) {
                $(".marker:not(.active)").css({ opacity: "0.1" });
            }
        },

        setInitialActiveMarker() {
            let mbm = this.getMapboxMarker(this.currentLocation);

            $(mbm._element).addClass("active");

            this.updateMarkerElements();
        },

        resetActiveMarkers() {
            $(".marker")
                .removeClass("active")
                .css({
                    opacity: 1,
                    border: 0
                });
        },

        async handleMarkerClick(marker, e) {
            e.preventDefault();

            /**
             * The active markers are reset whenever the
             * route changes and the new route is not a location
             */
            setTimeout(() => {
                this.$emit("location-clicked", marker.place);
            }, 300);
        }
    },
    computed: {
        ...mapGetters(["validFilters"]),

        markers({ places }) {
            return places.map(p => ({
                place: p,
                type: "Feature",
                properties: {
                    description: p.name
                },
                geometry: {
                    type: "Point",
                    coordinates: [p.long, p.lat]
                }
            }));
        }
    },
    watch: {
        validFilters: {
            deep: true,
            handler(filters) {
                this.updateMarkerElements();
            }
        }
    }
};
