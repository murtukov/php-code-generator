<?php

use Murtukov\PHPCodeGenerator\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private string $oneLineText = 'Hi! My name is Vasilis Kehagias!';
    private string $multilineText = "I am from Greece.\nI'm learning German.";

    /**
     * @test
     */
    public function starCommentMultiline()
    {
        $expected = <<<CODE
        /*
         * $this->oneLineText
         */
        CODE;

        $this->assertEquals(
            $expected,
            (string) Comment::stars($this->oneLineText)
        );
    }

    /**
     * @test
     */
    public function hashComment()
    {
        $this->assertEquals(
            "# $this->oneLineText",
            (string) Comment::hash($this->oneLineText)
        );
    }

    /**
     * @test
     */
    public function hashCommentMultiline()
    {
        $this->assertEquals(
            "# $this->oneLineText",
            (string) Comment::hash($this->oneLineText)
        );
    }
}
