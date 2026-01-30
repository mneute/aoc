<?php

declare(strict_types=1);

namespace App\Year2023\Day19;

final readonly class Workflow
{
    private const string REGEXP = '#^((?<letter>[xmas])(?<op><|>)(?<limit>\d+)\:)?(?<outcome>[ARa-z]+)$#';

    /** @var list<callable(Part): ?string> */
    private array $steps;

    public function __construct(public string $input)
    {
        $steps = [];
        foreach (explode(',', $this->input) as $step) {
            preg_match(self::REGEXP, $step, $matches);
            \assert(\array_key_exists('outcome', $matches)
                && \array_key_exists('limit', $matches)
                && \array_key_exists('op', $matches));

            $outcome = $matches['outcome'];

            if ('' === ($matches['letter'] ?? '')) {
                $steps[] = static fn (Part $part): string => $outcome;
                continue;
            }

            [
                'letter' => $letter,
                'op' => $op,
                'limit' => $limit,
            ] = $matches;

            if ('<' === $op) {
                $steps[] = static fn (Part $part): ?string => $part->{$letter} < $limit ? $outcome : null;
            } else {
                $steps[] = static fn (Part $part): ?string => $part->{$letter} > $limit ? $outcome : null;
            }
        }
        $this->steps = $steps;
    }

    public function run(Part $part): string
    {
        foreach ($this->steps as $step) {
            $result = $step($part);
            if (\is_string($result)) return $result;
        }

        throw new \RuntimeException(\sprintf('The workflow %s could not handle the part %s', $this->input, $part->input));
    }
}
