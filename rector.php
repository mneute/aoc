<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/.castor',
        __DIR__ . '/src',
    ])
    ->withSkip([
        __DIR__ . '/.castor/.castor.stub.php',
        CompleteMissingIfElseBracketRector::class,
    ])
    ->withRootFiles()
    ->withPhpSets()
    ->withImportNames(importShortClasses: false, removeUnusedImports: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        typeDeclarationDocblocks: true,
        privatization: true,
        instanceOf: true,
    )
    ->withCache(__DIR__ . '/cache/rector');
