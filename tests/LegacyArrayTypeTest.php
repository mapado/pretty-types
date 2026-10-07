<?php

declare(strict_types=1);

namespace Mapado\PrettyTypes\Tests;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Mapado\PrettyTypes\LegacyArrayType;
use PHPUnit\Framework\TestCase;

final class LegacyArrayTypeTest extends TestCase
{
    public function testScalarsAndDatesRoundTrip(): void
    {
        $type = new LegacyArrayType();
        $platform = new MySQLPlatform();
        $value = ['roles' => ['ROLE_USER', 'ROLE_ADMIN'], 'validStartDate' => new \DateTime('2026-01-01'), 'createdAt' => new \DateTimeImmutable('2026-01-01')];

        self::assertEquals($value, $type->convertToPHPValue($type->convertToDatabaseValue($value, $platform), $platform));
    }

    public function testOtherObjectsAreNeverRevived(): void
    {
        $type = new LegacyArrayType();
        $platform = new MySQLPlatform();
        $value = $type->convertToPHPValue(serialize(['object' => new \stdClass()]), $platform);

        self::assertInstanceOf(\__PHP_Incomplete_Class::class, $value['object']);
    }
}
