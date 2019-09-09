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
