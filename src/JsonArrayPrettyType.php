<?php

namespace Mapado\PrettyTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;

/**
 * Array Type which can be used to generate pretty printed json arrays.
 *
 * @author Thomas di Luccio <thomas.diluccio@mapado.com>
 */
class JsonArrayPrettyType extends JsonArrayType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        $encoded = json_encode($value, JSON_PRETTY_PRINT);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw ConversionException::conversionFailedSerialization($value, 'json', json_last_error_msg());
        }
        return $encoded;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return parent::getName() . '_pretty';
    }
}
