<?php

namespace Tests;

use Blank\Comment;

class CommentTest extends TestCase {
    public function test_get_uri() {
        $instance = new Comment($this->create_theme_instance_without_features());

        $this->assertInstanceOf(Comment::class, $instance);
    }
}
