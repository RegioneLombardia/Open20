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
use open20\amos\socialauth\models\SocialAuthUsers;
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
class SocialLinkBar extends Widget
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

        /**
         * @var $enabledProviders array List of providers not yet linked
         */
        $enabledProviders = [];

        /**
         * Iterate all provider and find existing links
         */
        foreach ($providers as $providerName=>$config) {
            $lowCaseName = strtolower($providerName);

            /**
             * @var $socialAccount SocialAuthUsers
             */
            $socialAccount = SocialAuthUsers::findOne([
                'provider' => $lowCaseName,
                'user_id' => Yii::$app->user->id
            ]);

            /**
             * If the user profile is not linked to this user append the provider
             */
            if(!$socialAccount || !$socialAccount->id) {
                $enabledProviders[$providerName] = $config;
            }
        }

        return $this->render('social-link-bar', [
            'providers' => $enabledProviders
        ]);
    }
}
