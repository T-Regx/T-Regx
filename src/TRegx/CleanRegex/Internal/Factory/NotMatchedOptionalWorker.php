<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Match\Details\NotMatched;
use function call_user_func;

class NotMatchedOptionalWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var string */
    private $subject;
    /** @var NotMatched */
    private $notMatched;

    public function __construct(NotMatchedMessage $message, string $subject, NotMatched $notMatched)
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->notMatched = $notMatched;
    }

    public function orThrow(string $exceptionClassName): Throwable
    {
        return (new SignatureExceptionFactory($exceptionClassName, $this->message))->create($this->subject);
    }

    public function orElse(callable $producer)
    {
        return call_user_func($producer, $this->notMatched);
    }
}
