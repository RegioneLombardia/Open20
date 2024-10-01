<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
$psrLogger = new \Monolog\Logger('logger');
$psrHandler = new \Monolog\Handler\RotatingFileHandler(__DIR__ . '/../runtime/logs' . '/main_' . date('Y-m-d') . '.log', 5, \Monolog\Logger::DEBUG);
$psrFormatter = new \Monolog\Formatter\LineFormatter(null, 'Y-m-d H::i::s', true);
$psrFormatter->includeStacktraces();
$psrHandler->setFormatter($psrFormatter);
$psrLogger->pushHandler($psrHandler);
$psrLogger->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());

return [
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'samdark\log\PsrTarget',
                'logger' => $psrLogger,
                // It is optional parameter. The message levels that this target is interested in.
                // The parameter can be an array.
                'levels' => [Psr\Log\LogLevel::CRITICAL, yii\log\Logger::LEVEL_ERROR],
                // It is optional parameter. Default value is false. If you use Yii log buffering, you see buffer write time, and not real timestamp.
                // If you want write real time to logs, you can set addTimestampToContext as true and use timestamp from log event context.
                'addTimestampToContext' => true,
            ],
        ],
    ],
    'urlManager' => [
        'showScriptName' => false,
        'enablePrettyUrl' => true,
        'baseUrl' => '/',
        'hostInfo' => 'SITE_URL_HERE',
    ],
    'user' => [
        'class' => 'open20\amos\core\user\AmosUser',
        'identityClass' => 'open20\amos\core\user\User',
        'enableSession' => false,
        'enableAutoLogin' => false,
    ],
    'session' => [
        'class' => 'yii\web\Session'      
    ],
];
