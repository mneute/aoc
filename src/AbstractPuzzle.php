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

    public function getFilePath(): string
    {
        static $path = null;

        if (null === $path) {
            $reflector = new \ReflectionClass($this);

            $path = sprintf(
                '%s/%s',
                dirname($reflector->getFileName()),
                $this->test ? 'input-test.txt' : 'input.txt'
            );
        }

        return $path;
    }

    /**
     * @return \Generator<int, string> The key is the line number (0 indexed), the value is the line itself (line endings are trimed)
     */
    protected function readFile(): \Generator
    {
        $file = fopen($this->getFilePath(), 'rb')
            ?: throw new \RuntimeException(sprintf('Cannot open file %s', $this->getFilePath()));

        $i = 0;
        while (false !== ($line = fgets($file))) {
            yield $i++ => trim($line, "\r\n");
        }

        fclose($file);
    }
}
