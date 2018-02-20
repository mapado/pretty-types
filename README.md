# Mapado pretty-types

Based on [`Doctrine DBAL types`](https://github.com/doctrine/dbal/tree/master/lib/Doctrine/DBAL/Types).

Will store pretty printed JSON into database.

## Installation
```
$ composer require  mapado/pretty-types
```

## Usage

### Configuration

In your config file
```
# Doctrine Configuration
doctrine:
    dbal:
        types:
            json_pretty:  Mapado\PrettyTypes\JsonPrettyType
```

In your entities

```
/**
 * ...
 *
 * @ORM\Column(name="column_name", type="json_pretty")
 */
```

## License

This project is licensed under the [MIT license](LICENSE).
