<?php

declare(strict_types=1);

namespace Mapado\PrettyTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\DBAL\Types\PhpDateMappingType;
use Doctrine\DBAL\Types\Type;

/**
 * Adapts DateImmutableType to \DateTime,
 * as DBAL 4.6 deprecates its own DateType.
 */
class MutableDateType extends Type implements PhpDateMappingType
{
    public function __construct(
        private readonly DateImmutableType $inner = new DateImmutableType(),
    ) {
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->inner->getSQLDeclaration($column, $platform);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof \DateTime) {
            $value = \DateTimeImmutable::createFromMutable($value);
        }

        return $this->inner->convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?\DateTime
    {
        if ($value instanceof \DateTime) {
            return $value;
        }

        $value = $this->inner->convertToPHPValue($value, $platform);

        return $value instanceof \DateTimeImmutable ? \DateTime::createFromImmutable($value) : $value;
    }
}
