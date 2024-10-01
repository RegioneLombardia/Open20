<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\bootstrap
 * @category   CategoryName
 */

namespace open20\amos\admin\bootstrap;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\User;

/**
 * Class RedirectAfterLogin
 * @package open20\amos\admin\bootstrap
 */
class UserAccessLogBootstrap implements BootstrapInterface
{
    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(User::className(), User::EVENT_AFTER_LOGIN, [$this, 'log']);
    }
    
    /**
     * @param $event
     * @throws \yii\base\InvalidConfigException
     */
    public function log($event)
    {
        \open20\amos\admin\models\UserAccessLog::saveLog();
    }
}
