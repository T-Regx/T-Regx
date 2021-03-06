<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\atLeast;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIgnore_first_atLeast_once()
    {
        // when
        $result = pattern('Foo')->replace('Foo Bar Bar Bar')->first()->atLeast()->with('Bar');

        // then
        $this->assertSame('Bar Bar Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldIgnore_first_atLeast_two()
    {
        // when
        $result = pattern('Foo')->replace('Foo Foo Bar Bar')->first()->atLeast()->with('Bar');

        // then
        $this->assertSame('Bar Foo Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_first_atLeast_none()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at least 1 replacement(s), but 0 replacement(s) were actually performed');

        // when
        pattern('Foo')->replace('Bar Bar Bar Bar')->first()->atLeast()->with('Bar');
    }

    /**
     * @test
     */
    public function shouldIgnore_two_atLeast_twice()
    {
        // when
        $result = pattern('Foo')->replace('Foo Foo Bar Bar')->only(2)->atLeast()->with('Bar');

        // then
        $this->assertSame('Bar Bar Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_two_atLeast_none()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at least 2 replacement(s), but 1 replacement(s) were actually performed');

        // when
        pattern('Foo')->replace('Foo Bar Bar Bar')->only(2)->atLeast()->with('Bar');
    }

    /**
     * @test
     */
    public function shouldIgnore_two_atLeast_thrice()
    {
        // when
        $result = pattern('Foo')->replace('Foo Foo Foo Bar')->only(2)->atLeast()->with('Bar');

        // then
        $this->assertSame('Bar Bar Foo Bar', $result);
    }
}
