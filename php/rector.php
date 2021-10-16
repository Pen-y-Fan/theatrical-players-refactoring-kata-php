<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\CompleteVarDocTypePropertyRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
https://github.com/rectorphp/rector
composer require rector/rector --dev

"scripts": {
    "rector-dry-run": "rector process --dry-run --ansi -vvv",
    "rector": "rector process --ansi -vvv"
}

batch files:
rdr.bat = composer rector-dry-run
rec.bat = composer rector

 */
return static function (ContainerConfigurator $containerConfigurator): void {

    // Define what rule sets will be applied
    // Contains RemoveUnusedPromotedPropertyRector which requires PHP 8 !
     $containerConfigurator->import(SetList::DEAD_CODE);

    // here we can define, what sets of rules will be applied
    // tip: use "SetList" class to autocomplete sets
    $containerConfigurator->import(SetList::PHP_74);
    $containerConfigurator->import(SetList::PHP_80);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_80); // Upgrade PHPUnit to 8, run one at a time
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_90);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_91);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();
    $services->set(ReturnTypeDeclarationRector::class);
    $services->set(TypedPropertyRector::class);
    $services->set(CompleteVarDocTypePropertyRector::class);

    // get parameters
    $parameters = $containerConfigurator->parameters();

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(
        Option::PATHS,
        [
            __DIR__ . '/src',
            __DIR__ . '/tests'
        ]
    );

    // is your PHP version different from the one your refactor to? [default: your PHP version], uses PHP_VERSION_ID format
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_80);

    // Path to PhpStan with extensions, that PhpStan in Rector uses to determine types
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, getcwd() . '/phpstan.neon');
};
