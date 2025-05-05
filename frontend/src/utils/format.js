// utils/format.js
export const kebabCase = (str) =>
    str && str.toLowerCase().replace(/[^a-z0-9]+/g, '-');