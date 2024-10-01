<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti
 * @category   CategoryName
 */

/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */
return [
    'translator' => 'AmosDocumenti::t',
    'sourcePath' => __DIR__ . '/../',
    'messagePath' => __DIR__ . '/../messages',
    'languages' => [
        'it-IT',
        'en-US'
    ],
    'fileTypes' => ['php'],
    'overwrite' => true,
    'exclude' => [
        '.svn',
        '.gitignore',
        'messages'
    ]
];