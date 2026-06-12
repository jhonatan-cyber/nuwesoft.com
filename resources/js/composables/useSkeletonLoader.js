import { ref, onMounted } from 'vue'

/**
 * Composable para manejar el timing de skeletons durante navegación Inertia.
 *
 * Retorna un ref `skeletonReady` que inicia como `false` (skeleton visible)
 * y se vuelve `true` automáticamente cuando termina el tiempo de skeleton,
 * calculado dinámicamente según cuánto tardó la navegación de Inertia.
 *
 * @param {Object} options
 * @param {number}  options.minSkeletonMs   - Duración mínima del skeleton tras una navegación (default: 300)
 * @param {number}  options.initialLoadMs   - Duración del skeleton en primer load / URL directa (default: 200)
 * @returns {{ skeletonReady: import('vue').Ref<boolean> }}
 *
 * @example
 * const { skeletonReady } = useSkeletonLoader()
 * // En el template:
 * // <div v-if="!skeletonReady"><SkeletonStuff /></div>
 * // <div v-else><RealContent /></div>
 */
export function useSkeletonLoader(options = {}) {
    const { minSkeletonMs = 300, initialLoadMs = 200 } = options

    const skeletonReady = ref(false)

    onMounted(() => {
        const navStart = window.__nuwesoft_navStart
        window.__nuwesoft_navStart = null

        if (navStart) {
            // Llegamos aquí vía navegación Inertia — calculamos el tiempo restante
            const elapsed = Date.now() - navStart
            const remaining = Math.max(0, minSkeletonMs - elapsed)

            if (remaining > 0) {
                setTimeout(() => { skeletonReady.value = true }, remaining)
            } else {
                requestAnimationFrame(() => { skeletonReady.value = true })
            }
        } else {
            // Primer load (URL directa / refresh)
            setTimeout(() => { skeletonReady.value = true }, initialLoadMs)
        }
    })

    return { skeletonReady }
}
