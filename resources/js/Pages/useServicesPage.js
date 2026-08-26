import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useInView } from '@/composables/useInView';
import { usePageTracking } from '@/composables/usePageTracking';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';

export function useServicesPage() {
    const { t } = useI18n();
    const page = usePage();
    const settings = computed(() => page.props.settings || {});
    const siteName = computed(() => settings.value.site_name || 'NUWESOFT');
    const pageTitle = computed(() => t('servicios.head_title').replace('NUWESOFT', siteName.value));
    const pageUrl = computed(() => window.location.href);
    const pageDesc = computed(() => t('servicios.subtitle'));
    const { skeletonReady } = useSkeletonLoader();
    const { el: ctaRef, isVisible: ctaVisible } = useInView(0.1);
    const isVisible = ref(false);

    usePageTracking();
    onMounted(() => { isVisible.value = true; });

    const serviciosJsonLd = computed(() => [
        ['Software a Medida', 'Desarrollo de software fullstack a medida: backend, frontend, arquitectura y experiencia de usuario.', 'Custom Software Development'],
        ['Cloud y DevOps', 'Infraestructura cloud, automatización de despliegues, CI/CD y observabilidad.', 'Cloud Infrastructure & DevOps'],
        ['Automatización', 'Integraciones, flujos automáticos y eliminación de trabajo repetitivo.', 'Business Process Automation'],
        ['Frontend Premium', 'Interfaces de alta calidad, accesibles y optimizadas para conversión.', 'Frontend Development'],
    ].map(([name, description, serviceType]) => ({
        '@context': 'https://schema.org', '@type': 'ProfessionalService', name,
        description, serviceType,
        provider: { '@type': 'Organization', name: siteName.value, url: window.location.origin },
        areaServed: 'Global', url: window.location.href,
    })));

    const serviceStats = [
        { value: 'END-TO-END', labelKey: 'servicios.stats.s1' },
        { value: 'MULTISTACK', labelKey: 'servicios.stats.s2' },
        { value: 'SHIP FAST', labelKey: 'servicios.stats.s3' },
    ];
    const sectionDelay = (index, step = 120) => ({ transitionDelay: `${index * step}ms` });
    const services = ['software', 'cloud', 'automation', 'frontend'].map((slug, index) => ({
        slug,
        eyebrow: t(`servicios.items.${slug}.eyebrow`),
        title: t(`servicios.items.${slug}.title`),
        description: t(`servicios.items.${slug}.desc`),
        bullets: [1, 2, 3].map(item => t(`servicios.items.${slug}.b${item}`)),
        color: ['bg-brutalist-pink', 'bg-brutalist-blue', 'bg-brutalist-yellow', 'bg-brutalist-pink'][index],
    }));

    return {
        t, pageTitle, pageUrl, pageDesc, skeletonReady, ctaRef, ctaVisible,
        isVisible, serviciosJsonLd, serviceStats, sectionDelay, services,
    };
}
