<?php
namespace Test\Danon\CleanRegex;

use Danon\CleanRegex\Match\Match;
use PHPUnit\Framework\TestCase;

class ReadMeTest extends TestCase
{
    /**
     * @test
     */
    public function cleanerApi()
    {
        // when
        $result = pattern('[a-z0-9]')->replace('Hello, world')->with('*');

        // then
        $this->assertEquals('H****, *****', $result);
    }

    /**
     * @test
     */
    public function match()
    {
        // when
        $result = pattern('[aeiouy]')->matches('Computer');

        // then
        $this->assertTrue($result, "Failed asserting that pattern matches the subject");
    }

    /**
     * @test
     */
    public function allMatches()
    {
        // when
        $result = pattern('\d+')->match('192 168 172 14')->all();

        // then
        $this->assertEquals(['192', '168', '172', '14'], $result);
    }

    /**
     * @test
     */
    public function retrieveFirst()
    {
        // when
        $result = pattern('[a-zA-Z]+')->match('Robert likes trains')->first();

        // then
        $this->assertEquals('Robert', $result);
    }

    /**
     * @test
     */
    public function retrieveFirstWithCallback()
    {
        // given
        $result = null;

        // when
        pattern('[a-z]+$')
            ->match('Robert likes trains')
            ->first(function (Match $match) use (&$result) {
                $result = $match . ' at ' . $match->offset();
            });

        // then
        $this->assertEquals('trains at 13', $result);
    }

    /**
     * @test
     */
    public function iterate()
    {
        // when + then
        pattern('\d+')
            ->match('192 168 172 14')
            ->iterate(function (Match $match) {

                if ($match->match() != '172') return;

                // gets the match
                $this->assertEquals("172", $match->match());
                $this->assertEquals("172", (string)$match);

                // gets the match offset
                $this->assertEquals(8, $match->offset());

                // gets the group index
                $this->assertEquals(2, $match->index());

                // gets other groups
                $this->assertEquals(['192', '168', '172', '14'], $match->all());
            });
    }

    /**
     * @test
     */
    public function map()
    {
        // when
        $map = pattern('\d+')
            ->match('192 168 172 14')
            ->map(function (Match $match) {
                return $match->match() * 2;
            });

        // then
        $this->assertEquals([384, 336, 344, 28], $map);
    }

    /**
     * @test
     */
    public function countVowels()
    {
        // when
        $amount = pattern('[aeiouy]')->count('Computer');

        // then
        $this->assertEquals('There are 3 vowels', "There are $amount vowels");
    }

    /**
     * @test
     */
    public function replaceStrings()
    {
        // when
        $result = pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('*');

        // then
        $this->assertEquals('P. Sh*man, 42 Wall*y w*, Sydn*', $result);
    }

    /**
     * @test
     */
    public function replaceCallbacks()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(com|org)';
        $subject = 'Links: http://google.com and http://other.org.';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->callback(function (Match $match) {
                return $match->group('name');
            });

        // then
        $this->assertEquals($result, 'Links: google and other.');
    }

    /**
     * @test
     */
    public function validatePattern()
    {
        // when
        $result = pattern('/[a-z/')->valid();

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function validateWithoutDelimiters()
    {
        // when
        $result = pattern('welcome')->valid();

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function validateWithDelimiters()
    {
        // when
        $result = pattern('/[a-z]/')->valid();

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function quotePattern()
    {
        // when
        $result = pattern('.*[a-z]?')->quote();

        // then
        $this->assertEquals('\.\*\[a\-z\]\?', $result);
    }
}
