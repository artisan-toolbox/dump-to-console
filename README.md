<!-- MAINTAINER:README_HEAD:START -->
<div id="maintainer-title" align="center">
    <img src="logo/dump-to-console-icon.svg" alt="" width="88">
    <h1>Dump to Console</h1>
</div>

<p id="maintainer-badges" align="center">
<a href="https://github.com/artisan-toolbox/dump-to-console/releases"><img src="https://img.shields.io/badge/version-1.0.0-blue?style=flat-square" alt="version"></a>
<a href="https://packagist.org/packages/artisan-toolbox/dump-to-console"><img src="https://img.shields.io/packagist/v/artisan-toolbox/dump-to-console.svg?style=flat-square" alt="Packagist"></a>
<a href="https://github.com/artisan-toolbox/dump-to-console/actions"><img src="https://img.shields.io/github/actions/workflow/status/artisan-toolbox/dump-to-console/tests.yml?branch=1.x&amp;label=Tests&amp;style=flat-square" alt="Tests"></a>
</p>

<p id="maintainer-description" align="center">
    Send Laravel dumps to a local console listener without interrupting the application.
</p>
<!-- MAINTAINER:README_HEAD:END -->

## Documentation

The complete usage, configuration, security, and architecture documentation is available at [artisantoolbox.wsssoftware.com.br/packages/dump-to-console](https://artisantoolbox.wsssoftware.com.br/packages/dump-to-console/).

## Installation

```bash
composer require artisan-toolbox/dump-to-console
```

Requires PHP 8.5 and Laravel 13.

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
