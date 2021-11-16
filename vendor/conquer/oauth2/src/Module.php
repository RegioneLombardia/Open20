<?php
/**
 */

namespace conquer\oauth2;

use conquer\oauth2\console\Oauth2Controller;
use yii\base\BootstrapInterface;

/**
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    public $behaviors;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $app->controllerMap[$this->id] = [
                'class' => Oauth2Controller::class,
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        if (!empty($this->behaviors)) {
            return $this->behaviors;
        } else {
            return parent::behaviors();
        }
    }
}
