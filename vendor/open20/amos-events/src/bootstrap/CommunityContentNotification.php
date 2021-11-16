<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\bootstrap
 * @category   CategoryName
 */

namespace open20\amos\events\bootstrap;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\components\ReDirectAfterLoginComponent;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\record\ContentModel;
use open20\amos\events\AmosEvents;
use open20\amos\events\components\ReDirectComponent;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\events\models\EventPushNotification;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\rest\Controller;
use yii\web\User;

class CommunityContentNotification implements BootstrapInterface
{

    public $classes = [
        'open20\amos\news\models\News',
        'open20\amos\documenti\models\Documenti',
        'open20\amos\discussioni\models\DiscussioniTopic',
        'open20\amos\sondaggi\models\Sondaggi',
    ];

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(ContentModel::className(), ActiveRecord::EVENT_BEFORE_UPDATE, [$this, 'savePushNotification']);
    }

    public function savePushNotification($event)
    {
//        return;
        $object = $event->sender;
        if (!(Yii::$app->controller instanceof Controller)) {
            if (in_array(get_class($object), $this->classes)) {

                $moduleCwh = Yii::$app->getModule('cwh');
                if (!is_null($moduleCwh)) {
                    $scope = $moduleCwh->getCwhScope();
                    if (!empty($scope) && isset($scope['community'])) {
                        $communityId = $scope['community'];

                        $event = \open20\amos\events\models\Event::find()->andWhere(['community_id' => $communityId])->one();
                        if ($event) {
                            $Classname = get_class($object);
                            $ObjectOld = $Classname::findOne($object->id);

                            if ($ObjectOld->status != $object->status && ($object->status == $object->getValidatedStatus())) {
                                $pushNotification = new  EventPushNotification();
                                $pushNotification->event_id = $event->id;
                                $pushNotification->type = EventPushNotification::TYPE_NEW_CONTENT;
                                $pushNotification->content_class = get_class($object);
                                $pushNotification->content_id = $object->id;
                                $pushNotification->status = EventPushNotification::STATUS_DRAFT;
                                $pushNotification->save(false);
//                                die;

                            }
                        }
                    }
                }
            }
        }
    }

}