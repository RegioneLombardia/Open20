<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$modules = array_merge(
    require(__DIR__ . '/modules-others.php'),
    require(__DIR__ . '/modules-amos.php')
);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$bootstrap = array_merge(
    require(__DIR__ . '/bootstrap-extra.php')
);

//Installation file
if (file_exists(__DIR__ . '/install.php')) {
    $bootstrap = array_merge(
        include(__DIR__ . '/install.php'),
        $bootstrap
    );
}

if (isset($modules['chat'])) {
    $bootstrap[] = 'chat';
}
if (isset($modules['cwh'])) {
    $bootstrap[] = 'cwh';
}
if (isset($modules['tag'])) {
    $bootstrap[] = 'tag';
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$components = array_merge(
    require(__DIR__ . '/components-others.php'),
    require(__DIR__ . '/components-amos.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
return [
    'aliases' => [
        '@file' => dirname(__DIR__),
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => 'it-IT',
    'timeZone' => 'Europe/Rome',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'Amos Basic Template',
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    'bootstrap' => $bootstrap,
    'components' => $components,
    'modules' => $modules,
    'params' => $params,
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
];
