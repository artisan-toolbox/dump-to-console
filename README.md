<div align="center">
    <h1>Artisan Toolbox Dump To Console</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/artisan-toolbox/dump-to-console"><img src="https://img.shields.io/packagist/v/artisan-toolbox/dump-to-console.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/artisan-toolbox/dump-to-console"><img src="https://img.shields.io/packagist/php-v/artisan-toolbox/dump-to-console.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/artisan-toolbox/dump-to-console"><img src="https://badge.laravel.cloud/badge/artisan-toolbox/dump-to-console?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/artisan-toolbox/dump-to-console/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/artisan-toolbox/dump-to-console/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/artisan-toolbox/dump-to-console"><img src="https://img.shields.io/packagist/dt/artisan-toolbox/dump-to-console.svg?style=flat-square" alt="Total Downloads"></a>
</p>

Dump to Console sends debug output directly to your console without interrupting or blocking your application.

## Installation

You can install the package via Composer:

```bash
composer require artisan-toolbox/dump-to-console
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="dump-to-console"
```

Or, you may publish each resource individually:

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag="dump-to-console-config"
```

### Publishing and Running the Migrations

```bash
php artisan vendor:publish --tag="dump-to-console-migrations"
php artisan migrate
```

### Publishing the Views

```bash
php artisan vendor:publish --tag="dump-to-console-views"
```

### Publishing the Translations

```bash
php artisan vendor:publish --tag="dump-to-console-lang"
```

### Publishing the Public Assets

```bash
php artisan vendor:publish --tag="dump-to-console-assets"
```

## Usage

<!-- Add a basic usage example here. -->

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Artisan Toolbox Dump To Console! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Allan Mariucci Carvalho](https://github.com/artisan-toolbox)
- [All Contributors](../../contributors)

## License

Artisan Toolbox Dump To Console is open-sourced software licensed under the [MIT license](LICENSE.md).
