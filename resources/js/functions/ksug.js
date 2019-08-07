import getSlug from "slugify";

export const mapStoriesToLocations = stories => {
    return [...new Set(stories.map(story => story.location))].map(location => {
        let locationStories = stories.filter(
            item => item.location === location
        );
        return {
            name: location,
            stories: locationStories,
            lat: stories[0].lat,
            long: stories[0].long,
            slug: getSlug(location, {
                remove: /['()]/g
            }).toLowerCase()
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
    try {
        return {
            "KSU Student": "#C6D6BD",
            Resident: "#F0A38C",
            "National Guard": "#C6C1CE",
            "High School Student": "#C9D9E0",
            "KSU Staff": "#F4EAE2",
            "KSU Faculty": "#D8C6BF"
        }[story.role];
    } catch (e) {
        return "#C6D6BD";
    }
};
