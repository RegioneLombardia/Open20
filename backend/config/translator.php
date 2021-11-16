<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */
return array(
    'sourcePath' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'backend',
    'messagePath' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'backend/messages',
    'languages' => ['it', 'en'],
    'fileTypes' => array('php'),
    'overwrite' => true,
    'exclude' => array(
        '.svn',
        '.gitignore',
        'yiilite.php',
        'yiit.php',
        '/i18n/data',
        '/messages',
        '/web/js',
    ),
);