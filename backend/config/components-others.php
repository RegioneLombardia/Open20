<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
return [
    'assetManager' => [
        'appendTimestamp' => true,
        'forceCopy' => false,
        'hashCallback' => function ($path) {
            return hash('md4', $path);
        },
        'converter' => [
            'class'=>'nizsheanez\assetConverter\Converter',
            'force'=> false,
            'destinationDir' => 'compiled', //at which folder of @webroot put compiled files
            'parsers' => [
                'sass' => [ // file extension to parse
                    'class' => 'nizsheanez\assetConverter\Sass',
                    'output' => 'css', // parsed output file type
                    'options' => [
                        'cachePath' => '@app/runtime/cache/sass-parser' // optional options
                    ],
                ],
                'scss' => [ // file extension to parse
                    'class' => 'open20\amos\core\converters\Scss',
                    'output' => 'css', // parsed output file type
                    'options' => [
                        'importPaths' => [
                            '@vendor/open20/amos-layout/src/assets/resources/bootstrap-italia-custom',
                            '@backend/web',
                        ], // import paths, you may use path alias here,
                        // e.g., `['@path/to/dir', '@path/to/dir1', ...]`
                        'enableCompass' => false,
                        'lineComments' => false, // if true — compiler will place line numbers in your compiled output
                        'outputStyle' => 'expanded', // May be `compressed`, `crunched`, `expanded` or `nested`,
                        // see more at http://sass-lang.com/documentation/file.SASS_REFERENCE.html#output_style
                    ],
                ],
                'less' => [ // file extension to parse
                    'class' => 'nizsheanez\assetConverter\Less',
                    'output' => 'css', // parsed output file type
                    'options' => [
                        'importDirs' => [], // import paths, you may use path alias here ex. '@app/assets/common/less'
                        'auto' => true, // optional options
                    ]
                ]
            ]
        ],
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'eventSequence' => [
        'class' => '\raoul2000\workflow\events\BasicEventSequence',
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
                'logVars' => ['_SERVER'],
            ],
        ],
    ],
    'request' => [
        'csrfParam' => '_csrf-backend',
        'enableCookieValidation' => false,
    ],
    'session' => [
        // this is the name of the session cookie used for login on the backend
        'name' => 'advanced-backend',
    ],
    'user' => [
        'class' => 'open20\amos\core\user\AmosUser',
        'identityClass' => 'open20\amos\core\user\User',
        'loginUrl' => '/admin/security/login',
        'enableAutoLogin' => true,
        'identityCookie' => [
            'name' => '_identity-backend',
            'httpOnly' => true,
            'secure' => true
        ],
    ],
    'socialShare' => [
        'class' => \open20\amos\core\components\ConfiguratorSocialShare::class,
    ],
];
