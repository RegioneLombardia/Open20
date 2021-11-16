<?php
/**
 */

namespace dosamigos\fileupload;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 * Base class for both uploaders.
 *
 */
class BaseUpload extends InputWidget
{
    /**
     * @var string|array upload route
     */
    public $url;
    /**
     * @var array the plugin options. For more information see the jQuery File Upload options documentation.
     */
    public $clientOptions = [];
    /**
     * @var array the event handlers for the jQuery File Upload plugin.
     * Please refer to the jQuery File Upload plugin web page for possible options.
     */
    public $clientEvents = [];
    /**
     * @var array for the internalization configuration
     */
    public $i18n = [];

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->initI18N();

        if(empty($this->url)) {
            throw new InvalidConfigException('"url" cannot be empty.');
        }

        $this->clientOptions['url'] = Url::to($this->url);
    }

    /**
     * Initialize internalization
     */
    public function initI18N()
    {
        Yii::setAlias('@fileupload', dirname(__FILE__));
        if (empty($this->i18n)) {
            $this->i18n = [
                'sourceLanguage' => 'en',
                'basePath' => '@fileupload/messages',
                'class' => 'yii\i18n\PhpMessageSource',
            ];
        }
        Yii::$app->i18n->translations['fileupload'] = $this->i18n;
    }
}
