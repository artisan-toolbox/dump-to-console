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

Dump to Console sends Laravel dumps to a local console listener without stopping the application or changing its response, stream, download, or command output.

## Installation

You can install the package via Composer:

```bash
composer require artisan-toolbox/dump-to-console
```

Start the listener in a terminal:

```bash
php artisan dump:listen
```

Then send any value to it with `dc()`:

```php
$order = dc(Order::find($id));

dc(
    request: $request->all(),
    order: $order,
);
```

The application keeps running, the original output stays intact, and each dump includes its application source file and line. Blade calls resolve to the original view file.

## Usage

### Listening for Dumps

Run the listener by itself:

```bash
php artisan dump:listen
```

The listener uses `tcp://127.0.0.1:9912` by default. You may override it for one invocation:

```bash
php artisan dump:listen --host=tcp://127.0.0.1:9913
```

On Laravel versions that expose the `DevCommands` API, the package also adds a `dumps` process to `php artisan dev`. Disable that integration in the published configuration if you prefer to run the listener separately.

### Dumping Values

Like Laravel's `dump()` helper, `dc()` returns the values it receives. Unlike `dump()`, it sends the rendered value to the listener instead of writing into the current process output.

```php
$user = dc(User::findOrFail($id));

$values = dc('first', 'second');

$named = dc(user: $user, permissions: $user->getAllPermissions());
```

Multiple positional values are labeled `1`, `2`, and so on. Named arguments keep their names. Calling `dc()` without arguments sends a small marker, which is handy for checking whether a branch was reached.

Delivery is best effort: if the listener is unavailable or a value cannot be prepared or written, the dump is silently discarded. Debugging should not become the production incident it was investigating.

### Fluent Dumps

Use the package trait when an application class should support fluent console dumps:

```php
use ArtisanToolbox\DumpToConsole\Concerns\Dumpable;

class ReportBuilder
{
    use Dumpable;
}

$report = $builder
    ->dc()
    ->generate();
```

`dc()` returns the same object after sending it to the listener. Additional arguments are supported and are dumped alongside the object.

### Benchmarks

The package registers `Benchmark::dc()` to measure a callback once, dump its duration and result, and return the result:

```php
use Illuminate\Support\Benchmark;

$report = Benchmark::dc(fn () => $service->generateReport());
```

### Configuration

Publish the configuration when you need to change the listener endpoint or the `artisan dev` integration:

```bash
php artisan vendor:publish --tag="dump-to-console-config"
```

You may set the listener endpoint through the environment:

```dotenv
DUMP_TO_CONSOLE_HOST=tcp://127.0.0.1:9913
```

The dump protocol has no authentication or encryption. Keep it bound to a trusted local interface and treat dumped values with the same care as local logs.

## How It Works

The client clones values with Symfony VarDumper, adds a timestamp and source context, and sends the payload over a reusable non-blocking TCP connection. The listener uses Symfony's dump server protocol and Laravel's CLI dumper, preserving Laravel's familiar rendering and editor links.

The HTTP server, queue workers, scheduler, and Artisan commands can run as separate processes while sending their temporary debug values to the same console.

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
