<div align="center">
    <h1>Artisan Toolbox Dump To Console</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/artisan-toolbox/dump-to-console"><img src="https://img.shields.io/packagist/v/artisan-toolbox/dump-to-console.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://github.com/artisan-toolbox/dump-to-console/actions"><img alt="GitHub Workflow Status (1.x)" src="https://img.shields.io/github/actions/workflow/status/artisan-toolbox/dump-to-console/tests.yml?branch=1.x&label=Tests&style=flat-square"></a>
</p>

Dump to Console sends Laravel dumps to a local console listener without interrupting the application response, stream, download, or command output.

## Documentation

The complete usage, configuration, security, and architecture documentation is available at [artisantoolbox.wsssoftware.com.br/packages/dump-to-console](https://artisantoolbox.wsssoftware.com.br/packages/dump-to-console/).

## Installation

```bash
composer require artisan-toolbox/dump-to-console
```

Start the listener and send it a value:

```bash
php artisan dump:listen
```

```php
$order = dc(Order::find($id));
```

The application keeps running and its original output remains unchanged.

## Resources

- [Documentation](https://artisantoolbox.wsssoftware.com.br/packages/dump-to-console/)
- [Changelog](CHANGELOG.md)
- [Contributing](.github/CONTRIBUTING.md)
- [Security policy](.github/SECURITY.md)
- [License](LICENSE.md)
