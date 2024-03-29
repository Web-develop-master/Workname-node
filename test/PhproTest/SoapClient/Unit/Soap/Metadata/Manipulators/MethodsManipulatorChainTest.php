<?php

declare(strict_types=1);

namespace PhproTest\SoapClient\Unit\Soap\Metadata\Manipulators;

use Phpro\SoapClient\Soap\Metadata\Manipulators\MethodsManipulatorChain;
use Phpro\SoapClient\Soap\Metadata\Manipulators\MethodsManipulatorInterface;
use PHPUnit\Framework\TestCase;
use Soap\Engine\Metadata\Collection\MethodCollection;
use Soap\Engine\Metadata\Collection\ParameterCollection;
use Soap\Engine\Metadata\Model\Method;
use Soap\Engine\Metadata\Model\XsdType;

class MethodsManipulatorChainTest extends TestCase
{
    /** @test */
    public function it_is_a_method_manipulator(): void
    {
        self::assertInstanceOf(MethodsManipulatorInterface::class, new MethodsManipulatorChain());
    }

    /** @test */
    public function it_does_not_touch_methods_with_no_manipulator(): void
    {
        $methods = new MethodCollection();
        $chain = new MethodsManipulatorChain();
        $result = $chain($methods);

        self::assertSame($methods, $result);
    }

    /** @test */
    public function it_manipulates_methods_collection(): void
    {
        $methods = new MethodCollection();
        $chain = new MethodsManipulatorChain(
            new class implements MethodsManipulatorInterface {
                public function __invoke(MethodCollection $allMethods): MethodCollection
                {
                    return new MethodCollection(...array_merge(
                        iterator_to_array($allMethods),
                        [new Method('method', new ParameterCollection(), XsdType::create('Response'))]
                    ));
                }
            },
            new class implements MethodsManipulatorInterface {
                public function __invoke(MethodCollection $allMethods): MethodCollection
                {
                    return new MethodCollection(...array_merge(
                        iterator_to_array($allMethods),
                        [new Method('method2', new ParameterCollection(), XsdType::create('Response'))]
                    ));
                }
            }
        );
        $result = $chain($methods);

        self::assertNotSame($methods, $result);
        self::assertInstanceOf(MethodCollection::class, $result);
        self::assertCount(2, $result);
        self::assertEquals(
            [
                new Method('method', new ParameterCollection(), XsdType::create('Response')),
                new Method('method2', new ParameterCollection(), XsdType::create('Response')),
            ],
            iterator_to_array($result)
        );
    }
}
