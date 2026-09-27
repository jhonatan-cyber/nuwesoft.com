import { nextTick, ref, type Ref } from 'vue'

interface AnalyzerDeps {
    form: any
    selectedTechnologies: Ref<any[]>
    newImages: Ref<any[]>
    automaticCaptureNames: Ref<Set<string>>
    gallerySection: Ref<any>
}

const captureToFile = (capture: { base64: string; name?: string; mime_type?: string }, index: number): File => {
    const bytes = window.atob(capture.base64)
    const buffer = new Uint8Array(bytes.length)
    for (let position = 0; position < bytes.length; position += 1) {
        buffer[position] = bytes.charCodeAt(position)
    }

    const safeName = (capture.name || `pagina-${index + 1}`).replace(/[^a-z0-9-]+/gi, '-').toLowerCase()
    return new File([buffer], `${safeName || `pagina-${index + 1}`}.jpg`, { type: capture.mime_type || 'image/jpeg' })
}

/**
 * Analiza la URL del proyecto, detecta tecnologías, credenciales y capturas.
 * Devuelve los refs que el formulario pinta en el panel del analizador.
 */
export function useProjectAnalyzer({ form, selectedTechnologies, newImages, automaticCaptureNames, gallerySection }: AnalyzerDeps) {
    const isAnalyzing = ref(false)
    const needsCredentials = ref(false)
    const analyzerMessage = ref('')
    const analyzerError = ref('')
    const analyzerUsername = ref('')
    const analyzerPassword = ref('')
    const authenticationFields = ref<string[]>([])
    const authenticationValues = ref<Record<string, string>>({})

    const analyzeTechnologies = async () => {
        analyzerError.value = ''
        analyzerMessage.value = ''
        if (!form.project_url) {
            analyzerError.value = 'Ingresa primero la URL del sitio.'
            return
        }

        isAnalyzing.value = true
        try {
            const { data } = await (window as any).axios.post(route('projects.analyze-technologies'), {
                url: form.project_url,
                username: analyzerUsername.value || null,
                password: analyzerPassword.value || null,
                credentials: authenticationValues.value,
            })
            selectedTechnologies.value = [...new Set([...selectedTechnologies.value, ...data.technology_ids])]
            const descriptionWasCompleted = !form.desc.trim() && Boolean(data.page_description)
            if (descriptionWasCompleted) {
                form.desc = data.page_description
            }
            authenticationFields.value = data.authentication_fields || []
            needsCredentials.value = Boolean(data.needs_credentials)
            if (!needsCredentials.value) {
                analyzerPassword.value = ''
                authenticationValues.value = {}
            }
            analyzerMessage.value = data.detected.length
                ? `Detectadas: ${data.detected.join(', ')}. Se seleccionaron ${data.technology_ids.length} tecnologías disponibles.`
                : 'El sitio respondió, pero no se identificaron tecnologías compatibles.'
            if (needsCredentials.value) {
                analyzerMessage.value += ' Se detectó un formulario de acceso; completa sus campos para analizar el área privada.'
            }
            if (descriptionWasCompleted) {
                analyzerMessage.value += ' La descripción del proyecto también se completó con la información publicada por el sitio.'
            }
            if (data.captures?.length) {
                const capturedFiles = data.captures.map(captureToFile)
                newImages.value = [...newImages.value, ...capturedFiles]
                automaticCaptureNames.value = new Set([...automaticCaptureNames.value, ...capturedFiles.map(file => file.name)])
                authenticationValues.value = {}
                analyzerMessage.value += ` Se agregaron ${data.captures.length} capturas, incluido el acceso y los módulos encontrados.`
                await nextTick()
                gallerySection.value?.scrollIntoView({ behavior: 'smooth', block: 'nearest' })
            }
        } catch (error: any) {
            if (error.response?.status === 401 && error.response?.data?.needs_credentials) {
                needsCredentials.value = true
                authenticationFields.value = []
                analyzerError.value = 'El sitio requiere autenticación HTTP Basic.'
            } else {
                analyzerError.value = error.response?.data?.message || 'No se pudo analizar el sitio.'
            }
        } finally {
            isAnalyzing.value = false
        }
    }

    return {
        isAnalyzing,
        needsCredentials,
        analyzerMessage,
        analyzerError,
        analyzerUsername,
        analyzerPassword,
        authenticationFields,
        authenticationValues,
        analyzeTechnologies,
    }
}
