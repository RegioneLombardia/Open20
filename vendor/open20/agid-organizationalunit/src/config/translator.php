<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */
return [
    'translator' => 'Module::t',
    'sourcePath' => __DIR__ . '/../',
    'messagePath' => __DIR__ . '/../messages',
    'languages' => [
        'it-IT',
        'en-GB'
    ],
    'fileTypes' => ['php'],
    'overwrite' => true,
    'exclude' => [
        '.svn',
        '.gitignore',
        'messages'
    ]
];