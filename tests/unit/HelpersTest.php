<?php

namespace Tests;

use WP_Mock as mock;

use function Blank\Helpers\make_attr_from_array;
use function Blank\Helpers\make_html_tag;
use function Blank\Helpers\normalize_class_attr;

class HelpersTest extends TestCase {
    /**
     * @dataProvider classes_should_normalized
     */
    public function test_normalize_class_attribute($expected, $input) {
        $actual = normalize_class_attr(...$input);

        $this->assertEquals($actual, $expected);
    }

    /**
     * @dataProvider array_should_attributes
     */
    public function test_array_to_html_attribute($expected, $attr, $quoted) {
        $actual = make_attr_from_array($attr, ' ', $quoted);

        $this->assertEquals($actual, $expected);
    }

    /**
     * @dataProvider html_elements_data
     */
    public function test_create_html_element($expected, $tag, $attr = [], $ends = false, $returns = true) {
        mock::userFunction('wp_parse_args', [
            'return_arg' => 0
        ]);

        if ( $returns ) {
            $actual = make_html_tag($tag, $attr, $ends, $returns);
        } else {
            ob_start();

            make_html_tag($tag, $attr, $ends, false);

            $actual = ob_get_clean();
        }

        $this->assertEquals($actual, $expected);
    }

    public function classes_should_normalized() {
        return [
            [
                ['a', 'b', 'c', 'd', 'e'],
                [
                    ['a b', 'c d', 'e'],
                ]
            ],
            [
                ['a', 'b', 'c', 'd', 'e'],
                [
                    ['a b', 'c d'],
                    ['c d', 'e']
                ],
            ],
            [
                ['f', 'a', 'b', 'c', 'd', 'e'],
                [
                    'f',
                    ['a b', 'c d'],
                    ['c d', 'e']
                ],
            ],
            [
                ['a', 'b', 'c', 'd'],
                ['a', 'b', 'c', 'd'],
            ],
            [
                ['a', 'b', 'c', 'd', 'e'],
                [
                    'a',
                    [
                        'a b',
                        ['c d', 'e'],
                    ],
                ],
            ],
        ];
    }

    public function array_should_attributes() {
        return [
            [
                'foo=bar baz=baz',
                ['foo' => 'bar', 'baz' => 'baz'],
                false
            ],
            [
                'class="bar baz bam" baz="baz"',
                ['class' => ['bar', 'baz bam'], 'baz' => 'baz'],
                true
            ],
        ];
    }

    public function html_elements_data() {
        return [
            // make_html_tag('hr', [], true)
            [
                '<hr/>' . PHP_EOL,
                'hr',
                [],
                true,
            ],
            // make_html_tag('hr', [], true)
            [
                '<img src="/path/to/img.jpg"/>' . PHP_EOL,
                'img',
                ['src' => '/path/to/img.jpg'],
                true,
            ],
            // make_html_tag('img', ['src' => '...', 'class' => '...'], true)
            [
                '<img src="/path/to/img.jpg" class="img is-rounded"/> <!-- .img -->' . PHP_EOL,
                'img',
                ['src' => '/path/to/img.jpg', 'class' => 'img is-rounded'],
                true,
            ],
            // make_html_tag('img', ['src' => '...', 'id' => '...'], true)
            [
                '<img src="/path/to/img.jpg" id="img-id"/> <!-- #img-id -->' . PHP_EOL,
                'img',
                ['src' => '/path/to/img.jpg', 'id' => 'img-id'],
                true,
            ],
            // make_html_tag('img', ['src' => '...', '...'], true)
            [
                '<img src="/path/to/img.jpg" id="img-id" class="foo bar"/> <!-- #img-id -->' . PHP_EOL,
                'img',
                ['src' => '/path/to/img.jpg', 'id' => 'img-id', 'class' => 'foo bar' ],
                true,
            ],
            // make_html_tag('div', ['class' => '...'])
            [
                '<div class="hentry"></div> <!-- .hentry -->' . PHP_EOL,
                'div',
                ['class' => 'hentry'],
            ],
            // make_html_tag('div', ['class' => '...'], 'Some Text')
            [
                '<div class="hentry">Some Text</div> <!-- .hentry -->' . PHP_EOL,
                'div',
                ['class' => 'hentry'],
                'Some Text'
            ],
            [
                '<nav class="foo bar">'.PHP_EOL.
                    '<ul class="menu">'.PHP_EOL.
                        '<li class="menu-item">'.PHP_EOL.
                            '<a class="menu-link" href="#">'.PHP_EOL.
                            '<span>Item 1</span>'.PHP_EOL.
                            '</a> <!-- .menu-link -->'.PHP_EOL.
                        '</li> <!-- .menu-item -->'.PHP_EOL.
                        '<li class="menu-item">'.PHP_EOL.
                            '<a class="menu-link" href="#"><span>Item 2</span></a> <!-- .menu-link -->'.PHP_EOL.
                        '</li> <!-- .menu-item -->'.PHP_EOL.
                        '<li class="menu-item">'.PHP_EOL.
                            '<a class="menu-link" href="#">'.PHP_EOL.
                            '<span>Item 3</span>'.PHP_EOL.
                            '</a> <!-- .menu-link -->'.PHP_EOL.
                        '</li> <!-- .menu-item -->'.PHP_EOL.
                    '</ul> <!-- .menu -->'.PHP_EOL.
                '</nav> <!-- .foo -->' .PHP_EOL,
                'nav',
                ['class' => 'foo bar'],
                [
                    'ul' => [
                        'attr' => ['class' => 'menu'],
                        'ends' => [
                            [
                                'tag' => 'li',
                                'attr' => ['class' => 'menu-item'],
                                'ends' => [
                                    'a' => [
                                        'attr' => ['class' => 'menu-link', 'href' => '#'],
                                        'ends' => [
                                            'span' => 'Item 1'
                                        ]
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'li',
                                'attr' => ['class' => 'menu-item'],
                                'ends' => [
                                    'a' => [
                                        'attr' => ['class' => 'menu-link', 'href' => '#'],
                                        'ends' => '<span>Item 2</span>'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'li',
                                'attr' => ['class' => 'menu-item'],
                                'ends' => [
                                    'a' => [
                                        'attr' => ['class' => 'menu-link', 'href' => '#'],
                                        'ends' => [
                                            'span' => [
                                                'ends' => 'Item 3'
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ];
    }
}
