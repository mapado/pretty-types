# Mapado pretty-types

- [Installation](#installation)
- Types:
  - [JSON pretty](#json-pretty)
  - [UTCDateTime](#utcdatetime)
- [License](#license)

## Installation

```sh
composer require  mapado/pretty-types
```

## JSON pretty

Based on [`Doctrine DBAL types`](https://github.com/doctrine/dbal/tree/v2.10.0/lib/Doctrine/DBAL/Types).

Will store pretty printed JSON into database.

### Configuration

In your config file

```yaml
# Doctrine Configuration
doctrine:
  dbal:
    types:
      json_pretty: Mapado\PrettyTypes\JsonPrettyType
```

### Usage

In your entities

```php
/**
 * ...
 *
 * @ORM\Column(name="column_name", type="json_pretty")
 */
```

## UTCDateTime

If you want to store datetime in UTC in your database.

Copied version from [Working with DateTime Instances](https://www.doctrine-project.org/projects/doctrine-orm/en/3.2/cookbook/working-with-datetime.html#handling-different-timezones-with-the-datetime-type).

All credits goes to the doctrine team !

### Configuration

In your config file

```yaml
# Doctrine Configuration
doctrine:
  dbal:
    types:
      datetime: Mapado\PrettyTypes\UTCDateTimeType
      datetime_immutable: Mapado\PrettyTypes\UTCDateTimeImmutableType
      datetimetz: Mapado\PrettyTypes\UTCDateTimeType
```

Be aware that this will override all datetimes configured with doctrine.

If you do not want to override everything and use a custom types instead:

```yaml
# Doctrine Configuration
doctrine:
  dbal:
    types:
      utcdatetime: Mapado\PrettyTypes\UTCDateTimeType
      utc_datetime_immutable: Mapado\PrettyTypes\UTCDateTimeImmutableType
```

### Usage

In your entities, if you overrode the default datetime types, Doctrine will implicitly use the new types for all datetime fields:

```php
#[ORM\Column]
private \DateTime $someDate;

#[ORM\Column]
private \DateTimeImmutable $someDate;
```

You should store the timezone next to the datetime too. Read [the doctrine documentation](https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/cookbook/working-with-datetime.html) for more informations.

If you configured a custom type, use this instead:

```php
#[ORM\Column(type: 'utcdatetime')]
private \DateTime $someDate;

#[ORM\Column(type: 'utc_datetime_immutable')]
private \DateTimeImmutable $someDate;
```

## License

This project is licensed under the [MIT license](LICENSE).
