---
name: dump-to-console-development
description: >
  Use Artisan Toolbox Dump To Console to inspect Laravel values without stopping execution or changing application output.
license: MIT
metadata:
  author: Allan Mariucci Carvalho
---

# Artisan Toolbox Dump To Console

Use this skill when temporary debug values must appear in a local console while HTTP responses, streams, downloads, jobs, or command output remain intact.

## Primary Goal

Send short-lived development dumps to the package listener with the smallest useful probe and remove probes when the investigation is complete.

## Workflow

1. From a PHP 8.5 and Laravel 13 application, run `composer require artisan-toolbox/dump-to-console`.
2. Start `php artisan dump:listen`, or use the package's `dumps` process through `php artisan dev` when supported.
3. Call `dc($value)` at the application location being inspected. Use named arguments when several values need meaningful labels.
4. Use `Benchmark::dc(fn () => ...)` when both a result and its duration are useful.
5. Add `ArtisanToolbox\DumpToConsole\Concerns\Dumpable` to an application class only when fluent `$object->dc()` calls improve its development workflow.
6. Publish `dump-to-console-config` only when changing the host or disabling `artisan dev` integration.

## References

- [Installation](https://atb.dev.br/docs/dump-to-console/installation)
- [Listener setup and examples](https://atb.dev.br/docs/dump-to-console/usage)

## Examples

```php
$orderId = dc(42);
$summary = dc(orderId: $orderId, itemCount: 3);
```

```php
use Illuminate\Support\Benchmark;

$total = Benchmark::dc(fn (): int => array_sum([10, 20, 30]));
```

The listener receives the selected values and the timing. The calls return `42`, the named summary array, and `60` in the application.

## Anti-patterns

- Do not expose the TCP listener on an untrusted interface; its protocol has no authentication or encryption.
- Do not treat best-effort dumps as persistent logs or application control flow.
- Do not use dumps for secrets or values that should not appear in a developer terminal.
- Do not publish configuration when the local defaults are sufficient.
