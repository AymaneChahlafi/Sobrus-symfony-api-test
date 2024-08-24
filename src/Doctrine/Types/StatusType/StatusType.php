<?php
namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class StatusType extends Type
{
    public const STATUS_ENUM = 'status_enum'; // Custom type name

    private const STATUSES = [
        'draft',
        'published',
        'deleted',
    ];

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = implode("','", self::STATUSES);
        return "ENUM('" . $values . "')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, self::STATUSES, true)) {
            throw new \InvalidArgumentException("Invalid status value");
        }

        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, self::STATUSES, true)) {
            throw new \InvalidArgumentException("Invalid status value");
        }

        return $value;
    }

    public function getName()
    {
        return self::STATUS_ENUM;
    }
}
