<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

$common = require(__DIR__ . '/../../common/config/main.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$modules = array_merge(
    $common['modules'],
    require(__DIR__ . '/modules.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// TODO verificare che non ci siano index che non caricano bootstrap, in caso non ce ne siano, questa va eliminata
$bootstrap = array_merge(
    $common['bootstrap'],
    require(__DIR__ . '/bootstrap.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$components = array_merge(
    $common['components'],
    require(__DIR__ . '/components.php'),
    require(__DIR__ . '/components-local.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
return [
    'name' => 'Open 2.0',
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'migrate' => [
            'class' => 'open20\amos\core\migration\MigrateController',
            'migrationLookup' => require(__DIR__ . '/migrations.php'),
        ],
        'language' => [
            'class' => 'open20\amos\core\commands\LanguageSourceController',
        ],
        'userutility' => [
            'class' => 'open20\amos\admin\commands\UserUtilityController',
        ],
        'utility' => [
            'class' => 'open20\amos\utility\commands\UtilityController',
        ],
        'attach' => [
            'class' => 'open20\amos\attachments\commands\AttachController',
        ],
    ],
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    'aliases' => [
        '@web' => '',
    ],
    'bootstrap' => $bootstrap,
    'components' => $components,
    'modules' => $modules,
    'params' => $params,
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
];
