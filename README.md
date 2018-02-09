# Generate Database Aware Unique Codes For Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thejawker/super-random.svg?style=flat-square)](https://packagist.org/packages/thejawker/super-random)
[![Build Status](https://img.shields.io/travis/thejawker/super-random/master.svg?style=flat-square)](https://travis-ci.org/thejawker/super-random)
[![Quality Score](https://img.shields.io/scrutinizer/g/thejawker/super-random.svg?style=flat-square)](https://scrutinizer-ci.com/g/thejawker/super-random)
[![Total Downloads](https://img.shields.io/packagist/dt/thejawker/super-random.svg?style=flat-square)](https://packagist.org/packages/thejawker/super-random)

Nice and fluent way to create truly unique codes or tokens. 

## Installation

Require the package from Composer:

``` bash
composer require thejawker/super-random
```

As of Laravel 5.5 it will magically register the package.

## Usage
Just simple call the generate method on SuperRandom Facade or the handy shortcut helper function.

```php
$code = SuperRandom::generate();
echo $code; // 

$code = superRandom();
``` 

### Examples:

#### Database Aware
Often you don't want more than one of the same codes or tokens to be present in your database. Although it's easy to compare to the database it is annoying having to re-implement this all over the place.
By specifying the `table.column` in the `for` method you can easily make it entry aware.

```php
ConcertTicket::create([
    'band' => 'DYSSEBIA',
    'code' => SuperRandom::for(ConcertTicket::class)->generate()
]);

// Or more explicit:
ConcertTicket::create([
    'band' => 'DYSSEBIA',
    'code' => SuperRandom::for('concerts.code')->generate()
]);
```

### Length
You can specify the length as follows:

```php
ConcertTicket::create([
    'band' => 'DYSSEBIA',
    'code' => SuperRandom::length(12)->generate()
]);
```

### Allowed Chars
You can specify the allowed chars as follows:

```php
ConcertTicket::create([
    'band' => 'DYSSEBIA',
    'code' => SuperRandom::chars('abc123')->generate()
]);
```

By default we only include numbers and UPPERCASE characters with the exclusion of: `1, I, O, 0` since they look a lot alike and you don't want your users to guess. 


### Full Config
You can alternatively set the config right on the `generate()` method.
 
```php
ConcertTicket::create([
    'band' => 'DYSSEBIA',
    'code' => SuperRandom::generate([
        'table' => 'concerts',
        'column' => 'code',
        'length' => 15,
        'chars' => 'abc123'
    ])
]);
```

## Test

``` bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
