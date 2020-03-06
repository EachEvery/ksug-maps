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
