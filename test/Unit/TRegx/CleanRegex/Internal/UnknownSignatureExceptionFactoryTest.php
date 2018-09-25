<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use Error;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Test\Utils\ClassWithDefaultConstructor;
use Test\Utils\ClassWithoutSuitableConstructor;
use Test\Utils\ClassWithStringParamConstructor;
use Test\Utils\ClassWithTwoStringParamsConstructor;
use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\ClassExpectedException;
use TRegx\CleanRegex\Exception\CleanRegex\NoSuitableConstructorException;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\UnknownSignatureExceptionFactory;
use Utils\ClassWithErrorInConstructor;

class UnknownSignatureExceptionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onNotExistingClass()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory('Namespace\NoSuchClass', new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("Class 'Namespace\NoSuchClass' does not exists");

        // when
        $factory->create('');
    }

    /**
     * @test
     */
    public function shouldThrow_onInterface()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(Throwable::class, new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("'Throwable' is not a class, but an interface");

        // when
        $factory->create('');
    }

    /**
     * @test
     */
    public function shouldThrow_onClassThatIsNotThrowable()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(stdClass::class, new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("Class 'stdClass' is not throwable");

        // when
        $factory->create('');
    }

    /**
     * @test
     */
    public function shouldThrow_onClassWithoutSuitableConstructor()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithoutSuitableConstructor::class, new FirstMatchMessage());

        // then
        $this->expectException(NoSuitableConstructorException::Class);
        $this->expectExceptionMessage("Class 'Test\Utils\ClassWithoutSuitableConstructor' doesn't have a constructor with supported signature");

        // when
        $factory->create('');
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageAndSubjectParams()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithTwoStringParamsConstructor::class, new FirstMatchMessage());

        // when
        /** @var ClassWithTwoStringParamsConstructor $exception */
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf(ClassWithTwoStringParamsConstructor::class, $exception);
        $this->assertEquals(FirstMatchMessage::MESSAGE, $exception->getMessage());
        $this->assertEquals('my subject', $exception->getSubject());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageParam()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithStringParamConstructor::class, new FirstMatchMessage());

        // when
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf(ClassWithStringParamConstructor::class, $exception);
        $this->assertEquals(FirstMatchMessage::MESSAGE, $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withDefaultConstructor()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithDefaultConstructor::class, new FirstMatchMessage());

        // when
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf(ClassWithDefaultConstructor::class, $exception);
    }

    /**
     * @test
     * @dataProvider exceptions
     * @param string $className
     */
    public function shouldInstantiate_withMessage(string $className)
    {
        // given
        $factory = new UnknownSignatureExceptionFactory($className, new FirstMatchMessage());

        // when
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf($className, $exception);
        $this->assertEquals(FirstMatchMessage::MESSAGE, $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldRethrow_errorWhileInstantiating()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithErrorInConstructor::class, new FirstMatchMessage());

        // then
        $this->expectException(Error::class);

        // when
        $factory->create('my subject');
    }

    public function exceptions()
    {
        return [
            [Exception::class],
            [InvalidArgumentException::class],
            [SubjectNotMatchedException::class],
        ];
    }
}
