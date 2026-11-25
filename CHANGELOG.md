# Changelog

## Unreleased
### Added

- `MutableDateType`: a `date` type for `\DateTime` properties, which adapts `DateImmutableType` and triggers no doctrine/dbal 4.6 deprecation

### Changed

- `UTCDateTimeType` no longer extends `DateTimeType`, deprecated in doctrine/dbal 4.6: it adapts `UTCDateTimeImmutableType`, so its conversion errors now name `datetime_immutable` and `UTCDateTimeImmutableType`
- Requires doctrine/dbal 4.3+ (drops 2.x and 3.x)

## 1.3.0
### Added

- PHPUnit tests, runnable with `make test`

### Changed

- `LegacyArrayType` no longer unserializes objects other than `DateTime` and `DateTimeImmutable` (enum cases are still restored): any other object comes back as `__PHP_Incomplete_Class`

## 1.2.0
### Added

- Compatibility with doctrine/dbal 4 (still compatible with 2.6+ and 3.x)
- `LegacyArrayType`: port of the DBAL 3 `array` type (PHP `serialize()` in a text column) for applications on DBAL 4
- `UTCDateTimeType` accepts `\DateTimeImmutable` values when converting to the database
- phpstan (level max) on `src/`, runnable with `make phpstan`

### Changed

- Requires PHP 8.4+

## 1.1.0
### Added

- Add `UTCDateTimeImmutableType`

## 1.0.0
### Changed

- [Breaking] Added compatibility for PHP 8.1
