export default {
    data() {
        return {
            currentZoom: 16,
            zoomSteps: [14.5, 15, 15.5, 16, 16.5, 17, 17.5],
            zooming: false
        };
    },

    methods: {
        zoom(zoom) {
            return new Promise(resolve => {
                if (this.map.getZoom() === zoom || this.zooming) {
                    resolve();
                } else {
                    this.zooming = true;
                    this.map.zoomTo(zoom);

                    setTimeout(() => {
                        this.zooming = false;
                        resolve();
                    }, 450);
                }
            });
        }
    },
    computed: {
        prevZoom({ currentZoom, zoomSteps }) {
            let index = zoomSteps.findIndex(s => currentZoom === s);
            let prev = zoomSteps[index - 1];

            return prev === undefined ? zoomSteps[zoomSteps.length - 1] : prev;
        },

        nextZoom({ currentZoom, zoomSteps }) {
            let index = zoomSteps.findIndex(s => currentZoom === s);
            let next = zoomSteps[index + 1];

            return next === undefined ? zoomSteps[0] : next;
        }
    }
};
