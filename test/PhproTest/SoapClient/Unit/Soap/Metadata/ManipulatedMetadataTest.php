<?php

declare(strict_types=1);

namespace PhproTest\SoapClient\Unit\Soap\Metadata;

use Phpro\SoapClient\Soap\Metadata\ManipulatedMetadata;
use Phpro\SoapClient\Soap\Metadata\Manipulators\MethodsManipulatorChain;
use Phpro\SoapClient\Soap\Metadata\Manipulators\MethodsManipulatorInterface;
use Phpro\SoapClient\Soap\Metadata\Manipulators\TypesManipulatorChain;
use Phpro\SoapClient\Soap\Metadata\Manipulators\TypesManipulatorInterface;
use PHPUnit\Framework\TestCase;
use Soap\Engine\Metadata\Collection\MethodCollection;
use Soap\Engine\Metadata\Collection\ParameterCollection;
use Soap\Engine\Metadata\Collection\PropertyCollection;
use Soap\Engine\Metadata\Collection\TypeCollection;
use Soap\Engine\Metadata\Metadata;
use Soap\Engine\Metadata\Model\Method;
use Soap\Engine\Metadata\Model\Type;
use Soap\Engine\Metadata\Model\XsdType;

class ManipulatedMetadataTest extends TestCase
{
    /**
     * @var Metadata
     */
    private $metadata;

    protected function setUp(): void
    {
        $this->metadata = new class implements Metadata
        {
            public function getTypes(): TypeCollection
            {
                return new TypeCollection();
            }

            public function getMethods(): MethodCollection
            {
                return new MethodCollection();
            }
        };
    }

    /** @test */
    public function it_is_a_metdata_object(): void
    {
        self::assertInstanceOf(Metadata::class, new ManipulatedMetadata(
            $this->metadata,
            new MethodsManipulatorChain(),
            new TypesManipulatorChain()
        ));
    }

    /** @test */
    public function it_proxies_everything_on_no_manipulators(): void
    {
        $manipulator = new ManipulatedMetadata(
            $this->metadata,
            new MethodsManipulatorChain(),
            new TypesManipulatorChain()
        );

        self::assertEquals($this->metadata->getMethods(), $manipulator->getMethods());
        self::assertEquals($this->metadata->getTypes(), $manipulator->getTypes());
    }

    /** @test */
    public function it_can_manipulate_methods(): void
    {
        $manipulatedMeta = new ManipulatedMetadata(
            $this->metadata,
            new class implements MethodsManipulatorInterface {
                public function __invoke(MethodCollection $allMethods): MethodCollection
                {
                    return new MethodCollection(
                        new Method('method', new ParameterCollection(), XsdType::create('Response'))
                    );
                }

            },
            new TypesManipulatorChain()
        );

        self::assertEquals(
            new MethodCollection(
                new Method('method', new ParameterCollection(), XsdType::create('Response'))
            ),
            $manipulatedMeta->getMethods()
        );
    }

    /** @test */
    public function it_can_manipulate_types(): void
    {
        $manipulatedMeta = new ManipulatedMetadata(
            $this->metadata,
            new MethodsManipulatorChain(),
            new class implements TypesManipulatorInterface {
                public function __invoke(TypeCollection $allTypes): TypeCollection
                {
                    return new TypeCollection(new Type(XsdType::create('Type'), new PropertyCollection()));
                }
            }
        );

        self::assertEquals(
            new TypeCollection(new Type(XsdType::create('Type'), new PropertyCollection())),
            $manipulatedMeta->getTypes()
        );
    }
}
