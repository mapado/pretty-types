<?php

declare(strict_types=1);

namespace Mapado\PrettyTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;

class UTCDateTimeImmutableType extends DateTimeImmutableType
{
    private static ?\DateTimeZone $utc = null;

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof \DateTimeImmutable) {
            $value = $value->setTimezone(self::getUtc());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?\DateTimeImmutable
    {
        if (null === $value || $value instanceof \DateTimeImmutable) {
            return $value;
        }

        $converted = is_string($value)
            ? \DateTimeImmutable::createFromFormat($platform->getDateTimeFormatString(), $value, self::getUtc())
            : false;

        if (false === $converted) {
            throw new ConversionException(sprintf(
                'Could not convert database value "%s" to Doctrine Type datetime_immutable. Expected format "%s".',
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
