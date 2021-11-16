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
if (isset($modules['chat'])) {
    $bootstrap[] = 'chat';
}
if ($params['template-amos'] === true) {
    $bootstrap[] = 'backend\bootstrap\AssignRolesAdmin';
}

if (isset($modules['tag'])) {

    if (isset($modules['community'])) {
        $modules['tag']['modelsEnabled'][] = 'open20\amos\community\models\Community';
    }
    if (isset($modules['discussioni'])) {
        $modules['tag']['modelsEnabled'][] = 'open20\amos\discussioni\models\DiscussioniTopic';
    }
    if (isset($modules['documenti'])) {
        $modules['tag']['modelsEnabled'][] = 'open20\amos\documenti\models\Documenti';
    }
    if (isset($modules['events'])) {
        $modules['tag']['modelsEnabled'][] = 'open20\amos\events\models\Event';
    }
    if (isset($modules['news'])) {
        $modules['tag']['modelsEnabled'][] = 'open20\amos\news\models\News';
    }
    if (isset($modules['organizzazioni'])) {
        $modules['tag']['modelsEnabled'][] = 'open20\amos\organizzazioni\models\Profilo';
        $modules['tag']['modelsEnabled'][] = 'open20\amos\organizzazioni\models\ProfiloSedi';
    }
}

if (isset($modules['moodle'])) {
    $bootstrap[] = 'open20\amos\moodle\bootstrap\EventRoleUser';
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
return [
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            '*',
            '/build/'
        ],
    ],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => '/admin/security/login',
    'homeUrl' => '/dashboard',
    'id' => 'app-backend',
    'name' => 'Lombardia Informa',
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    'bootstrap' => $bootstrap,
    'components' => $components,
    'modules' => $modules,
    'params' => $params,
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
];
