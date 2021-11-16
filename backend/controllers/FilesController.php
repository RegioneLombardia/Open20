<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class FilesController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Return the file or exception
     * @param $file
     * @return $this
     * @throws NotFoundHttpException
     */
    public function actionIndex($file)
    {
        $filePath = __DIR__ . '/../web/' . $file;

        //If file exists
        if (file_exists($filePath) && !strstr($file, '../')) {
            /**
             * @var $fileInfo array
             */
            $fileInfo = pathinfo($filePath);

            /**
             * Extensions locked
             */
            $notExpectedFormats = [
                'cgi-script',
                'php',
                'php2',
                'php3',
                'php4',
                'php5',
                'php6',
                'php7',
                'php8',
                'pl',
                'py',
                'js',
                'jsp',
                'asp',
                'htm',
                'html',
                'shtml',
                'sh',
                'cgi',
                'git',
                'htaccess',
                'htpasswd'
            ];

            /**
             * If the extension is not alowed
             */
            if (in_array($fileInfo['extension'], $notExpectedFormats)) {
                throw new NotFoundHttpException('File Not Found');
            }

            return Yii::$app->response->sendFile($filePath);
        }

        throw new NotFoundHttpException('File Not Found');
    }
}
