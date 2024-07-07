<?php
namespace Application\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EnumType extends Type
{
    const ENUM = 'enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // Adjust ENUM values according to your database schema
        return "ENUM('value1', 'value2', 'value3')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value; // Assuming no conversion needed for PHP values
    }

    public function getName()
    {
        return self::ENUM;
    }
}