import { isEmpty } from "lodash";

export const unique = arr => {
    return [...new Set(arr)];
};

export const filled = arr => {
    return arr.filter(i => !isEmpty(i));
};
