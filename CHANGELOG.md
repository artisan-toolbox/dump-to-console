# Release Notes

## [Unreleased](https://github.com/artisan-toolbox/dump-to-console/compare/v0.1.0...1.x)

- Require PHP 8.5 or later, Laravel 13, and Pest 5.
- Add the fault-tolerant `dc()` helper for labeled console dumps with source context.
- Add the `dump:listen` TCP listener and optional Laravel `dev` command integration.
- Add fluent dumps through the package's `Dumpable` trait.
- Add `Benchmark::dc()` for in-place timing without interrupting execution.
- Add configurable listener hosts and Laravel CLI rendering with editor links.

## [v0.1.0](https://github.com/artisan-toolbox/dump-to-console/compare/...v0.1.0) - 202x-xx-xx

Initial pre-release.
