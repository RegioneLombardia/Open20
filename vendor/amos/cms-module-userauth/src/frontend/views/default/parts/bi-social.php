<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/proscriptions/gpl-3.0.html GNU General Public Proscription version 3
 *
 * @package amos-admin
 * @category CategoryName
 */

use amos\userauth\frontend\Module;
use open20\amos\core\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
$currentAsset = BootstrapItaliaDesignAsset::register($this);

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */
$socialAuthModule = Yii::$app->getModule('socialauth');

if ($socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) {

    //change social url

    $urlSocial = ($type == 'login') ? '/socialauth/social-auth/sign-in?provider=' : '/socialauth/social-auth/sign-up?provider=';

    if (empty($communityId)) {
        $communityId = \Yii::$app->request->get('community');
    }
    $paramCommunity = '';
    $paramsRedirectUrl = '';
    if ($communityId) {
        $paramCommunity = '&community=' . $communityId;
    } else if ($redirectUrl) {
        $paramsRedirectUrl = '';
        /**'&redirectUrl='.$redirectUrl;*/
    }


?>
   <div class="mt-5">
   
    <?= Html::tag('p', ($type == 'login') ? Module::t('Entra con i social') : Module::t('Entra con i social'), ['class' => 'h5 mb-3 border-bottom border-light pb-3']) ?>
    <div class="container-login-social mt-3">
        <?php
        foreach ($socialAuthModule->providers as $name => $config) :
        ?>

            <?php
            if (strtolower($name) == 'google') {
                $icon = $currentAsset->baseUrl . "/sprite/material-sprite.svg#" . strtolower($name);
                $orderClass = 'order-4';
            } else if (strtolower($name) == 'facebook') {
                $icon = $currentAsset->baseUrl . "/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-" . strtolower($name);
                $orderClass = 'order-1';
            } else if (strtolower($name) == 'twitter') {
                $icon = $currentAsset->baseUrl . "/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-" . strtolower($name);
                $orderClass = 'order-2';
            } else if (strtolower($name) == 'linkedin') {
                $icon = $currentAsset->baseUrl . "/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-" . strtolower($name);
                $orderClass = 'order-3';
            } else if (strtolower($name) == 'apple') {
                $icon = $currentAsset->baseUrl . "/sprite/material-sprite.svg#" . strtolower($name);
                $orderClass = 'order-5';
            } else {
                $icon = $currentAsset->baseUrl . "/sprite/material-sprite.svg#" . strtolower($name);
            }
            ?>
            
            
            <a class="mb-2 btn btn-xs btn-icon btn-<?= strtolower($name) ?> <?= $orderClass ?>" title="<?= ($type == 'login') ? Module::t('Entra con') . ' ' . $name : Module::t('Entra con') . ' ' . $name ?>" target="_self" href="<?= Yii::$app->urlManager->createAbsoluteUrl($urlSocial . strtolower($name) . $paramCommunity . $paramsRedirectUrl); ?>">
                
                    <svg class="icon icon-sm">
                        <use xlink:href="<?= $icon ?>"></use>
                    </svg>
                
                <span class="text-normal"><?= Module::t('Entra con') . ' ' . $name; ?></span>
            </a>
            
            
        <?php endforeach; ?>
    </div>
    </div>
<?php } ?>