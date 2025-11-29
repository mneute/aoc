<?php

declare(strict_types=1);

namespace App\Year2022\Day07;

use App\AbstractPuzzle;
use App\Result;

final class Day07 extends AbstractPuzzle
{
    private const int THRESHOLD = 100_000;
    private const int DISK_SIZE = 70_000_000;
    private const int DESIRED_UNUSED_SPACE = 30_000_000;

    private readonly Directory $root;
    private ?Directory $currentDir = null;

    public function __construct(bool $test)
    {
        parent::__construct($test);
        $this->root = new Directory('/');
    }

    public function run(): Result
    {
        foreach ($this->readFile() as $line) {
            if (str_starts_with($line, '$')) {
                $this->interpretCommand($line);
            } else {
                $this->addFileOrDir($line);
            }
        }

        $pt1 = $this->accumulateDirSizesSmallerThanThreshold($this->root);

        $pt2 = $this->getSmallestDirSizeToRemove(
            $this->root,
            self::DESIRED_UNUSED_SPACE - (self::DISK_SIZE - $this->root->getSize()),
        );

        return new Result($pt1, $pt2);
    }

    private function interpretCommand(string $line): void
    {
        $argv = explode(' ', substr($line, 2));
        $command = $argv[0] ?? throw new \RuntimeException(sprintf('Missing command for line "%s"', $line));

        if ('cd' === $command) {
            $dirname = $argv[1] ?? throw new \RuntimeException(sprintf('Missing argument for command cd in line "%s"', $line));
            if ('/' === $dirname) {
                $this->currentDir = $this->root;
            } else if ('..' === $dirname) {
                $this->currentDir = $this->currentDir->parent;
            } else {
                $this->currentDir = $this->currentDir->getDirectory($dirname);
            }
        } else if ('ls' !== $command) {
            throw new \UnexpectedValueException(sprintf('Unknown command "%s"', $line));
        }
    }

    private function addFileOrDir(string $line): void
    {
        $parts = explode(' ', $line);
        if (2 !== count($parts)) throw new \InvalidArgumentException(sprintf('Invalid line "%s"', $line));

        if ('dir' === $parts[0]) {
            $this->currentDir->addDirectory(new Directory($parts[1]));
            return;
        }

        $this->currentDir->addFile(new File($parts[1], (int) $parts[0]));
    }

    private function accumulateDirSizesSmallerThanThreshold(Directory $dir, int $currentSize = 0): int
    {
        if ($dir->getSize() <= self::THRESHOLD) {
            $currentSize += $dir->getSize();
        }
        foreach ($dir->directories as $subDir) {
            $currentSize = $this->accumulateDirSizesSmallerThanThreshold($subDir, $currentSize);
        }
        return $currentSize;
    }

    private function getSmallestDirSizeToRemove(Directory $dir, int $targetSizeToRemove, int $currentSmallest = PHP_INT_MAX): int
    {
        if ($dir->getSize() >= $targetSizeToRemove) {
            $currentSmallest = min($currentSmallest, $dir->getSize());
        }
        foreach ($dir->directories as $subDir) {
            $currentSmallest = $this->getSmallestDirSizeToRemove($subDir, $targetSizeToRemove, $currentSmallest);
        }

        return $currentSmallest;
    }
}
