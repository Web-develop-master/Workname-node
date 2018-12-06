<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Soap\Engine\Metadata\Model;

class Type
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Property[]
     */
    private $properties = [];

    public function __construct(string $name, array $properties)
    {
        $this->name = $name;
        foreach ($properties as $property) {
            $this->addProperty($property);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function addProperty(Property $property)
    {
        $this->properties[] = $property;
    }
}
