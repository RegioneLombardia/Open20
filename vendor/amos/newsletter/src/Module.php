<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\newsletter
 * @category   CategoryName
 */

namespace amos\newsletter;

use open20\amos\core\controllers\AmosController;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use yii\base\Event;

/**
 * Class Module
 * @package amos\newsletter
 */
class Module extends AmosModule
{
    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'amos\newsletter\controllers';

    public $newFileMode = 0666;

    public $name = 'Amos Newsletter';


    /**
     * @var string
     */
    public $client_id = "b14cbe4f-0468-459f-bf9a-2de3ec3b2e85";

    /**
     * @var string
     */
    public $client_secret = "f1e4dbd1-2cd1-4e69-a449-09a988012602";

//    public $customModel = 'app\models\FormSubscribeNewsletter';
    public $customModel = 'backend\models\FormSubscribeNewsletter';
    /**
     * @var string
     */
    public $callback_uri = "";

    /**
     * @var string
     */
    public $username = "";
//    public $username = "m127571";

    /**
     * @var string
     */
    public $password = "!";
//    public $password = "ersaf2019";

    public $SMTP_username = '';
    public $SMTP_password = '';

    /**
     * @var string
     */
    public $mail_service_driver = '\amos\newsletter\drivers\MailUpClient';

    /**
     * @var bool
     */
    public $enableServiceMailGroups = true;

    /**
     * @var bool
     */
    public $enableCreateTemplateNewsletter = false;

    public $fieldIdsSubscribersGridView = [1 => 'Name', 2 => 'Surname'];

    public $confirmEmail = false;


    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'newsletter';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::setAlias('@amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [
            'PageContent' => __NAMESPACE__ . '\\' . 'models\PageContent',
            'Metadata' => __NAMESPACE__ . '\\' . 'models\Metadata',
            'MetadataType' => __NAMESPACE__ . '\\' . 'models\MetadataType',
            'PageContentSearch' => __NAMESPACE__ . '\\' . 'models\search\PageContentSearch',
            'MetadataSearch' => __NAMESPACE__ . '\\' . 'models\search\MetadataSearch'
        ];
    }

    /**
     * This method return the session key that must be used to add in session
     * the url from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKey()
    {
        return 'beginCreateNewUrl_' . self::getModuleName();
    }

    /**
     * This method return the session key that must be used to add in session
     * the url date and time creation from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKeyDateTime()
    {
        return 'beginCreateNewUrlDateTime_' . self::getModuleName();
    }


}
