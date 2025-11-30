<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = new Finder()
    ->in([__DIR__, __DIR__ . '/.castor'])
    ->exclude(['vendor'])
    ->notPath(['.castor/.castor.stub.php']);

return new Config()
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'class_attributes_separation' => ['elements' => ['property' => 'only_if_meta', 'method' => 'one']],
        'concat_space' => ['spacing' => 'one'],
        'control_structure_braces' => false,
        'declare_strict_types' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'new_with_parentheses' => true,
        'no_extra_blank_lines' => ['tokens' => ['extra', 'break', 'curly_brace_block', 'parenthesis_brace_block', 'square_brace_block', 'throw', 'use', 'switch', 'case', 'default']],
        'types_spaces' => ['space' => 'single'],
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/cache/phpcsfixer.cache');
