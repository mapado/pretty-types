<?php

namespace Mapado\PrettyTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;

/**
 * Type generating pretty printed json objects values
 *
 * @author Thomas di Luccio <thomas.diluccio@mapado.com>
 */
class JsonPrettyType extends JsonType
{
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        try {
            return json_encode($value, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new ConversionException(
                sprintf(
                    'Could not convert PHP type "%s" to "json". An error was triggered by the serialization: %s',
                    get_debug_type($value),
                    $exception->getMessage(),
                ),
                0,
                $exception,
            );
        }
    }

    public function getName(): string
    {
        return 'json_pretty';
    }
}
