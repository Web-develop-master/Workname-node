<?php

namespace spec\Phpro\SoapClient\CodeGenerator\Context;

use Laminas\Code\Generator\ClassGenerator;
use Phpro\SoapClient\CodeGenerator\Context\ClientContext;
use Phpro\SoapClient\CodeGenerator\Context\TypeContext;
use PhpSpec\ObjectBehavior;

/**
 * Class TypeContextSpec
 *
 * @package spec\Phpro\SoapClient\CodeGenerator\Context
 * @mixin TypeContext
 */
class ClientContextSpec extends ObjectBehavior
{
    function let(ClassGenerator $class)
    {
        $this->beConstructedWith($class, 'MyClient', 'App\Client');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ClientContext::class);
    }

    function is_has_a_name()
    {
        $this->getName()->shouldBe('MyClient');
    }

    function it_has_a_namespace()
    {
        $this->getNamespace()->shouldBe('App\Client');
    }

    function it_has_a_class(ClassGenerator $class)
    {
        $this->getClass()->shouldBe($class);
    }
}
