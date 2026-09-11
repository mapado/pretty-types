<?php

declare(strict_types=1);

namespace Mapado\PrettyTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Maps a PHP array to a clob column through serialize()/unserialize(),
 * so applications on DBAL 4 (which ships no "array" type) keep reading their existing "array" columns.
 */
class LegacyArrayType extends Type
{
    /**
     * @param array<string, mixed> $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        return serialize($value);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if (null === $value) {
            return null;
        }

        $value = is_resource($value) ? stream_get_contents($value) : $value;

        if (!is_string($value)) {
            throw new ConversionException(sprintf(
                'Could not convert database value of type "%s" to Doctrine Type array: a string is expected.',
                get_debug_type($value),
            ));
        }

        set_error_handler(static function (int $code, string $message): bool {
            if (E_DEPRECATED === $code || E_USER_DEPRECATED === $code) {
                return false;
            }

            throw new ConversionException(sprintf(
                "Could not convert database value to 'array' as an error was triggered by the unserialization: '%s'",
                $message,
            ));
        });

        try {
            return unserialize($value);
        } finally {
            restore_error_handler();
        }
    }

    public function getName(): string
    {
        return 'array';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
