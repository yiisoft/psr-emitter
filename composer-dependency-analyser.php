<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    ->disableComposerAutoloadPathScan()
    ->setFileExtensions(['php'])
    ->addPathToScan(__DIR__ . '/src', isDev: false)
    ->addPathToScan(__DIR__ . '/tests', isDev: true)
    // Optional integration: EmitterMiddleware implements PSR-15 middleware, but psr/http-server-middleware
    // (and its dependency psr/http-server-handler) is only needed by consumers who use it as middleware.
    ->ignoreErrorsOnPackage('psr/http-server-middleware', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackage('psr/http-server-handler', [ErrorType::SHADOW_DEPENDENCY]);
