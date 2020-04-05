import { mapGetters } from "vuex";
export default {
    data() {
        return {
            mapboxMarkers: [],
        };
    },
    methods: {
        getMapboxMarker(location) {
            return this.mapboxMarkers.find(
                (m) =>
                    +m._lngLat.lng === +location.long &&
                    m._lngLat.lat === +location.lat
            );
        },

        updateActiveMarker(latlngArr) {
            let matchingMarker = this.mapboxMarkers.find(
                (m) =>
                    +m._lngLat.lng === +latlngArr[1] &&
                    m._lngLat.lat === +latlngArr[0]
            );

            if (matchingMarker) {
                $(matchingMarker._element).addClass("active");
                $(matchingMarker._element).css({
                    opacity: 1,
                });
            } else {
                this.updateMarkerElements();
            }
        },

        updateMarkerElements() {
            $(".marker").css({ opacity: 1 });
            this.resetActiveMarkers();
            $("canvas").css({ opacity: "1" });

            if (!this.isLocation && this.validFilters.length === 0) {
                $("canvas").css({ opacity: "1" });
            } else if (!this.isLocation && this.validFilters.length > 0) {
                this.mapboxMarkers.forEach((marker) => {
                    let markerStoriesMatchingAnyFilterSet = marker.place.stories.filter(
                        (s) => {
                            let passingFilters = this.validFilters.filter(
                                (f) => {
                                    if (f.day && s.day) {
                                        return (
                                            f.day == s.day && f.role === s.role
                                        );
                                    }

                                    if (f.day) {
                                        return f.day.trim() === s.day;
                                    }

                                    if (f.role) {
                                        return f.role === s.role;
                                    }
                                }
                            );

                            return passingFilters.length !== 0;
                        }
                    );

                    if (markerStoriesMatchingAnyFilterSet.length === 0) {
                        $(marker._element).css({ opacity: 0.3 });
                    } else {
                        $(marker._element).css({ opacity: 1 });
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
            $(".marker").removeClass("active");
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
        },
    },
    computed: {
        ...mapGetters(["validFilters"]),

        markers({ places }) {
            return places.map((p) => ({
                place: p,
                type: "Feature",
                properties: {
                    description: p.name,
                },
                geometry: {
                    type: "Point",
                    coordinates: [p.long, p.lat],
                },
            }));
        },
    },
    watch: {
        validFilters: {
            deep: true,
            handler(filters) {
                this.updateMarkerElements();
            },
        },

        $route($newRoute, $oldRoute) {
            this.updateMarkerElements();
        },
        // filters(filters) {
        //     this.mapboxMarkers.forEach((mbm) => {
        //         let filteredStories = mbm.place.stories.filter((item) => {
        //             for (let i = 0; i < filters.length; i++) {
        //                 let filter = filters[i];

        //                 if (item[filter.key.trim()] === filter.value.trim()) {
        //                     return true;
        //                 }
        //             }

        //             return false;
        //         });

        //         let shouldSupressMaker =
        //             filteredStories.length === 0 && filters.length > 0;

        //         $(mbm._element).css({
        //             opacity: shouldSupressMaker ? 0.3 : 1,
        //         });
        //     });
        // },
    },
};
