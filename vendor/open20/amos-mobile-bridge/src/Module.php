<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge;

use open20\amos\core\module\AmosModule;
use open20\amos\mobile\bridge\controllers\NotificationController;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\web\Application;

/**
 * Class Module
 * @package open20\amos\mobile\bridge
 */
class Module extends AmosModule implements BootstrapInterface
{
    public static $CONFIG_FOLDER = 'config';

    /**
     * @inheritdoc
     */
    static $name = 'mobilebridge';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\mobile\bridge\controllers';
    public $timeout             = 180;

    public function __construct($id, $parent = null, $config = array())
    {
        $local_config = ArrayHelper::merge(
                require(__DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php'),
                $config
        );
        parent::__construct($id, $parent, $local_config);
    }

    /**
     * @throws Exception
     */
    public function init()
    {
        parent::init();

        //Configuration
        //$config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
        //Yii::configure($this, ArrayHelper::merge($config, $this));

        if (!is_null(Yii::$app->request)) {
            if (strpos(Yii::$app->request->url, self::getModuleName())) {

                //Override user identity
                Yii::$app->set('user', $this->user);

                //Override request component
                Yii::$app->set('request', $this->request);
            }
        }
    }

    public function bootstrap($app)
    {
        if(Yii::$app->request->headers->has('authorization')) {
            //Set mobile mode
            \Yii::$app->session->set('isMobile', true);
        }

        if ($app instanceof Application) {
            $notificationController = new NotificationController('notifications', $this);

            Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, [
                $notificationController, 'afterActiveRecordCreate'
            ]);
        }
    }

    public static function getModuleName()
    {
        return self::$name;
    }

    public function getWidgetIcons()
    {
        return [
        ];
    }

    public function getWidgetGraphics()
    {
        return [
        ];
    }

    protected function getDefaultModels()
    {
        return [
        ];
    }
}