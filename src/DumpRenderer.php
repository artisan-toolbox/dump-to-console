<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole;

use ArtisanToolbox\DumpToConsole\Console\CliDumper;
use Symfony\Component\VarDumper\Cloner\Data;

class DumpRenderer
{
    public function __construct(
        private readonly CliDumper $dumper,
        private readonly string $basePath,
    ) {}

    /** @param array<string, mixed> $context */
    public function render(Data $data, array $context = []): void
    {
        $this->dumper->dumpWithSource($data, $this->resolveSource($context));
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{0: string, 1: string, 2: int|null}|null
     */
    private function resolveSource(array $context): ?array
    {
        $source = $context['source'] ?? null;

        if (! is_array($source) || ! is_string($source['file'] ?? null)) {
            return null;
        }

        $file = $source['file'];
        $relativeFile = is_string($source['file_relative'] ?? null)
            ? $source['file_relative']
            : $file;

        if (! isset($source['file_relative']) && str_starts_with($file, $this->basePath.DIRECTORY_SEPARATOR)) {
            $relativeFile = substr($file, strlen($this->basePath) + 1);
        }

        $line = is_int($source['line'] ?? null) ? $source['line'] : null;

        return [$file, $relativeFile, $line];
    }
}
