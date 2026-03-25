/**
 * Capitaliza la primera letra de cada palabra en un texto.
 * @param {string} text
 * @returns {string}
 */
export const capitalizeWords = (text) => {
    if (!text) return '';
    return text.toString()
        .toLowerCase()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

/**
 * Capitaliza solo la primera letra de una oración o texto.
 * @param {string} text
 * @returns {string}
 */
export const capitalizeFirstLetter = (text) => {
    if (!text) return '';
    const trimmed = text.toString().trim();
    return trimmed.charAt(0).toUpperCase() + trimmed.slice(1).toLowerCase();
};
