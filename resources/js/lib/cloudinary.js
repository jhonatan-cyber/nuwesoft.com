/**
 * Transform a Cloudinary image URL into a thumbnail URL with optimized dimensions.
 *
 * Inserts transformation parameters (c_fill, w, h, q_auto, f_auto) into a Cloudinary URL.
 * For non-Cloudinary URLs, returns the original URL unchanged.
 *
 * @param {string} url - The original image URL (Cloudinary or external)
 * @param {number} width - Desired thumbnail width (default: 400)
 * @param {number} height - Desired thumbnail height (default: 300)
 * @returns {string} Transformed thumbnail URL or original URL if not Cloudinary
 *
 * @example
 * cloudinaryThumb('https://res.cloudinary.com/demo/image/upload/v123/sample.jpg', 200, 150)
 * // https://res.cloudinary.com/demo/image/upload/c_fill,w_200,h_150,q_auto,f_auto/v123/sample.jpg
 */
/**
 * Parse a Cloudinary URL into base + rest components.
 * Returns null for non-Cloudinary URLs.
 */
function parseCloudinaryUrl(url) {
  if (!url || typeof url !== 'string') return null;
  const regex = /^(https?:\/\/res\.cloudinary\.com\/[^/]+\/image\/upload\/)(.*)$/;
  const match = url.match(regex);
  if (!match) return null;
  return { base: match[1], rest: match[2] };
}

/**
 * Strip any existing transformation segment from a Cloudinary URL's rest part.
 * Robusto: busca `v{number}/` y conserva desde ahí; si no hay versión,
 * elimina el primer segmento solo si parece transformación (contiene , _ o :).
 */
function stripTransformations(rest) {
  if (!rest) return rest;
  // Si ya empieza con versión, no hay transformación que quitar
  if (/^v\d+\//.test(rest)) return rest;

  // Busca versión `v123/` en cualquier posición — todo lo anterior es transformación
  const vIndex = rest.search(/v\d+\//);
  if (vIndex > 0) {
    return rest.slice(vIndex);
  }
  if (vIndex === 0) return rest;

  // Sin versión: quita primer segmento solo si parece transformación
  return rest.replace(/^[^\/]*[_,:][^\/]*\//, '');
}

/**
 * Transform a Cloudinary image URL into a thumbnail URL with optimized dimensions.
 *
 * Inserts transformation parameters (c_fill, w, h, q_auto, f_auto) into a Cloudinary URL.
 * For non-Cloudinary URLs, returns the original URL unchanged.
 *
 * @param {string} url - The original image URL (Cloudinary or external)
 * @param {number} width - Desired thumbnail width (default: 400)
 * @param {number} height - Desired thumbnail height (default: 300)
 * @returns {string} Transformed thumbnail URL or original URL if not Cloudinary
 *
 * @example
 * cloudinaryThumb('https://res.cloudinary.com/demo/image/upload/v123/sample.jpg', 200, 150)
 * // https://res.cloudinary.com/demo/image/upload/c_fill,w_200,h_150,q_auto,f_auto/v123/sample.jpg
 */
export function cloudinaryThumb(url, width = 400, height = 300) {
  const parsed = parseCloudinaryUrl(url);
  if (!parsed) return url;
  return `${parsed.base}c_fill,w_${width},h_${height},q_auto,f_auto/${stripTransformations(parsed.rest)}`;
}

/**
 * Generate a tiny blurred placeholder URL from a Cloudinary image.
 *
 * Useful for blur-up lazy loading: show this placeholder while the full image loads.
 * Returns a 20px-wide heavily blurred version via Cloudinary's `e_blur` transformation.
 * For non-Cloudinary URLs, returns the original URL unchanged.
 *
 * @param {string} url - The original Cloudinary image URL
 * @returns {string} Blurred placeholder URL or original URL if not Cloudinary
 *
 * @example
 * cloudinaryBlurUrl('https://res.cloudinary.com/demo/image/upload/v123/sample.jpg')
 * // https://res.cloudinary.com/demo/image/upload/w_20,e_blur:1000,q_auto,f_auto/v123/sample.jpg
 */
export function cloudinaryBlurUrl(url) {
  const parsed = parseCloudinaryUrl(url);
  if (!parsed) return url;
  return `${parsed.base}w_20,e_blur:1000,q_auto,f_auto/${stripTransformations(parsed.rest)}`;
}
