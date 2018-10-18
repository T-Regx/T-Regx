<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\CleanRegex\FlagNotAllowedException;
use TRegx\CleanRegex\Internal\FlagsValidator;
use PHPUnit\Framework\TestCase;

class FlagsValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAllowFlags()
    {
        // given
        $flags = new FlagsValidator();

        // when
        $flags->validate('mi');

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldEmptyBeAllowed()
    {
        // given
        $flags = new FlagsValidator();

        // when
        $flags->validate('');

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotAllowWhitespace()
    {
        // given
        $flags = new FlagsValidator();

        // then
        $this->expectException(FlagNotAllowedException::class);
        $this->expectExceptionMessage("Regular expression flag: ' ' is not allowed");

        // when
        $flags->validate(' i');
    }

    /**
     * @test
     * @dataProvider invalidFlags
     * @expectedException \TRegx\CleanRegex\Exception\CleanRegex\FlagNotAllowedException
     * @param string $flag
     */
    public function shouldNotAllowInvalidFlags(string $flag)
    {
        // given
        $flags = new FlagsValidator();

        // when
        $flags->validate($flag);
    }

    public function invalidFlags()
    {
        return [
            ['+g'],
            ['-g'],
            ['/'],
            ['G'],
        ];
    }

    /**
     * @test
     */
    public function shouldValidateMultipleFlags()
    {
        // given
        $flags = new FlagsValidator();

        // then
        $this->expectException(FlagNotAllowedException::class);
        $this->expectExceptionMessage("Regular expression flags: ['f', 'c'] are not allowed");

        // when
        $flags->validate('fc');
    }
}
