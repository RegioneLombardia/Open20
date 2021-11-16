<?php

/**
 * Lombardia Informatica S.p.A.
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
    require(__DIR__ . '/modules-others.php'),
    require(__DIR__ . '/modules-amos.php')
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
    require(__DIR__ . '/components-others.php'),
    require(__DIR__ . '/components-amos.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$params = array_merge(
    $common['params'],
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
return [
    'id' => 'app-console',
    'name' => 'Lombardia Informa',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'migrate' => [
            'class' => 'open20\amos\core\migration\MigrateController',
            'migrationLookup' => array_merge(
                require(__DIR__ . '/migrations-amos.php'),
                require(__DIR__ . '/migrations-others.php')
            ),
            'migrationNamespaces' => [
                'conquer\oauth2\migrations'
            ]
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
