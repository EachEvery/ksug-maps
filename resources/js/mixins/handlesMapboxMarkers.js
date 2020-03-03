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

        updateMarkerElements() {
            $(".marker").css({ opacity: 1 });

            if (!this.isLocation) {
                $("canvas").css({ opacity: "1" });
                this.resetActiveMarkers();
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

            let currentZoom = this.map.getZoom();

            this.map.easeTo({
                center: [+marker.place.long, +marker.place.lat],
                curve: 0,
                zoom: currentZoom < 16 ? 16 : currentZoom
            });

            /**
             * The active markers are reset whenever the
             * route changes and the new route is not a location
             */
            setTimeout(() => {
                this.$emit("location-clicked", marker.place);
                $(e.target).addClass("active");
            }, 300);
        }
    },
    computed: {
        markers({ places }) {
            return places.map(p => ({
                place: p,
                type: "Feature",
                geometry: {
                    type: "Point",
                    coordinates: [p.long, p.lat]
                }
            }));
        }
    },
    watch: {
        $route($newRoute, $oldRoute) {
            this.updateMarkerElements();
        },
        filters(filters) {
            this.mapboxMarkers.forEach(mbm => {
                let filteredStories = mbm.place.stories.filter(item => {
                    for (let i = 0; i < filters.length; i++) {
                        let filter = filters[i];

                        if (item[filter.key.trim()] === filter.value.trim()) {
                            return true;
                        }
                    }

                    return false;
                });

                let shouldSupressMaker =
                    filteredStories.length === 0 && filters.length > 0;

                $(mbm._element).css({
                    opacity: shouldSupressMaker ? 0.3 : 1
                });
            });
        }
    }
};
