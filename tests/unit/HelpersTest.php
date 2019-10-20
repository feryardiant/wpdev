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
                [
                    'a',
                    'b',
                    'c',
                    'd',
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
                '<hr/>',
                'hr',
                [],
                true,
            ],
            // make_html_tag('hr', [], true)
            [
                '<img src="/path/to/img.jpg"/>',
                'img',
                ['src' => '/path/to/img.jpg'],
                true,
            ],
            // make_html_tag('img', ['src' => '...', 'class' => '...'], true)
            [
                '<img src="/path/to/img.jpg" class="img is-rounded"/> <!-- .img -->',
                'img',
                ['src' => '/path/to/img.jpg', 'class' => 'img is-rounded'],
                true,
            ],
            // make_html_tag('img', ['src' => '...', 'id' => '...'], true)
            [
                '<img src="/path/to/img.jpg" id="img-id"/> <!-- #img-id -->',
                'img',
                ['src' => '/path/to/img.jpg', 'id' => 'img-id'],
                true,
            ],
            // make_html_tag('img', ['src' => '...', '...'], true)
            [
                '<img src="/path/to/img.jpg" id="img-id" class="foo bar"/> <!-- #img-id -->',
                'img',
                ['src' => '/path/to/img.jpg', 'id' => 'img-id', 'class' => 'foo bar' ],
                true,
            ],
            // make_html_tag('div', ['class' => '...'])
            [
                '<div class="hentry"></div> <!-- .hentry -->',
                'div',
                ['class' => 'hentry'],
            ],
            // make_html_tag('div', ['class' => '...'], 'Some Text')
            [
                '<div class="hentry">Some Text</div> <!-- .hentry -->',
                'div',
                ['class' => 'hentry'],
                'Some Text'
            ],
        ];
    }
}
