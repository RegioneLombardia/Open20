<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\socialauth
 * @category   CategoryName
 */

namespace open20\amos\socialauth\components;

use open20\amos\core\icons\AmosIcons;
use open20\amos\socialauth\Module;
use Yii;
use yii\base\Component;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class FileImport
 * @package open20\amos\socialauth\components
 */
class SocialAuthBar extends Widget
{
    public function run()
    {
        parent::run();

        /**
         * Return string
         */
        $result = '';

        /**
         * @var $module Module
         */
        $module = Yii::$app->getModule('socialauth');

        /**
         * List of providers configured
         */
        $providers = $module->providers;

        return $this->render('social-auth-bar', [
            'providers' => $providers
        ]);
    }
}
