<?php

declare(strict_types=1);

namespace Mapado\PrettyTypes\Tests;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\Deprecations\Deprecation;
use Mapado\PrettyTypes\UTCDateTimeType;
use PHPUnit\Framework\TestCase;

final class UTCDateTimeTypeTest extends TestCase
{
    public function testDateTimeRoundTripsInUtc(): void
    {
        $type = new UTCDateTimeType();
        $platform = new MySQLPlatform();
        $date = new \DateTime('2026-01-15 10:00:00', new \DateTimeZone('Europe/Paris'));

        $databaseValue = $type->convertToDatabaseValue($date, $platform);
        $phpValue = $type->convertToPHPValue($databaseValue, $platform);

        self::assertSame('2026-01-15 09:00:00', $databaseValue);
        self::assertSame('UTC', $date->getTimezone()->getName(), 'the written DateTime is moved to UTC');
        self::assertInstanceOf(\DateTime::class, $phpValue);
        self::assertSame('2026-01-15T09:00:00+00:00', $phpValue->format(\DATE_ATOM));
    }

    public function testDateTimeImmutableIsWrittenInUtc(): void
    {
        $date = new \DateTimeImmutable('2026-01-15 10:00:00', new \DateTimeZone('Europe/Paris'));

        self::assertSame('2026-01-15 09:00:00', (new UTCDateTimeType())->convertToDatabaseValue($date, new MySQLPlatform()));
    }

    public function testNull(): void
    {
        $type = new UTCDateTimeType();
        $platform = new MySQLPlatform();

        self::assertNull($type->convertToDatabaseValue(null, $platform));
        self::assertNull($type->convertToPHPValue(null, $platform));
    }

    public function testInvalidFormatThrows(): void
    {
        $this->expectException(ConversionException::class);

        (new UTCDateTimeType())->convertToPHPValue('not a date', new MySQLPlatform());
    }

    public function testTriggersNoDeprecation(): void
    {
        $type = new UTCDateTimeType();
        $platform = new MySQLPlatform();
        Deprecation::disable(); // resets the counters
        Deprecation::enableTrackingDeprecations();

        $type->getSQLDeclaration([], $platform);
        $type->convertToPHPValue($type->convertToDatabaseValue(new \DateTime(), $platform), $platform);

        self::assertSame([], array_filter(Deprecation::getTriggeredDeprecations()));
    }
}
