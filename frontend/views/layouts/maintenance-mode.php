<?php

use app\modules\seo\frontend\behaviors\LuyaSeoBehavior;
use open20\design\assets\BootstrapItaliaDesignAsset;
use Yii;

/* @var $this luya\web\View */
/* @var $content string */

$this->attachBehavior('seo', LuyaSeoBehavior::className());

$currentAsset = BootstrapItaliaDesignAsset::register($this);

echo Yii::$app->get('layoutmanager')->renderMainLayout([

    'customBeavior' => $customBehavior,
    'currentAsset' => $currentAsset,
    'content' => $content,
    'hideHamburgerMenuHeader' => true,
    'hideCmsMenuPluginHeader' => true,
    'hideUserMenuHeader' => true,
    'hideCookieBar' => true,
    'hideGlobalSearchHeader' => true,
    'showSocialHeader' => false,
    'showSocialFooter' => false

]);
