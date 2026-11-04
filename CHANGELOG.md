# Changelog

## [1.1.0] - 2026-11-04

### Fixes

- **Configure Larastan to include package config directory** (`8498d9b`)
  Adjusted Larastan/PHPStan configuration so the analyzer recognizes the package’s `config/` directory. This improves static analysis accuracy for any configuration-dependent code paths and suppresses false positives/false negatives that occur when the analyzer cannot resolve config files. No runtime/public API behavior is changed, but it can affect developer experience and CI static analysis outcomes.

- **Install maintainer as runtime dependency** (`cbd0340`)
  Changed dependency configuration so `artisan-toolbox/maintainer` is installed under production `require` instead of only `require-dev`. This ensures consumers can use `dc()` without needing development dependencies. The practical impact is improved out-of-the-box usability for end users who install the package normally. Compatibility note: environments that previously did not install dev dependencies and relied on `dc()` not being available at runtime will now see it available; conversely, increasing production dependencies may affect install size/autoloading and could be considered a behavior/packaging change (potentially breaking for highly size-sensitive setups).

### Code Style

- **Bump shivammathur/setup-php from 2.37.2 to 2.40.0** (`007a0de`)
  Updated the pinned version of the GitHub Action `shivammathur/setup-php` from 2.37.2 to 2.40.0. This is a CI/tooling version bump that helps ensure consistent PHP installation during workflow runs. User impact is limited to CI behavior and tooling reliability; no runtime or public API changes are intended.

- **Remove extra blank line in DumpToConsole** (`ef1d521`)
  Performed a formatting-only cleanup by removing an extra blank line in `DumpToConsole`. This is intended to keep code style consistent and does not change functionality, logic, or public APIs.

### Maintenance

- **Merge origin/1.x into 1.x** (`2f1b526`)
  Merged the remote-tracking branch "origin/1.x" into the local 1.x branch. This is an integration-only change that brings in whatever commits were present on the remote, potentially including fixes or configuration updates needed for the 1.x line. User-facing impact depends on what was contained in the merged branch; no behavior is explicitly described beyond the branch merge.

- **Bump CI setup-php action to shivammathur/setup-php v2.40.0** (`75f8cc2`)
  Merged a pull request that updates the GitHub Actions `shivammathur/setup-php` usage to version 2.40.0 (still within the v2 major line). This ensures CI installs the intended PHP tooling with the updated action commit/pinning and improves compatibility with the action’s latest fixes. Indirectly, the workflow adds a Windows-only Composer step to require a compatible PHPStan version (>=2.2.8 <3.0) to include the "Turbo PHPDoc fix" for Windows; this can indirectly change dev/static-analysis dependencies in CI, affecting test/static-analysis dependency resolution but not runtime/public APIs.

- **Remove update-changelog GitHub Actions workflow** (`13acbde`)
  Deleted the GitHub Actions workflow `.github/workflows/update-changelog.yml`, which previously ran on `release` events (type `released`) to update `CHANGELOG.md` and automatically commit it back to `main` using `stefanzweifel/changelog-updater-action` and `stefanzweifel/git-auto-commit-action`. After this removal, future published releases will no longer automatically update and commit changelog content. User impact is on project maintenance/visibility rather than runtime behavior; consumers are not directly affected, but maintainers must update the changelog through other means.

## Unreleased

### Fixes

- Configure Larastan to recognize the package's `config/` directory during static analysis.
- Install Maintainer as a runtime dependency so consumers can call `dc()` without installing development dependencies.

### Documentation

- Standardize README badges for release status, compatibility, CI, and documentation.
- Add the Dump to Console logo and manage the README header with Maintainer.

## [1.0.0] - 2026-08-17

### Features

- **Implement Versionable on DumpToConsole** (`599e65f`)
  Adds Versionable support to the DumpToConsole component, enabling it to participate in versioning/version-aware behavior. This matters because it standardizes how DumpToConsole reports or tracks version metadata across the package lifecycle (e.g., for tooling, documentation, or release automation). User impact: consumers of DumpToConsole may benefit from improved version metadata integration without changing how they call it. Compatibility: no breaking API changes are implied by the summary; however, version metadata behavior may now be present/used by downstream tooling. Migration: if you rely on prior absence of Versionable metadata, update any custom tooling that assumed no versioning interface was available.

- **Introduce Dump to Console functionality with TCP listener and fluent API** (`18aef6b`)
  Introduces the “Dump to Console” functionality, including a configurable TCP listener, console dumps, and a fluent API. Why it matters: it enables redirecting dump output to a console consumer over TCP, improving debugging workflows and developer tooling. User impact: users can adopt the new DumpToConsole feature to stream formatted dump data to a console destination, and they gain a fluent interface for configuring behavior. Compatibility: this adds new functionality; no existing APIs should break, but verify your environment/ports/network permissions for TCP listening. Migration: start using the new feature via the fluent API; ensure any TCP listener configuration aligns with your local/dev environment.

