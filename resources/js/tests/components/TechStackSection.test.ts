import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import TechStackSection from '@/Components/TechStackSection.vue'

const mockTechnologies = [
    { name: 'Vue.js', category: 'frontend', logo_url: '/vue.svg', invert_dark: false },
    { name: 'Laravel', category: 'backend', logo_url: '/laravel.svg', invert_dark: false },
    { name: 'n8n', category: 'automation', logo_url: '/n8n.svg', invert_dark: false },
    { name: 'TypeScript', category: 'languages', logo_url: '/ts.svg', invert_dark: false },
]

describe('TechStackSection.vue', () => {
    it('renders the skills badge', () => {
        const wrapper = mount(TechStackSection, {
            props: { technologies: mockTechnologies },
        })
        expect(wrapper.text()).toContain('skills.badge')
    })

    it('renders the section title', () => {
        const wrapper = mount(TechStackSection, {
            props: { technologies: mockTechnologies },
        })
        expect(wrapper.text()).toContain('skills.title1')
        expect(wrapper.text()).toContain('skills.title2')
    })

    it('groups technologies by category', () => {
        const wrapper = mount(TechStackSection, {
            props: { technologies: mockTechnologies },
        })
        const html = wrapper.html()
        expect(html).toContain('technologies.categories.frontend')
        expect(html).toContain('technologies.categories.backend')
        expect(html).toContain('technologies.categories.automation')
        expect(html).toContain('technologies.categories.languages')
    })

    it('renders technology names', () => {
        const wrapper = mount(TechStackSection, {
            props: { technologies: mockTechnologies },
        })
        const html = wrapper.html()
        expect(html).toContain('Vue.js')
        expect(html).toContain('Laravel')
        expect(html).toContain('n8n')
        expect(html).toContain('TypeScript')
    })

    it('renders technology icons as images', () => {
        const wrapper = mount(TechStackSection, {
            props: { technologies: mockTechnologies },
        })
        const imgs = wrapper.findAll('img')
        expect(imgs.length).toBe(4)
        // Categories are sorted by CATEGORY_ORDER: languages comes before frontend
        expect(imgs[0].attributes('src')).toBe('/ts.svg')
        expect(imgs[1].attributes('src')).toBe('/vue.svg')
    })

    it('shows tech count per category', () => {
        const wrapper = mount(TechStackSection, {
            props: { technologies: mockTechnologies },
        })
        const text = wrapper.text()
        expect(text).toContain('1 TECHS') // each category has 1 tech
    })

    it('displays category accent diamonds', () => {
        const wrapper = mount(TechStackSection, {
            props: { technologies: mockTechnologies },
        })
        const html = wrapper.html()
        // Each category should have a diamond indicator
        expect(html).toContain('rotate-45')
    })

    it('renders without technologies (empty state)', () => {
        const wrapper = mount(TechStackSection, {
            props: { technologies: [] },
        })
        // Should render header but no tech cards
        expect(wrapper.text()).toContain('skills.badge')
        const imgs = wrapper.findAll('img')
        expect(imgs.length).toBe(0)
    })

    it('handles technologies with many items (marquee)', () => {
        const manyTechs = Array.from({ length: 8 }, (_, i) => ({
            name: `Tech ${i + 1}`,
            category: 'frontend',
            logo_url: `/tech${i + 1}.svg`,
            invert_dark: false,
        }))
        const wrapper = mount(TechStackSection, {
            props: { technologies: manyTechs },
        })
        // With 8 items in one category, it should use marquee
        const html = wrapper.html()
        expect(html).toContain('Tech 1')
        expect(html).toContain('Tech 8')
    })

    it('sorts categories by CATEGORY_ORDER', () => {
        const unordered = [
            { name: 'AWS', category: 'infrastructure', logo_url: '/aws.svg', invert_dark: false },
            { name: 'Vue', category: 'frontend', logo_url: '/vue.svg', invert_dark: false },
        ]
        const wrapper = mount(TechStackSection, {
            props: { technologies: unordered },
        })
        const html = wrapper.html()
        // frontend should appear before infrastructure in CATEGORY_ORDER
        const frontendIdx = html.indexOf('technologies.categories.frontend')
        const infraIdx = html.indexOf('technologies.categories.infrastructure')
        expect(frontendIdx).toBeGreaterThan(-1)
        expect(infraIdx).toBeGreaterThan(-1)
        expect(frontendIdx).toBeLessThan(infraIdx)
    })
})
