<?php

declare(strict_types=1);

namespace App\Year2020\Day04;

final class Passport
{
    private const string REGEX = '/\b([a-z]{3}):(\S+)\b/';
    private ?int $birthYear = null;
    private ?int $issueYear = null;
    private ?int $expirationYear = null;
    private ?Height $height = null;
    private ?string $hairColor = null;
    private ?string $eyeColor = null;
    private ?string $passportID = null;

    public function __construct(string $input)
    {
        foreach (explode(' ', $input) as $part) {
            if (1 !== preg_match(self::REGEX, $part, $matches)) throw new \InvalidArgumentException(\sprintf('Invalid part : %s', $part));

            $key = $matches[1];
            $value = $matches[2];

            switch ($key) {
                case 'byr':
                    $this->birthYear = (int) $value;
                    break;
                case 'iyr':
                    $this->issueYear = (int) $value;
                    break;
                case 'eyr':
                    $this->expirationYear = (int) $value;
                    break;
                case 'hgt':
                    $this->height = new Height($value);
                    break;
                case 'hcl':
                    $this->hairColor = $value;
                    break;
                case 'ecl':
                    $this->eyeColor = $value;
                    break;
                case 'pid':
                    $this->passportID = $value;
                    break;
                case 'cid':
                    // Country ID, can be ignored
                    break;
                default:
                    throw new \UnexpectedValueException(\sprintf('Unexpected key : %s', $key));
            }
        }
    }

    public function hasAllRequiredFields(): bool
    {
        return !\in_array(null, [$this->birthYear, $this->issueYear, $this->expirationYear, $this->hairColor, $this->eyeColor, $this->passportID], true)
            && $this->height instanceof Height;
    }

    public function isValid(): bool
    {
        \assert(\is_int($this->birthYear));
        \assert(\is_int($this->issueYear));
        \assert(\is_int($this->expirationYear));
        \assert($this->height instanceof Height);
        \assert(\is_string($this->hairColor));
        \assert(\is_string($this->eyeColor));
        \assert(\is_string($this->passportID));

        return 1920 <= $this->birthYear && $this->birthYear <= 2002
            && 2010 <= $this->issueYear && $this->issueYear <= 2020
            && 2020 <= $this->expirationYear && $this->expirationYear <= 2030
            && $this->height->isValid()
            && 1 === preg_match('/^#[0-9a-f]{6}$/', $this->hairColor)
            && \in_array($this->eyeColor, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'], true)
            && 1 === preg_match('/^\d{9}$/', $this->passportID);
    }
}
