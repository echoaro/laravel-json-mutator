# Changelog

All notable changes to the `laravel-json-mutator` project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-15

### Added

- Initial release of Laravel Json Mutator package
- `JsonMutator` class with comprehensive JSON metadata management
- `JsonMutatorCast` class for Laravel Eloquent model integration
- Dot notation support for accessing nested data
- Fluent interface for method chaining
- Array access implementation for easy data manipulation
- Magic property support for dynamic property access
- Comprehensive merge functionality for arrays, collections, and other mutators
- Static factory methods (`fromJson`, `fromArray`, `fromCollection`)
- Utility methods (`isEmpty`, `isNotEmpty`, `clear`, `count`)
- Collection conversion with `toCollection()` method
- JSON serialization with `toJson()` method
- Iterator support for foreach loops
- Comprehensive test suite with PHPUnit
- Laravel service provider for automatic registration
- Configuration file with customizable options
- Complete documentation with examples
- MIT license

### Features

- **Easy JSON Management**: Simple and intuitive API for managing JSON metadata
- **Dot Notation Support**: Access nested data using dot notation (e.g., `user.profile.name`)
- **Fluent Interface**: Chain methods for better readability
- **Type Safety**: Full type hints and return type declarations
- **Array Access**: Use array syntax for accessing metadata
- **Laravel Integration**: Seamless integration with Laravel Eloquent models
- **Comprehensive Testing**: Full test coverage with PHPUnit

### Technical Details

- PHP 8.2+ support
- Laravel 10.0+, 11.0+, 12.0+ support
- PSR-4 autoloading
- Composer package structure
- Orchestra Testbench for testing
- Comprehensive error handling
- JSON validation and error recovery
- Laravel 11+ & 12+ Attribute-based cast support
