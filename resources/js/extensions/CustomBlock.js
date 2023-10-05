import {mergeAttributes, Node} from "@tiptap/core"

export const CustomBlock = Node.create({
    name: 'customBlock',
    content: 'inline+',
    group: 'block',
    atom: true,
    draggable: true,
    selectable: false,
    isolating: true,
    allowGapCursor: true,
    addAttributes() {
        return {
            type: {
                default: null,
                parseHTML: element => element.getAttribute('data-block-type'),
                renderHTML: attributes => {
                    return {
                        'data-block-type': attributes.type
                    }
                }
            }
        }
    },
    parseHTML() {
        return [
            {
                tag: 'custom-block',
            }
        ]
    },
    renderHTML({ HTMLAttributes }) {
        return ['custom-block', mergeAttributes(HTMLAttributes)]
    },
    addNodeView() {
        return ({node, extension}) => {
            const dom = document.createElement('div')
            dom.setAttribute('data-block-type', node.attrs.type)
            dom.setAttribute('wire:ignore.self', 'true')

            extension.config.getBlock(node.attrs.type)
                .then((html) => {
                    dom.innerHTML = html
                })

            return {
                dom,
            }
        }
    },
    addCommands() {
        return {
            insertBlock: (attributes) => ({ commands }) => {
                return commands.setNode(this.name, attributes)
            },
            removeBlock: () => ({ commands }) => {
                return commands.removeNode(this.name)
            }
        }
    },
    async getBlock(path) {
        let domain = (new URL(window.location.href))
        return await fetch(`${domain.origin}/tiptap-editor/blocks/${path}`, {
            headers: {
                'Accept': 'application/json'
            }
        }).then((res) => res.json())
    }
})