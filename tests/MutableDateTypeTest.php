<?php

declare(strict_types=1);

namespace Mapado\PrettyTypes\Tests;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\Deprecations\Deprecation;
use Mapado\PrettyTypes\MutableDateType;
use PHPUnit\Framework\TestCase;

final class MutableDateTypeTest extends TestCase
{
    public function testDateTimeRoundTripsWithoutTimezoneConversion(): void
    {
        $type = new MutableDateType();
        $platform = new MySQLPlatform();
        $date = new \DateTime('2026-01-15 00:30:00', new \DateTimeZone('Europe/Paris'));

        $databaseValue = $type->convertToDatabaseValue($date, $platform);
        $phpValue = $type->convertToPHPValue($databaseValue, $platform);

        self::assertSame('2026-01-15', $databaseValue);
        self::assertSame('Europe/Paris', $date->getTimezone()->getName(), 'the written DateTime is left untouched');
        self::assertInstanceOf(\DateTime::class, $phpValue);
        self::assertSame('2026-01-15 00:00:00', $phpValue->format('Y-m-d H:i:s'));
    }

    public function testDateTimeImmutableIsWritten(): void
    {
        $date = new \DateTimeImmutable('2026-01-15 00:30:00', new \DateTimeZone('Europe/Paris'));

        self::assertSame('2026-01-15', (new MutableDateType())->convertToDatabaseValue($date, new MySQLPlatform()));
    }

    public function testNull(): void
    {
        $type = new MutableDateType();
        $platform = new MySQLPlatform();

        self::assertNull($type->convertToDatabaseValue(null, $platform));
        self::assertNull($type->convertToPHPValue(null, $platform));
    }

    public function testInvalidFormatThrows(): void
    {
        $this->expectException(ConversionException::class);

        (new MutableDateType())->convertToPHPValue('not a date', new MySQLPlatform());
    }

    public function testTriggersNoDeprecation(): void
    {
        $type = new MutableDateType();
        $platform = new MySQLPlatform();
        Deprecation::disable(); // resets the counters
        Deprecation::enableTrackingDeprecations();

        $type->getSQLDeclaration([], $platform);
        $type->convertToDatabaseValue(null, $platform);
        $type->convertToPHPValue($type->convertToDatabaseValue(new \DateTime(), $platform), $platform);

        self::assertSame([], array_filter(Deprecation::getTriggeredDeprecations()));
    }
}
