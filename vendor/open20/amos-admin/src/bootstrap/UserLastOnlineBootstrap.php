<?php

namespace open20\amos\admin\bootstrap;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use yii\base\BootstrapInterface;
use yii\base\Event;
use Yii;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\bootstrap
 * @category   CategoryName
 */


/**
 * Class UserLastOnlineBootstrap
 * @package open20\amos\admin\bootstrap
 */
class UserLastOnlineBootstrap implements BootstrapInterface
{
    /**
     * AmosAdmin module
     * @var AmosAdmin $moduleAdmin
     */
    private $moduleAdmin;

    /**
     * UserLastOnlineBootstrap constructor.
     */
    public function __construct()
    {
        $this->moduleAdmin = AmosAdmin::instance();
    }

    /**
     * @param $app
     * @return void
     */
    public function bootstrap($app)
    {
        if (Yii::$app instanceof \yii\web\Application && $this->moduleAdmin->enableOnlineUsers && !Yii::$app->user->isGuest) {
            Event::on(\yii\web\Controller::className(), \yii\base\Controller::EVENT_AFTER_ACTION, [$this, 'saveLastOnline']);
        }
    }

    /**
     * Save last online datetime
     * @param $event
     * @return true|void
     */
    public function saveLastOnline($event)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            return true;
        }

        $userProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (!empty($userProfile)) {
            $userProfile->last_online = date('Y-m-d H:i:s');
            $userProfile->save(false);
        }
    }
}