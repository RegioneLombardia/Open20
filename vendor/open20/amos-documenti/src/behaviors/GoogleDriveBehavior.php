<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 16/12/2019
 * Time: 11:25
 */

namespace open20\amos\documenti\behaviors;


use open20\amos\documenti\models\Documenti;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class GoogleDriveBehavior extends Behavior
{

    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [
            ActiveRecord::EVENT_AFTER_INSERT => 'saveDrive',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveDrive',
        ];

        return $events;
    }

    /**
     * @param $event
     * @throws \Google_Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function saveDrive($event){
        /** @var  $model Documenti*/
        $model = $this->owner;
        $GoogleDriveManager = new \open20\amos\documenti\utility\GoogleDriveManager(['model' => $model]);
//        pr(\Yii::$app->request->post());die;
        $fileId = \Yii::$app->request->post('fileid');
        $filename = \Yii::$app->request->post('filename');
        if(!empty($GoogleDriveManager)){
            if($model->drive_file_id != $fileId) {
                $GoogleDriveManager->getFileAndSave($fileId, $filename);
                $GoogleDriveManager->shareWithUser($fileId);
//                die;
            }
        }
    }

}