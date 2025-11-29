<?php

declare(strict_types=1);
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/.castor',
        __DIR__ . '/src',
    ])
    ->withSkip([
        __DIR__.'/.castor/.castor.stub.php'
    ])
    ->withRootFiles()
    ->withPhpSets()
    ->withImportNames(importShortClasses: false, removeUnusedImports: true)
    ->withCache(__DIR__ . '/cache/rector');
