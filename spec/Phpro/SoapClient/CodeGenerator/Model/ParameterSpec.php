<?php

namespace spec\Phpro\SoapClient\CodeGenerator\Model;

use Phpro\SoapClient\CodeGenerator\Model\Parameter;
use PhpSpec\ObjectBehavior;
use Soap\Engine\Metadata\Model\TypeMeta;

/**
 * Class ParameterSpec
 *
 * @package spec\Phpro\SoapClient\CodeGenerator\Model
 * @mixin Parameter
 */
class ParameterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('MyParameter', 'MyParameterType', 'MyNamespace', new TypeMeta());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Parameter::class);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('MyParameter');
    }

    function is_has_a_namespace()
    {
        $this->getNamespace()->shouldBe('MyParameterType');
    }

    function it_returns_an_array()
    {
        $this->toArray()->shouldBe(
            [
                'name' => 'MyParameter',
                'type' => '\\MyNamespace\\MyParameterType',
            ]
        );
    }

    public function it_has_type_meta(): void
    {
        $this->getMeta()->shouldBeLike(new TypeMeta());
    }
}
