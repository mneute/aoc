<?php

declare(strict_types=1);

namespace App\Year2023\Day07;

final class Hand
{
    private const array CARDS_POINTS_PART_1 = [
        'A' => 'E',
        'K' => 'D',
        'Q' => 'C',
        'J' => 'B',
        'T' => 'A',
    ];
    private const array CARDS_POINTS_PART_2 = [
        'A' => 'E',
        'K' => 'D',
        'Q' => 'C',
        'J' => '1', // 👈
        'T' => 'A',
    ];
    private const int ORD_J = 74;
    public private(set) Type $part1Type;
    public private(set) int $part1Weight;
    public private(set) Type $part2Type;
    public private(set) int $part2Weight;

    /** @var array<int, int> */
    private array $charsCount;

    public function __construct(
        public private(set) readonly string $input,
        public private(set) readonly int $bid,
    ) {
        $this->part1Type = $this->getPart1Type();
        $this->part1Weight = $this->getWeight(self::CARDS_POINTS_PART_1);

        $this->part2Type = $this->getPart2Type();
        $this->part2Weight = $this->getWeight(self::CARDS_POINTS_PART_2);
    }

    /**
     * @param array<string, string> $cardsPoints
     */
    private function getWeight(array $cardsPoints): int
    {
        return \intval(
            str_replace(
                array_keys($cardsPoints),
                array_values($cardsPoints),
                $this->input,
            ),
            16
        );
    }

    private function getPart1Type(): Type
    {
        $this->charsCount = count_chars($this->input, 1);

        if (\in_array(5, $this->charsCount, true)) {
            return Type::FIVE_OF_A_KIND;
        }
        if (\in_array(4, $this->charsCount, true)) {
            return Type::FOUR_OF_A_KIND;
        }
        if (\in_array(3, $this->charsCount, true)) {
            return \in_array(2, $this->charsCount, true)
                ? Type::FULL_HOUSE
                : Type::THREE_OF_A_KIND;
        }
        if (\in_array(2, $this->charsCount, true)) {
            return 3 === \count($this->charsCount)
                ? Type::TWO_PAIR
                : Type::ONE_PAIR;
        }

        return Type::HIGH_CARD;
    }

    private function getPart2Type(): Type
    {
        $jokerCount = $this->charsCount[self::ORD_J] ?? 0;

        if (\in_array($jokerCount, [0, 5], true)) {
            return $this->part1Type;
        }

        if (1 === $jokerCount) {
            return match ($this->part1Type) {
                Type::FOUR_OF_A_KIND => Type::FIVE_OF_A_KIND,
                Type::THREE_OF_A_KIND => Type::FOUR_OF_A_KIND,
                Type::TWO_PAIR => Type::FULL_HOUSE,
                Type::ONE_PAIR => Type::THREE_OF_A_KIND,
                Type::HIGH_CARD => Type::ONE_PAIR,
                default => throw new \LogicException('Impossible'),
            };
        }

        if (2 === $jokerCount) {
            return match ($this->part1Type) {
                Type::FULL_HOUSE => Type::FIVE_OF_A_KIND,
                Type::TWO_PAIR => Type::FOUR_OF_A_KIND,
                Type::ONE_PAIR => Type::THREE_OF_A_KIND,
                default => throw new \LogicException('Impossible'),
            };
        }

        if (3 === $jokerCount) {
            return match ($this->part1Type) {
                Type::FULL_HOUSE => Type::FIVE_OF_A_KIND,
                Type::THREE_OF_A_KIND => Type::FOUR_OF_A_KIND,
                default => throw new \LogicException('Impossible'),
            };
        }

        // 4 === $jokerCount
        return match ($this->part1Type) {
            Type::FOUR_OF_A_KIND => Type::FIVE_OF_A_KIND,
            default => throw new \LogicException('Impossible'),
        };
    }
}
