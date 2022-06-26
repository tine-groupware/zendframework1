<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\PHPUnit\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/tests',
    ]);

    // register a single rule
    // $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    // $rectorConfig->sets([
    //     LevelSetList::UP_TO_PHP_71
    // ]);
    // $rectorConfig->skip([
    //     AddDoesNotPerformAssertionToNonAssertingTestRector::class,
    // ]);
    $rectorConfig->rule(AddDoesNotPerformAssertionToNonAssertingTestRector::class);
    // $rectorConfig->sets([
    //     PHPUnitLevelSetList::UP_TO_PHPUNIT_100, 
    // ]);
};
