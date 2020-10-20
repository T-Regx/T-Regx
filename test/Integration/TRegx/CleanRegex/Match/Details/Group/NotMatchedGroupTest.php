<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details\Group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function testGetText()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'first', but the group was not matched");

        // when
        $matchGroup->text();
    }

    /**
     * @test
     */
    public function testMatch()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $matches = $matchGroup->matched();

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function testEquals_shouldAwaysBeNotEqual()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $equals = $matchGroup->equals("any");

        // then
        $this->assertFalse($equals);
    }

    /**
     * @test
     */
    public function testGetOffset()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call offset() for group 'first', but the group was not matched");

        // when
        $matchGroup->offset();
    }

    /**
     * @test
     */
    public function testGetTail()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call tail() for group 'first', but the group was not matched");

        // when
        $matchGroup->tail();
    }

    /**
     * @test
     */
    public function testReplace()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call replace() for group 'first', but the group was not matched");

        // when
        $matchGroup->replace('');
    }

    /**
     * @test
     */
    public function testGetByteOffset()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteOffset() for group 'first', but the group was not matched");

        // when
        $matchGroup->byteOffset();
    }

    /**
     * @test
     */
    public function testGetByteTail()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteTail() for group 'first', but the group was not matched");

        // when
        $matchGroup->byteTail();
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $name = $matchGroup->name();

        // then
        $this->assertEquals('first', $name);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $index = $matchGroup->index();

        // then
        $this->assertEquals(1, $index);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orThrow()
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected to get group 'first', but it was not matched");

        // when
        $matchGroup->orThrow(InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orElse()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $orElse = $matchGroup->orElse(function (NotMatched $notMatched) {
            return $notMatched->subject();
        });

        // then
        $this->assertEquals('My super subject', $orElse);
    }

    /**
     * @test
     */
    public function shouldControlMatched_orReturn()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $orReturn = $matchGroup->orReturn(13);

        // then
        $this->assertEquals(13, $orReturn);
    }

    private function matchGroup(): NotMatchedGroup
    {
        $subject = new Subject('My super subject');
        return new NotMatchedGroup(
            new GroupDetails('first', 1, 'first', new EagerMatchAllFactory(new RawMatchesOffset([]))),
            new GroupExceptionFactory($subject, 'first'),
            new NotMatchedOptionalWorker(
                new GroupMessage('first'),
                $subject,
                new NotMatched(new RawMatches([]), $subject)
            )
        );
    }
}
