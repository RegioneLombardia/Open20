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
use open20\amos\events\AmosEvents;
use open20\amos\events\components\ReDirectComponent;
use open20\amos\events\models\EventGroupReferentMm;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\rest\Controller;
use yii\web\User;

class CompleteOperatorCourse implements BootstrapInterface
{

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(User::className(), User::EVENT_AFTER_LOGIN, [$this, 'startUpRedirect']);
    }

    public function startUpRedirect($event)
    {
        if (!(Yii::$app->controller instanceof Controller)) {
            $moodleModule = \Yii::$app->getModule('moodle');
            /** @var  $moduleEvents AmosEvents */
            $moduleEvents = \Yii::$app->getModule('events');
            if (!empty($moodleModule) && !empty($moduleEvents)) {
                if ($moduleEvents->operatorCandidate && !empty($moduleEvents->operatorCandidate['enabled'])) {
                    if (\Yii::$app->user->can($moduleEvents->operatorCandidate['role'])) {
                        $count = EventGroupReferentMm::find()
                            ->andWhere(['user_id' => \Yii::$app->user->id, 'exclude_from_query' => true])
                            ->andWhere(['status' => null])->count();
                        if ($count >0) {
                            $component = new ReDirectComponent();
                            $component->redirectToUrl(\Yii::$app->params['platform']['backendUrl'].'/events/event-group-referent-mm/complete-course');
                            Yii::$app->response->send();
                        }
                    }

                }

            }
        }
    }
}