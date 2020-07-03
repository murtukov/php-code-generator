<?php

use Murtukov\PHPCodeGenerator\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private string $firstLine = 'Hi! My name is Vasilis Kehagias!';
    private string $lastLine = 'Bye!';

    /**
     * @test
     */
    public function starCommentMultiline()
    {
        $expected = <<<CODE
        /*
         * $this->firstLine
         */
        CODE;

        $this->assertEquals(
            $expected,
            Comment::block($this->firstLine)->generate()
        );
    }

    /**
     * @test
     */
    public function hashComment()
    {
        $this->assertEquals(
            "# $this->firstLine",
            Comment::hash($this->firstLine)->generate()
        );
    }

    /**
     * @test
     */
    public function hashCommentMultiline()
    {
        $comment = Comment::hash($this->firstLine);
        $comment->addEmptyLine();
        $comment->addLine($this->lastLine);

        $this->expectOutputString(<<<CODE
        # $this->firstLine
        # 
        # $this->lastLine
        CODE);

        echo $comment->generate();
    }
}
