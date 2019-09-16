import getSlug from "slugify";

export const mapStoriesToLocations = stories => {
    return [...new Set(stories.map(story => story.place))].map(location => {
        let locationStories = stories.filter(
            item => +item.place_id === +location.id
        );

        return {
            ...location,
            stories: locationStories
        };
    });
};

export const mapStories = stories => {
    return stories.map(s => {
        return {
            ...s,
            color: getStoryColor(s)
        };
    });
};

export const getStoryColor = story => {
    let color = {
        Student: "#C6D6BD",
        Resident: "#F0A38C",
        "National Guardsman": "#C6C1CE",
        "University School Student": "#C9D9E0",
        Professor: "#F4EAE2",
        Faculty: "#D8C6BF"
    }[story.role];

    return color === undefined ? "#C6D6BD" : color;
};

export const mapTheme = [
    {
        featureType: "all",
        elementType: "labels.text.fill",
        stylers: [
            {
                saturation: 36
            },
            {
                color: "#000000"
            },
            {
                lightness: 40
            }
        ]
    },
    {
        featureType: "all",
        elementType: "labels.text.stroke",
        stylers: [
            {
                visibility: "on"
            },
            {
                color: "#000000"
            },
            {
                lightness: 16
            }
        ]
    },
    {
        featureType: "all",
        elementType: "labels.icon",
        stylers: [
            {
                visibility: "off"
            }
        ]
    },
    {
        featureType: "administrative",
        elementType: "geometry.fill",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 20
            }
        ]
    },
    {
        featureType: "administrative",
        elementType: "geometry.stroke",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 17
            },
            {
                weight: 1.2
            }
        ]
    },
    {
        featureType: "landscape",
        elementType: "geometry",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 20
            }
        ]
    },
    {
        featureType: "poi",
        elementType: "geometry",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 21
            }
        ]
    },
    {
        featureType: "road.highway",
        elementType: "geometry.fill",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 17
            }
        ]
    },
    {
        featureType: "road.highway",
        elementType: "geometry.stroke",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 29
            },
            {
                weight: 0.2
            }
        ]
    },
    {
        featureType: "road.arterial",
        elementType: "geometry",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 18
            }
        ]
    },
    {
        featureType: "road.local",
        elementType: "geometry",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 16
            }
        ]
    },
    {
        featureType: "transit",
        elementType: "geometry",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 19
            }
        ]
    },
    {
        featureType: "water",
        elementType: "geometry",
        stylers: [
            {
                color: "#000000"
            },
            {
                lightness: 17
            }
        ]
    }
];
