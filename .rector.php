<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector as CodeQuality;
use Rector\Php53\Rector as Php53;
use Rector\Php54\Rector as Php54;
use Rector\Php55\Rector as Php55;
use Rector\Php56\Rector as Php56;
use Rector\Php70\Rector as Php70;
use Rector\Php71\Rector as Php71;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Set\ValueObject\LevelSetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/library',
    ])
    ->withRules([
        CodeQuality\Class_\CompleteDynamicPropertiesRector::class
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_85
    ])
    ->withPhpVersion(PhpVersion::PHP_82);
