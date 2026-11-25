<?php

namespace Mapado\PrettyTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\PhpDateTimeMappingType;
use Doctrine\DBAL\Types\Type;

/**
 * Adapts UTCDateTimeImmutableType to \DateTime,
 * as DBAL 4.6 deprecates its own mutable types.
 */
class UTCDateTimeType extends Type implements PhpDateTimeMappingType
{
    private static ?\DateTimeZone $utc = null;

    public function __construct(
        private readonly UTCDateTimeImmutableType $inner = new UTCDateTimeImmutableType(),
    ) {
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->inner->getSQLDeclaration($column, $platform);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof \DateTime) {
            $value = \DateTimeImmutable::createFromMutable($value->setTimezone(self::getUtc()));
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

    private static function getUtc(): \DateTimeZone
    {
        return self::$utc ??= new \DateTimeZone('UTC');
    }
}
