<?php

namespace FilamentTiptapEditor\Extensions\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class Vimeo extends Node
{
    public static $name = 'vimeo';

    public function addOptions(): array
    {
        return [
            'inline' => false,
            'HTMLAttributes' => [],
            'allowFullscreen' => true,
            'width' => 640,
            'height' => 480,
        ];
    }

    public function addAttributes(): array
    {
        return [
            'style' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('style');
                },
            ],
            'src' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('src');
                },
            ],
            'width' => [
                'default' => $this->options['width'],
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('width');
                },
            ],
            'height' => [
                'default' => $this->options['height'],
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('height');
                },
            ],
            'autoplay' => [
                'default' => 0,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('autoplay');
                },
            ],
            'loop' => [
                'default' => 0,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('loop');
                },
            ],
            'title' => [
                'default' => 0,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('title');
                },
            ],
            'byline' => [
                'default' => 0,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('byline');
                },
            ],
            'portrait' => [
                'default' => 0,
                'parseHTML' => function ($DOMNode) {
                    return $DOMNode->firstChild->getAttribute('portrait');
                },
            ],
            'responsive' => [
                'default' => true,
                'parseHTML' => function ($DOMNode) {
                    return str_contains($DOMNode->getAttribute('class'), 'responsive') ?? false;
                },
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div[data-vimeo-video]',
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'div',
            [
                'data-vimeo-video' => true,
                'class' => $node->attrs->responsive ? 'responsive' : null,
            ],
            [
                'iframe',
                HTML::mergeAttributes($this->options['HTMLAttributes'], [
                    'src' => $node->attrs->src,
                    'width' => $this->options['width'],
                    'height' => $this->options['height'],
                    'allowfullscreen' => true,
                    'allow' => 'autoplay; fullscreen; picture-in-picture',
                    'style' => $node->attrs->style,
                ]),
            ],
        ];
    }
}
