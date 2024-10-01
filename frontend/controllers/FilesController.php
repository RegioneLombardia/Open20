<?php

namespace app\controllers;

use open20\amos\core\controllers\AmosController;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class FilesController extends AmosController
{
    
    /**
    * Extensions locked
    */
    private $notExpectedFormats = [
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
                            'index',
                            'img',
                        ],
                        'allow' => true,
                        'roles' => ['?','@'],
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
             * If the extension is not alowed
             */
            if (in_array($fileInfo['extension'], $this->notExpectedFormats)) {
                throw new NotFoundHttpException('File Not Found');
            }

            return Yii::$app->response->sendFile($filePath);
        }

        throw new NotFoundHttpException('File Not Found');
    }
    
    /**
     * 
     * @param type $file
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionImg($file)
    {
        $filePath = __DIR__ . '/../web/img' . $file;

        //If file exists
        if (file_exists($filePath) && !strstr($file, '../')) {
            /**
             * @var $fileInfo array
             */
            $fileInfo = pathinfo($filePath);

            /**
             * If the extension is not alowed
             */
            if (in_array($fileInfo['extension'], $this->notExpectedFormats)) {
                throw new NotFoundHttpException('File Not Found');
            }

            return Yii::$app->response->sendFile($filePath);
        }

        throw new NotFoundHttpException('File Not Found');
    }
}
