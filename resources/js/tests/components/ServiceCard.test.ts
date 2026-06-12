import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import ServiceCard from '@/Components/ServiceCard.vue'

const mockService = {
    slug: 'software',
    eyebrow: 'DESARROLLO',
    title: 'Software Engineering',
    description: 'Building robust backend systems.',
    bullets: ['API Design', 'Microservices', 'Testing'],
    color: 'bg-brutalist-pink',
}

describe('ServiceCard.vue', () => {
    it('renders the service title', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 0 },
        })
        expect(wrapper.text()).toContain('Software Engineering')
    })

    it('renders the eyebrow badge', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 0 },
        })
        expect(wrapper.text()).toContain('DESARROLLO')
    })

    it('renders the description', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 0 },
        })
        expect(wrapper.text()).toContain('Building robust backend systems.')
    })

    it('renders all bullet points', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 0 },
        })
        for (const bullet of mockService.bullets) {
            expect(wrapper.text()).toContain(bullet)
        }
    })

    it('renders the index number', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 0 },
        })
        expect(wrapper.text()).toContain('1')
    })

    it('renders index + 1 for non-zero index', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 3 },
        })
        expect(wrapper.text()).toContain('4')
    })

    it('sets the id attribute from service.slug', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 0 },
        })
        const div = wrapper.find(`#${mockService.slug}`)
        expect(div.exists()).toBe(true)
    })

    it('applies the color class from service.color', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 0 },
        })
        // The color class should be on the decorative circle div
        const html = wrapper.html()
        expect(html).toContain('bg-brutalist-pink')
    })

    it('renders different services with different content', () => {
        const anotherService = {
            slug: 'cloud',
            eyebrow: 'INFRAESTRUCTURA',
            title: 'Cloud Infrastructure',
            description: 'Scalable cloud solutions.',
            bullets: ['AWS', 'Docker', 'Kubernetes'],
            color: 'bg-brutalist-blue',
        }

        const wrapper = mount(ServiceCard, {
            props: { service: anotherService, index: 1 },
        })

        expect(wrapper.text()).toContain('Cloud Infrastructure')
        expect(wrapper.text()).toContain('INFRAESTRUCTURA')
        expect(wrapper.text()).toContain('Kubernetes')
        expect(wrapper.text()).toContain('2') // index 0 + 1 = 2
        expect(wrapper.html()).toContain('bg-brutalist-blue')
    })

    it('has the expected CSS classes', () => {
        const wrapper = mount(ServiceCard, {
            props: { service: mockService, index: 0 },
        })
        const html = wrapper.html()
        // Check for brutalism card classes
        expect(html).toContain('border-4')
        expect(html).toContain('border-black')
        expect(html).toContain('shadow-brutalist')
        expect(html).toContain('hover:shadow-brutalist-hover-lg')
    })
})
