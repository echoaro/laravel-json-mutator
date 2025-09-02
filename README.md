# Laravel JSON Mutator

[![Packagist](https://img.shields.io/packagist/v/echoaro/laravel-json-mutator.svg)](https://packagist.org/packages/echoaro/laravel-json-mutator)
[![Downloads](https://img.shields.io/packagist/dt/echoaro/laravel-json-mutator.svg)](https://packagist.org/packages/echoaro/laravel-json-mutator)
[![License](https://img.shields.io/packagist/l/echoaro/laravel-json-mutator.svg)](LICENSE.md)
[![Tests](https://github.com/echoaro/laravel-json-mutator/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/echoaro/laravel-json-mutator/actions/workflows/tests.yml)
[![PHP Version](https://img.shields.io/packagist/php-v/echoaro/laravel-json-mutator.svg)](https://packagist.org/packages/echoaro/laravel-json-mutator)
[![Illuminate Support](https://img.shields.io/packagist/dependency-v/echoaro/laravel-json-mutator/illuminate/support.svg)](https://packagist.org/packages/echoaro/laravel-json-mutator)

A powerful Laravel package for easy JSON metadata management with custom casts, dot notation support, and fluent
interface.

## Features

- **Easy JSON Management**: Simple and intuitive API for managing JSON metadata
- **Dot Notation Support**: Access nested data using dot notation (e.g., `user.profile.name`)
- **Fluent Interface**: Chain methods for better readability
- **Type Safety**: Full type hints and return type declarations
- **Array Access**: Use array syntax for accessing metadata
- **Laravel Integration**: Seamless integration with Laravel Eloquent models
- **Laravel 11+ & 12 Support**: New Attribute-based cast for better compatibility
- **Comprehensive Testing**: Full test coverage with PHPUnit

## Quick Links

- **[📋 Documentation](https://github.com/echoaro/laravel-json-mutator#readme)** - Full documentation
- **[🐛 Issues](https://github.com/echoaro/laravel-json-mutator/issues)** - Report bugs
- **[💡 Discussions](https://github.com/echoaro/laravel-json-mutator/discussions)** - Ask questions

## Installation

You can install the package via Composer:

```bash
composer require echoaro/laravel-json-mutator
```

The package will automatically register itself with Laravel.

### Requirements

- **PHP**: ^8.2
- **Laravel**: ^10.0|^11.0|^12.0

## Basic Usage

### 1. Add the Cast to Your Model

#### Option 1: Traditional Cast (Laravel 10+)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use JsonMutator\Casts\JsonMutatorCast;

class User extends Model
{
    protected $casts = [
        'metadata' => JsonMutatorCast::class,
    ];
}
```

#### Option 2: Attribute Cast (Laravel 11+ Recommended)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use JsonMutator\Casts\JsonMutatorAttributeCast;

class User extends Model
{
    protected function metadata(): Attribute
    {
        return JsonMutatorAttributeCast::make();
    }
}
```

### 2. Use the metadata (column name) in Your Code

```php
// Create a new user
$user = new User();

// Using array access syntax
$user->metadata['profile']['name'] = 'John Doe';
$user->metadata['profile']['email'] = 'john@example.com';
$user->metadata['preferences']['theme'] = 'dark';

// Access metadata using array access
echo $user->metadata['profile']['name']; // Output: John Doe
echo $user->metadata['profile']['email']; // Output: john@example.com

// Using magic properties
$user->metadata->user_role = 'admin';
$user->metadata->last_login = '2024-01-15 10:30:00';

echo $user->metadata->user_role; // Output: admin
echo $user->metadata->last_login; // Output: 2024-01-15 10:30:00

// Using direct array assignment
$user->metadata = [
    'profile' => [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ],
    'preferences' => [
        'theme' => 'dark'
    ]
];

// Save the user
$user->save();
```

## Advanced Usage

### Merging Data

```php
// Merge arrays
$user->metadata->merge([
    'settings' => [
        'notifications' => true
    ]
]);

// Merge collections
$collection = collect(['tags' => ['admin', 'moderator']]);
$user->metadata->merge($collection);

// Merge other metadata mutators
$otherMetadata = new MetadataMutator(['role' => 'admin']);
$user->metadata->merge($otherMetadata);
```

### Working with Collections

```php
// Convert to Laravel Collection
$collection = $user->metadata->toCollection();

// Use collection methods
$user->metadata->toCollection()->each(function ($value, $key) {
    echo "$key: $value";
});
```

### Static Methods

```php
use JsonMutator\Mutators\JsonMutatorDTO;

// Create from JSON string
$metadata = JsonMutatorDTO::fromJson('{"key":"value"}');

// Create from array
$metadata = JsonMutatorDTO::fromArray(['key' => 'value']);

// Create from collection
$metadata = JsonMutatorDTO::fromCollection(collect(['key' => 'value']));
```

### Fluent Interface

```php
$user->metadata
    ->set('profile.name', 'John Doe')
    ->set('profile.email', 'john@example.com')
    ->set('preferences.theme', 'dark')
    ->set('preferences.language', 'en')
    ->forget('old_setting')
    ->merge(['new_setting' => 'value']);
```

## Database Migration

Create a migration to add a metadata column:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('metadata')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};
```

## Available Methods

### JsonMutator Methods

| Method                       | Description                    |
|------------------------------|--------------------------------|
| `get($key, $default = null)` | Get a value using dot notation |
| `set($key, $value)`          | Set a value using dot notation |
| `has($key)`                  | Check if a key exists          |
| `forget($key)`               | Remove a key                   |
| `merge($data)`               | Merge data into data           |
| `replace($data)`             | Replace all data               |
| `clear()`                    | Clear all data                 |
| `isEmpty()`                  | Check if data is empty         |
| `isNotEmpty()`               | Check if data is not empty     |
| `toArray()`                  | Get as array                   |
| `toCollection()`             | Get as Laravel Collection      |
| `toJson($options = 0)`       | Get as JSON string             |
| `count()`                    | Get number of items            |

### Static Methods

| Method                        | Description             |
|-------------------------------|-------------------------|
| `fromJson($json)`             | Create from JSON string |
| `fromArray($array)`           | Create from array       |
| `fromCollection($collection)` | Create from collection  |

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=json-mutator-config
```

This will create `config/json-mutator.php` with the following options:

```php
return [
    'json_options' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
    'defaults' => [
        'empty_value' => '{}',
        'null_value' => null,
    ],
    'validation' => [
        'max_depth' => 10,
        'max_length' => 65535,
    ],
];
```

## Testing

Run the tests:

```bash
composer test
```

### Test Coverage

The package includes comprehensive test coverage for all functionality:

- ✅ **JsonMutator Tests**: All methods and edge cases
- ✅ **JsonMutatorCast Tests**: Traditional cast functionality
- ✅ **JsonMutatorAttributeCast Tests**: Laravel 11+ Attribute cast
- ✅ **Integration Tests**: Full Laravel integration

### Continuous Integration

This package uses GitHub Actions for continuous integration:

- **Multiple PHP versions**: 8.2, 8.3
- **Latest Laravel version**: 12.x
- **Code coverage**: Uploaded to Codecov
- **Automatic testing**: On every push and pull request

## Development

### Local Development Setup

1. Clone the repository:

```bash
git clone https://github.com/echoaro/laravel-json-mutator.git
cd laravel-json-mutator
```

2. Install dependencies:

```bash
composer install
```

3. Run tests:

```bash
composer test
```

4. Run tests with coverage:

```bash
composer test -- --coverage-html coverage/
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Arayik Grigoryan](https://github.com/echoaro)
- [All Contributors](../../contributors)

