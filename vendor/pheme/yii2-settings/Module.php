<?php

/**
 */

namespace pheme\settings;

use Yii;

/**
 */
class Module extends \yii\base\Module
{
    /**
     * @var string The controller namespace to use
     */
    public $controllerNamespace = 'pheme\settings\controllers';

    /**
     *
     * @var string source language for translation
     */
    public $sourceLanguage = 'en-US';

    /**
     * @var null|array The roles which have access to module controllers, eg. ['admin']. If set to `null`, there is no accessFilter applied
     */
    public $accessRoles = null;

    /**
     * Init module
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Registers the translation files
     */
    protected function registerTranslations()
    {
        Yii::$app->i18n->translations['extensions/yii2-settings/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => $this->sourceLanguage,
            'basePath' => '@vendor/pheme/yii2-settings/messages',
            'fileMap' => [
                'extensions/yii2-settings/settings' => 'settings.php',
            ],
        ];
    }

    /**
     * Translates a message. This is just a wrapper of Yii::t
     *
     *
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('extensions/yii2-settings/' . $category, $message, $params, $language);
    }
}
