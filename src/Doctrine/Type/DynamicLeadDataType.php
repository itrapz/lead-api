<?php

declare(strict_types=1);

namespace App\Doctrine\Type;

use App\DTO\DynamicLeadData;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DynamicLeadDataType extends Type
{
    const NAME = 'dynamic_lead_data';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|false
    {
        return json_encode($value instanceof DynamicLeadData ? $value->toArray() : $value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return DynamicLeadData::fromArray(json_decode($value, true) ?? []);
    }

    public function getName()
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
