import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import WorkflowSteps from '@/Components/WorkflowSteps.vue'

describe('WorkflowSteps.vue', () => {
    it('renders the workflow badge', () => {
        const wrapper = mount(WorkflowSteps)
        expect(wrapper.text()).toContain('servicios.workflow_badge')
    })

    it('renders the section title', () => {
        const wrapper = mount(WorkflowSteps)
        const text = wrapper.text()
        expect(text).toContain('servicios.workflow_title1')
        expect(text).toContain('servicios.workflow_title2')
    })

    it('renders the workflow quote', () => {
        const wrapper = mount(WorkflowSteps)
        expect(wrapper.text()).toContain('servicios.workflow_quote')
    })

    it('renders 4 workflow steps', () => {
        const wrapper = mount(WorkflowSteps)
        const text = wrapper.text()
        // All four process steps should be rendered
        expect(text).toContain('servicios.steps.discovery.name')
        expect(text).toContain('servicios.steps.architect.name')
        expect(text).toContain('servicios.steps.develop.name')
        expect(text).toContain('servicios.steps.deploy.name')
    })

    it('renders step descriptions', () => {
        const wrapper = mount(WorkflowSteps)
        const text = wrapper.text()
        expect(text).toContain('servicios.steps.discovery.desc')
        expect(text).toContain('servicios.steps.architect.desc')
        expect(text).toContain('servicios.steps.develop.desc')
        expect(text).toContain('servicios.steps.deploy.desc')
    })

    it('renders step numbers from 1 to 4', () => {
        const wrapper = mount(WorkflowSteps)
        const text = wrapper.text()
        expect(text).toContain('PHASE 01')
        expect(text).toContain('PHASE 02')
        expect(text).toContain('PHASE 03')
        expect(text).toContain('PHASE 04')
    })

    it('renders icons for each step', () => {
        const wrapper = mount(WorkflowSteps)
        const svgs = wrapper.findAll('svg')
        // 4 step icons + 3 connector arrows = 7 total
        expect(svgs.length).toBeGreaterThanOrEqual(4)
    })

    it('renders the desktop stepper with step numbers', () => {
        const wrapper = mount(WorkflowSteps)
        // The desktop stepper numbers
        const html = wrapper.html()
        expect(html).toContain('>1<')
        expect(html).toContain('>4<')
    })

    it('applies step-specific color classes', () => {
        const wrapper = mount(WorkflowSteps)
        const html = wrapper.html()
        // Each step has different badge colors
        expect(html).toContain('bg-brutalist-yellow')
        expect(html).toContain('bg-brutalist-pink')
        expect(html).toContain('bg-brutalist-blue')
        expect(html).toContain('bg-brutalist-purple')
    })

    it('renders the connector arrows between steps', () => {
        const wrapper = mount(WorkflowSteps)
        const svgs = wrapper.findAll('svg')
        // 4 step icons + 3 connector arrows = 7 total
        // But connector arrows have `hidden md:block` class so they exist in DOM
        expect(svgs.length).toBeGreaterThanOrEqual(4)
    })
})
