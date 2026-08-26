import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import MarkdownPreview from '@/Components/MarkdownPreview.vue'

describe('MarkdownPreview.vue', () => {
    it('renders empty state when content is empty', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '' },
        })
        expect(wrapper.text()).toContain('Sin contenido aún')
    })

    it('renders plain text content', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: 'Hello World' },
        })
        expect(wrapper.html()).toContain('Hello World')
    })

    it('renders markdown headings as HTML', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '# Title\n## Subtitle' },
        })
        const html = wrapper.html()
        expect(html).toContain('<h1')
        expect(html).toContain('Title')
        expect(html).toContain('<h2')
        expect(html).toContain('Subtitle')
    })

    it('renders bold and italic text', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '**bold** and *italic*' },
        })
        const html = wrapper.html()
        expect(html).toContain('<strong')
        expect(html).toContain('bold')
        expect(html).toContain('<em')
        expect(html).toContain('italic')
    })

    it('renders links as anchor tags', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '[Click here](https://example.com)' },
        })
        const html = wrapper.html()
        expect(html).toContain('<a')
        expect(html).toContain('href="https://example.com"')
        expect(html).toContain('Click here')
    })

    it('renders unordered lists', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '- Item 1\n- Item 2\n- Item 3' },
        })
        const html = wrapper.html()
        expect(html).toContain('<ul')
        expect(html).toContain('Item 1')
        expect(html).toContain('Item 2')
    })

    it('renders ordered lists', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '1. First\n2. Second' },
        })
        const html = wrapper.html()
        expect(html).toContain('<ol')
        expect(html).toContain('First')
        expect(html).toContain('Second')
    })

    it('renders blockquotes', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '> A wise quote' },
        })
        const html = wrapper.html()
        expect(html).toContain('<blockquote')
        expect(html).toContain('A wise quote')
    })

    it('renders code blocks', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '```js\nconsole.log("hi")\n```' },
        })
        const html = wrapper.html()
        expect(html).toContain('<pre')
        expect(html).toContain('<code')
        expect(html).toContain('console')
    })

    it('renders inline code', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: 'Use the `foo()` function' },
        })
        const html = wrapper.html()
        expect(html).toContain('<code')
        expect(html).toContain('foo()')
    })

    it('renders horizontal rules', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: 'Above\n\n---\n\nBelow' },
        })
        const html = wrapper.html()
        expect(html).toContain('<hr')
    })

    it('renders tables', () => {
        const wrapper = mount(MarkdownPreview, {
            props: {
                content: [
                    '| Name  | Role  |',
                    '|-------|-------|',
                    '| Alice | Admin |',
                    '| Bob   | User  |',
                ].join('\n'),
            },
        })
        const html = wrapper.html()
        expect(html).toContain('<table')
        expect(html).toContain('Alice')
        expect(html).toContain('Admin')
    })

    it('renders images', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: '![Alt text](https://example.com/img.png)' },
        })
        const html = wrapper.html()
        expect(html).toContain('<img')
        expect(html).toContain('src="https://example.com/img.png"')
        expect(html).toContain('alt="Alt text"')
    })

    it('handles GFM line breaks (two trailing spaces)', () => {
        const wrapper = mount(MarkdownPreview, {
            props: { content: 'Line 1  \nLine 2' },
        })
        const html = wrapper.html()
        // With breaks:true, newlines become <br>
        expect(html).toContain('Line 1')
        expect(html).toContain('Line 2')
    })

    it('handles long content without crashing', () => {
        const longText = Array.from({ length: 50 }, (_, i) => `# Heading ${i}\n\nParagraph ${i} with some text.\n`)
            .join('\n')
        const wrapper = mount(MarkdownPreview, {
            props: { content: longText },
        })
        expect(wrapper.html()).toContain('Heading 0')
        expect(wrapper.html()).toContain('Heading 49')
    })

    it('removes executable HTML from markdown content', () => {
        const wrapper = mount(MarkdownPreview, {
            props: {
                content: '<script>alert(1)</script><img src="x" onerror="alert(2)"><a href="javascript:alert(3)">link</a>',
            },
        })

        const html = wrapper.html()
        expect(html).not.toContain('<script')
        expect(html).not.toContain('onerror')
        expect(html).not.toContain('javascript:')
        expect(html).toContain('link')
    })
})
