<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetOffset_first()
    {
        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]{3,})')
            ->match('Cześć, Tomek')
            ->first(function (Detail $detail) {
                // when
                $offset = $detail->group('lowercase')->offset();
                $byteOffset = $detail->group('lowercase')->byteOffset();

                // then
                $this->assertEquals(8, $offset);
                $this->assertEquals(10, $byteOffset);
            });
    }

    /**
     * @test
     */
    public function shouldGetOffset_forEach()
    {
        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]{3,})')
            ->match('Cześć, Tomek i Kamil')
            ->forEach(function (Detail $detail) {
                if ($detail->index() !== 1) return;

                // when
                $offset = $detail->group('lowercase')->offset();
                $byteOffset = $detail->group('lowercase')->byteOffset();

                // then
                $this->assertEquals(16, $offset);
                $this->assertEquals(18, $byteOffset);
            });
    }
}
