<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\toInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchImplTest extends TestCase
{
    /**
     * @test
     * @dataProvider validIntegers
     * @param string $string
     * @param int $expected
     */
    public function shouldParseInt(string $string, int $expected)
    {
        // given
        $result = pattern('(?<name>-?\w+)')
            ->match($string)
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
            });

        // then
        $this->assertEquals($expected, $result);
    }

    public function validIntegers()
    {
        return [
            ['1', 1],
            ['-1', -1],
            ['0', 0],
            ['000', 0],
            ['011', 11],
            ['0001', 1],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_forPseudoInteger_becausePhpSucks()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group '1', but '1e3' is not a valid integer");

        // given
        pattern('(.*)', 's')
            ->match('1e3')
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldParseInt_byName()
    {
        // given
        $result = pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->map(function (Detail $detail) {
                // when
                return $detail->group('value')->toInt();
            });

        // then
        $this->assertEquals([12, 14, 13, 19, 18, 2], $result);
    }

    /**
     * @test
     */
    public function shouldParseInt_byIndex()
    {
        // given
        $result = pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->map(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
            });

        // then
        $this->assertEquals([12, 14, 13, 19, 18, 2], $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger_byName()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group 'name', but 'Foo' is not a valid integer");

        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('name')->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger_byIndex()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group '1', but 'Foo' is not a valid integer");

        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call toInt() for group 'missing', but the group was not matched");

        // given
        pattern('(?<name>\w+)(?<missing>\d+)?')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('missing')->toInt();
            });
    }
}
