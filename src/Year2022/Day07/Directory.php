<?php

declare(strict_types=1);

namespace App\Year2022\Day07;

final class Directory
{
    public private(set) ?self $parent = null;
    private int $size;

    /** @var array<string, self> */
    public private(set) array $directories = [];

    /** @var array<string, File> */
    public private(set) array $files = [];

    public function __construct(
        public readonly string $name,
    ) {
    }

    public function addDirectory(self $directory): void
    {
        if (isset($this->directories[$directory->name])) throw new \RuntimeException(\sprintf('Dir %s already exists in %s', $directory->name, $this->name));

        $this->directories[$directory->name] = $directory;
        $directory->parent = $this;
    }

    public function addFile(File $file): void
    {
        if (isset($this->files[$file->name])) throw new \RuntimeException(\sprintf('File %s already exists in %s', $file->name, $this->name));

        $this->files[$file->name] = $file;
    }

    public function getDirectory(string $name): self
    {
        return $this->directories[$name] ?? throw new \RuntimeException(\sprintf('No dir %s found in %s', $name, $this->name));
    }

    public function getSize(): int
    {
        return $this->size ??= array_reduce(
            $this->files,
            fn (int $carry, File $file): int => $carry + $file->size,
            array_reduce($this->directories, fn (int $carry, self $dir): int => $carry + $dir->getSize(), 0)
        );
    }
}