### Fixes

- **Normalize file path handling in DumpToConsole** (`6dd143b`)
  Normalizes file path handling in DumpToConsole to improve cross-platform compatibility. Why it matters: path normalization prevents issues when code runs on different OSes/filesystems (e.g., Windows vs Unix path separators). User impact: fewer platform-specific bugs when DumpToConsole reads or outputs file-related content. Compatibility: should improve behavior without API changes. Migration: none; existing integrations should behave more reliably across platforms.

- **Remove unnecessary phpstan paths** (`2e15709`)
  Removes unnecessary paths from phpstan configuration. Why it matters: cleaning static analysis inputs can improve signal-to-noise, reduce analysis time, and prevent false positives/irrelevant findings. User impact: more relevant/static-analysis results when running CI or local analysis. Compatibility: no runtime impact. Migration: none; developer tooling only.

### Documentation

- **Update README documentation links for clarity** (`2a69118`)
  Updates README and documentation links to be clearer and more direct. This matters for onboarding and navigation accuracy, reducing time users spend searching for the right docs. User impact: improved developer experience when discovering features and setup guidance. Compatibility: no code/runtime impact. Migration: none; only link targets changed.

- **Update README badges to reference 1.x workflows** (`df554d4`)
  Updates README badges so they reference the 1.x branch workflow(s) rather than the previous targets. Why it matters: it keeps build status indicators accurate for the supported major line. User impact: developers viewing the repository get correct CI signal relevant to 1.x. Compatibility: no runtime impact. Migration: none.

- **Update README badges for consistency and clarity** (`ab4a2b2`)
  Adjusts README badges for consistency and clarity. Why it matters: consistent badge presentation reduces confusion about what CI/doc status the badges represent. User impact: clearer repository status information. Compatibility: none. Migration: none.

### Refactoring

- **Use resolve() and attribute-based command metadata** (`92d8369`)
  Refactors command construction/metadata handling to use resolve() and attribute-based command metadata. Why it matters: attribute-driven command definitions improve consistency and reduce manual wiring, while resolve() can standardize how dependencies are created and injected. User impact: the resulting console command behavior/registration should remain functionally equivalent but may improve reliability and alignment with Laravel conventions. Compatibility: command metadata discovery changes how the framework reads command definitions; if you have custom discovery/overrides relying on prior metadata patterns, verify they still work. Migration: review any custom command registration extensions or reflection-based tooling to ensure it supports attribute-based metadata.

- **Refactor DumpToConsole to extract send() and streamline value handling** (`0516a33`)
  Refactors DumpToConsole by extracting a send() method and streamlining how values are handled. Why it matters: better internal separation clarifies the flow from preparing dump payloads to emitting them to the console target, and reduces duplication/complexity. User impact: output behavior should remain the same while improving maintainability and reducing risk of edge-case inconsistencies. Compatibility: no public API change is described; validate output formatting in your use-cases if you rely on subtle behavior. Migration: none expected, but if you used undocumented internals, re-check them against the new internal method structure.

### Build

- **Require PHP 8.5, Laravel 13, and Pest 5** (`8b1cbdf`)
  Updates dependency requirements to require PHP 8.5, Laravel 13, and Pest 5. Why it matters: it formalizes the supported runtime/test stack, allowing the project to rely on newer language/framework/testing capabilities. User impact: upgrading to this release will require environment/tooling updates—users on older PHP/Laravel/Pest versions will no longer be able to install or run the package. Compatibility: this is a breaking compatibility change for unsupported environments. Migration: upgrade your PHP to 8.5+, Laravel to 13+, and ensure Pest 5 is available in your test setup (and update any related testbench/dev dependencies if you have custom constraints).

- **Add artisan-toolbox/core dependency to GitHub Actions workflow** (`d47fe5a`)
  Adds the artisan-toolbox/core dependency to the GitHub Actions workflow used for CI runs. Why it matters: ensures CI installs the required core components, making the test/build pipeline reflect real installation/runtime expectations. User impact: more reliable CI and earlier detection of integration issues. Compatibility: none for consumers; CI/tooling improvement only. Migration: none.

- **Add artisan-toolbox/core dependency to composer.json** (`055b752`)
  Adds artisan-toolbox/core as a dependency in composer.json. Why it matters: it wires required core functionality into the package’s installable dependency graph, ensuring runtime features can rely on core services. User impact: installing the package will now pull in artisan-toolbox/core. Compatibility: may affect Composer resolution—projects with constrained dependency graphs might need to adjust version constraints. Migration: if you have strict Composer constraints, update them to allow compatible versions of artisan-toolbox/core.

### Maintenance

- **Initial commit** (`94b8a4d`)
  Creates the initial repository state for this project/version, establishing the baseline codebase and project configuration. Why it matters: it provides the starting point from which subsequent features, tooling, and configuration changes build. User impact: none beyond providing the initial implementation. Compatibility: establishes the initial supported surface area as implemented at commit time. Migration: none (starting point).

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
