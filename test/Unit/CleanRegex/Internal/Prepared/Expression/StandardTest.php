<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;

class StandardTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $interpretation = new Standard('foo', 'i');

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new Definition('/foo/i', 'foo'), $definition);
    }

    /**
     * @test
     */
    public function shouldChooseDelimiter()
    {
        // given
        $interpretation = new Standard('foo/bar', 'x');

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new Definition('#foo/bar#x', 'foo/bar'), $definition);
    }

    /**
     * @test
     */
    public function shouldThrowForTrailingEscape()
    {
        // given
        $interpretation = new Standard('bar\\', 'x');

        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $interpretation->definition();
    }

    /**
     * @test
     */
    public function shouldNotUseDuplicateFlags()
    {
        // given
        $interpretation = new Standard('foo', 'mm');

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new Definition('/foo/m', 'foo'), $definition);
    }
}
