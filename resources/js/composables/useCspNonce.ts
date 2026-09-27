import { usePage } from '@inertiajs/vue3'
import { computed, type ComputedRef } from 'vue'

/**
 * Nonce del script emitido por la cabecera Content-Security-Policy.
 *
 * La cabecera no permite `script-src 'unsafe-inline'`, así que cualquier
 * `<script>` generado en cliente (por ejemplo los JSON-LD de las páginas
 * públicas) debe llevar este nonce para que el navegador lo acepte.
 */
export function useCspNonce(): ComputedRef<string> {
    const page = usePage()

    return computed(() => {
        const props = page.props as Record<string, unknown>

        return typeof props.csp_nonce === 'string' ? props.csp_nonce : ''
    })
}
