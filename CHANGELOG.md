# Changelog

## Unreleased
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
