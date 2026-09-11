<?php

namespace Mapado\PrettyTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class UTCDateTimeType extends DateTimeType
{
    private static ?\DateTimeZone $utc = null;

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof \DateTime) {
            $value->setTimezone(self::getUtc());
        } elseif ($value instanceof \DateTimeImmutable) {
            // DateTimeType only accepts mutable dates: format the immutable one here
            return $value->setTimezone(self::getUtc())->format($platform->getDateTimeFormatString());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?\DateTime
    {
        if (null === $value || $value instanceof \DateTime) {
            return $value;
        }

        $converted = is_string($value)
            ? \DateTime::createFromFormat($platform->getDateTimeFormatString(), $value, self::getUtc())
            : false;

        if (false === $converted) {
            throw new ConversionException(sprintf(
                'Could not convert database value "%s" to Doctrine Type datetime. Expected format "%s".',
                is_string($value) ? $value : get_debug_type($value),
                $platform->getDateTimeFormatString(),
            ));
        }

        return $converted;
    }

    private static function getUtc(): \DateTimeZone
    {
        return self::$utc ??= new \DateTimeZone('UTC');
    }
}
