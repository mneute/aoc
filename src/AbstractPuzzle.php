<?php

declare(strict_types=1);

namespace App;

abstract class AbstractPuzzle
{
    public function __construct(
        private readonly bool $test,
    ) {
    }

    abstract public function run(): Result;

    /**
     * @return \Generator<string>
     */
    protected function readFile(): \Generator
    {
        $file = fopen($this->getFilePath(), 'rb')
            ?: throw new \RuntimeException(sprintf('Cannot open file %s', $this->getFilePath()));

        $i = 0;
        while (false !== ($line = fgets($file))) {
            yield $i++ => trim($line);
        }

        fclose($file);
    }

    private function getFilePath(): string
    {
        $reflector = new \ReflectionClass($this);

        return sprintf(
            '%s/%s',
            dirname($reflector->getFileName()),
            $this->test ? 'input-test.txt' : 'input.txt'
        );
    }
}
