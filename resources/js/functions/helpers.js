import { isEmpty } from "lodash";
import axios from "axios";

export const unique = arr => {
    return [...new Set(arr)];
};

export const filled = arr => {
    return arr.filter(i => !isEmpty(i));
};

export const formPost = (url, formEl) => {
    return axios.post(url, new FormData(formEl), {
        headers: { "Content-Type": "multipart/form-data" }
    });
};

export const getMapboxToken = () => {
    return "pk.eyJ1IjoibmF0ZWhvYmkiLCJhIjoiY2s3MHg2dTlxMDEzYzNnbnkweWJnbHZzOCJ9.uLkc9fWDpYi6Y_ojutcgWA";
};

export const orderPlaces = (places, tour) => {
    return places.sort((a, b) => {
        let aFirstStory = tour.stories
            .filter(s => s.place_id === a.id)
            .sort((a, b) => a.sort_order - b.sort_order)[0];

        let bFirstStory = tour.stories
            .filter(s => s.place_id === b.id)
            .sort((a, b) => a.sort_order - b.sort_order)[0];

        let aOrder =
            aFirstStory.pivot.sort_order === null
                ? 0
                : aFirstStory.pivot.sort_order;
        let bOrder =
            bFirstStory.pivot.sort_order === null
                ? 0
                : bFirstStory.pivot.sort_order;

        return aOrder - bOrder;
    });
};
