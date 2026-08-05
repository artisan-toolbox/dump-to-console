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

1. Install `artisan-toolbox/dump-to-console` with Composer.
2. Start `php artisan dump:listen`, or use the package's `dumps` process through `php artisan dev` when supported.
3. Call `dc($value)` at the application location being inspected. Use named arguments when several values need meaningful labels.
4. Use `Benchmark::dc(fn () => ...)` when both a result and its duration are useful.
5. Add `ArtisanToolbox\DumpToConsole\Concerns\Dumpable` to an application class only when fluent `$object->dc()` calls improve its development workflow.
6. Publish `dump-to-console-config` only when changing the host or disabling `artisan dev` integration.

## References

- `config/dump-to-console.php`
- `README.md`

## Examples

```php
$order = dc(Order::findOrFail($id));

dc(request: request()->all(), order: $order);
```

```php
use Illuminate\Support\Benchmark;

$report = Benchmark::dc(fn () => $service->generateReport());
```

## Anti-patterns

- Do not expose the TCP listener on an untrusted interface; its protocol has no authentication or encryption.
- Do not treat best-effort dumps as persistent logs or application control flow.
- Do not use dumps for secrets or values that should not appear in a developer terminal.
- Do not publish configuration when the local defaults are sufficient.
