<?php

namespace frontend\modules\socialwall\behaviors;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;

class SocialwallBehavior extends \yii\base\Behavior
{

    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [
//            ActiveRecord::EVENT_AFTER_DELETE => 'deleteSocialwallPosts',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveSocialwallPosts',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveSocialwallPosts',
//            ActiveRecord::EVENT_BEFORE_VALIDATE => 'evalAttributes',
//            ActiveRecord::EVENT_AFTER_VALIDATE => 'unevalAttributes'
        ];

        return $events;
    }

    public function saveSocialwallPosts($event) {
        if (\Yii::$app->request->isConsoleRequest || !\Yii::$app->request->isPost) {
            return false;
        }

        VarDumper::dump($event, 20, true);

        die;
    }

}