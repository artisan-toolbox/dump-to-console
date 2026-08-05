<?php

declare(strict_types=1);

namespace ArtisanToolbox\DumpToConsole;

use Symfony\Component\VarDumper\Cloner\Stub;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class DumpCloner extends VarCloner
{
    /** @return array<int|string, mixed> */
    protected function doClone(mixed $var): array
    {
        return $this->normalizeStubs(parent::doClone($var));
    }

    /**
     * @param  array<int|string, mixed>  $values
     * @return array<int|string, mixed>
     */
    private function normalizeStubs(array $values): array
    {
        foreach ($values as $key => $value) {
            if ($value instanceof Stub && $value::class !== Stub::class) {
                $stub = new Stub;
                $stub->type = $value->type;
                $stub->class = $value->class;
                $stub->value = $value->value;
                $stub->cut = $value->cut;
                $stub->handle = $value->handle;
                $stub->refCount = $value->refCount;
                $stub->position = $value->position;
                $stub->attr = $value->attr;

                $values[$key] = $stub;
            } elseif (is_array($value)) {
                $values[$key] = $this->normalizeStubs($value);
            }
        }

        return $values;
    }
}
