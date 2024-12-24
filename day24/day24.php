<?php

declare(strict_types=1);

$startTime = microtime(true);
memory_reset_peak_usage();

$knownStates = [];
$logicGates = [];

$hasMetEmptyLine = false;

$file = fopen(__DIR__.'/input.txt', 'rb') ?: throw new \RuntimeException('Could not open file');
while (($line = fgets($file)) !== false) {
    $line = trim($line);

    if ('' === $line) {
        $hasMetEmptyLine = true;
        continue;
    }

    if (!$hasMetEmptyLine) {
        [$wire, $value] = explode(': ', $line);
        $knownStates[$wire] = (int) $value;
    } else {
        [$input, $output] = explode(' -> ', $line);
        $logicGates[$output] = $input;
    }
}

$z = array_filter($logicGates, static fn (string $key): bool => str_starts_with($key, 'z'), ARRAY_FILTER_USE_KEY);
krsort($z);
$z = array_map(evalLogicGate(...), array_keys($z), $z);

printf('Part 1 : %s'.PHP_EOL, bindec(implode('', $z)));

printf('Execution time : %s seconds'.PHP_EOL, round(microtime(true) - $startTime, 4));
printf('  Memory usage : %s Mib'.str_repeat(PHP_EOL, 2), round(memory_get_peak_usage() / (2 ** 20), 4));

function evalLogicGate(string $wire, string $input): int
{
    global $knownStates, $logicGates;

    if (isset($knownStates[$wire])) return $knownStates[$wire];

    [$a, $operation, $b] = explode(' ', $input);

    $valA = $knownStates[$a] ?? evalLogicGate($a, $logicGates[$a]);
    $valB = $knownStates[$b] ?? evalLogicGate($b, $logicGates[$b]);

    return $knownStates[$wire] = match ($operation) {
        'AND' => $valA & $valB,
        'OR' => $valA | $valB,
        'XOR' => $valA ^ $valB,
        default => throw new \RuntimeException(sprintf('Unmanaged operation : %s', $operation))
    };
}
