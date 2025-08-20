<?php

declare(strict_types=1);

namespace App;

abstract class AbstractPuzzle
{
    abstract public function run(): Result;

    /**
     * @return \Generator<string>
     */
    protected function readFile(string $path): \Generator
    {
        $file = fopen($path, 'rb') ?: throw new \RuntimeException(sprintf('Cannot open file %s', $path));

        $i = 0;
        while (false !== ($line = fgets($file))) {
            yield $i++ => trim($line);
        }

        fclose($file);
    }
}
