<?php
use app\assets\ResourcesAsset;
use app\components\CmsHelper;
use app\modules\seo\frontend\behaviors\LuyaSeoBehavior;
use open20\design\assets\BootstrapItaliaCustomAsset;

$assetBundle = ResourcesAsset::register($this);
$module = \Yii::$app->getModule('design');

if ($module) {

    //$currentAsset = BootstrapItaliaCustomAsset::register($this);


    $customBehavior = $this->attachBehavior('seo', LuyaSeoBehavior::className());

    $iconSubmenu = '<svg class="icon right"><use xlink:href="' . $currentAsset->$baseUrl . '/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-chevron-right"></use>';

    $cmsDefaultMenu = CmsHelper::BiHamburgerMenuRender(Yii::$app->menu->findAll(['depth' => 1, 'container' => 'default']),$iconSubmenu, 'cms-menu-container-default',false);
    $cmsFooterMenu = CmsHelper::BiHamburgerMenuRender(Yii::$app->menu->findAll(['depth' => 1, 'container' => 'footer']),$iconSubmenu, 'cms-menu-container-footer',true);

    echo $this->render(
        '@vendor/open20/design/src/views/layouts/bi-main-layout',
        [
            'content' => $content,
            'customBeavior' => $customBehavior,
            'cmsDefaultMenu' => $cmsDefaultMenu,
            'cmsFooterMenu' => $cmsFooterMenu,
        ]
    );
}
