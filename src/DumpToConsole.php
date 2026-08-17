<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole;

use ArtisanToolbox\Maintainer\Versionable\Contracts\Versionable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Benchmark;
use RuntimeException;
use Symfony\Component\VarDumper\Caster\ScalarStub;
use Throwable;

class DumpToConsole implements Versionable
{
    public const string VERSION = '1.0.0';
    private readonly string $basePath;

    private readonly ?string $compiledViewPath;

    public function __construct(
        private readonly DumpClient $client,
        Application $app,
        Repository $config,
    ) {
        $this->basePath = $app->basePath();

        $compiledViewPath = $config->get('view.compiled');
        $this->compiledViewPath = is_string($compiledViewPath) ? $compiledViewPath : null;
    }

    /**
     * Send values to the dump listener and return them unchanged.
     *
     * @template TValue
     *
     * @param  TValue  ...$values
     * @return ($values is empty ? null : (TValue|array<array-key, TValue>))
     */
    public function dump(mixed ...$values): mixed
    {
        $this->send($values);

        if ($values === []) {
            return null;
        }

        return count($values) === 1 ? $values[array_key_first($values)] : $values;
    }

    /**
     * Send the provided values to the dump listener.
     *
     * @param  array<array-key, mixed>  $values
     */
    private function send(array $values): void
    {
        try {
            $context = $this->context();

            if ($values === []) {
                $this->client->dump(new ScalarStub('🐛'), $context);
            } elseif (array_key_exists(0, $values) && count($values) === 1) {
                $this->client->dump($values[0], $context);
            } else {
                foreach ($values as $key => $value) {
                    $label = is_int($key) ? (string) ($key + 1) : $key;

                    $this->client->dump($value, $context, $label);
                }
            }
        } catch (Throwable) {
            // A development dump must never affect the application.
        }
    }

    /**
     * Measure a callable once, dump its duration and result, and return the result.
     *
     * @template TResult
     *
     * @param  callable(): TResult  $callback
     * @return TResult
     */
    public function benchmark(callable $callback): mixed
    {
        [$result, $duration] = Benchmark::value($callback);

        $this->send([
            'duration' => number_format($duration, 3).'ms',
            'result' => $result,
        ]);

        return $result;
    }

    /**
     * Resolve the current dump context.
     *
     * @return array{source?: array{file: string, file_relative: string, line: int|null}}
     */
    private function context(): array
    {
        $source = $this->source();

        if ($source === null) {
            return [];
        }

        [$file, $relativeFile, $line] = $source;

        return [
            'source' => [
                'file' => $file,
                'file_relative' => $relativeFile,
                'line' => $line,
            ],
        ];
    }

    /**
     * Resolve the first caller outside the package source directory.
     *
     * @return array{0: string, 1: string, 2: int|null}|null
     */
    private function source(): ?array
    {
        $packageSourcePath = dirname(__DIR__).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
        $vendorSegment = DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR;

        foreach ((new RuntimeException)->getTrace() as $frame) {
            $file = $frame['file'] ?? null;
            $line = $frame['line'] ?? null;

            if (! is_string($file)
                || str_starts_with($file, $packageSourcePath)
                || str_contains($file, $vendorSegment)) {
                continue;
            }

            if ($this->isCompiledViewFile($file)) {
                $file = $this->originalCompiledViewFile($file);
                $line = null;
            }

            $relativeFile = str_starts_with($file, $this->basePath.DIRECTORY_SEPARATOR)
                ? substr($file, strlen($this->basePath) + 1)
                : $file;

            return [$file, $relativeFile, is_int($line) ? $line : null];
        }

        return null;
    }

    private function isCompiledViewFile(string $file): bool
    {
        return is_string($this->compiledViewPath)
            && $this->compiledViewPath !== ''
            && str_starts_with($this->normalizePath($file), $this->normalizePath($this->compiledViewPath))
            && str_ends_with($file, '.php');
    }

    private function normalizePath(string $path): string
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    private function originalCompiledViewFile(string $file): string
    {
        $contents = @file_get_contents($file);

        if (is_string($contents) && preg_match('/\/\*\*PATH\s(.*)\sENDPATH/', $contents, $matches) === 1) {
            return $matches[1];
        }

        return $file;
    }
}
