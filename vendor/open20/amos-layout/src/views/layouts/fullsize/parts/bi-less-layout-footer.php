<?php

use app\components\CmsHelper;
use open20\amos\core\helpers\Html;
use luya\admin\models\Lang;
use app\components\CmsMenu;

?>

<?php
$iconSubmenu    = '<span class="am am-chevron-right am-4"> </span>';

$mainMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['mainCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['mainCmsMenu'] : 'default';
$mainEngMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'] : 'default-eng';
$footerMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['footerCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['footerCmsMenu'] : 'footer';

$cmsDefaultMenuCustomClass = 'cms-menu-container-default';
$cmsDefaultEngMenuCustomClass = 'cms-menu-container-default cms-menu-container-default-eng';
$cmsSecondaryMenuCustomClass = 'cms-menu-container-secondary';
$cmsFooterMenuCustomClass = 'cms-menu-container-footer';

$menu3                = new CmsMenu();
$cmsDefaultMenuFooter = $menu3->luyaMenu(
    $mainMenu, $iconSubmenu, true, $currentAsset
);
$menu4                = new CmsMenu();
$cmsFooterMenu        = $menu4->luyaMenu(
    $footerMenu, $iconSubmenu, true
);

/**
 * if eng tree default menu is different to ita tree default menu
 */
if (isset(\Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'])) {
    $cmsEngDefaultMenuFooter = CmsHelper::BiHamburgerMenuRender(
        Yii::$app->menu->findAll([
            'depth' => 1,
            'container' => $mainEngMenu
        ]),
        $iconSubmenu,
        true,
        $currentAsset
    );
}

/**
 * check lang for default menu and footer menu
 */
$language_code = Yii::$app->composition['langShortCode'];
$language = Lang::findOne(['short_code' => $language_code]);

if ($language_code == 'en') {
    $cmsDefaultMenuFooter = Html::tag('ul', $cmsEngDefaultMenuFooter, ['class' => 'footer-list link-list' . ' ' . $cmsDefaultEngMenuCustomClass]);
} else {
    $cmsDefaultMenuFooter = Html::tag('ul', $cmsDefaultMenuFooter, ['class' => 'footer-list link-list' . ' ' . $cmsDefaultMenuCustomClass]);
}

/**
 * footer menu = first level default menu + footer
 */
$cmsFooterMenu = Html::tag('ul', $cmsFooterMenu, ['class' => 'footer-list link-list' . ' ' . $cmsFooterMenuCustomClass]);
$cmsFooterMenu = $cmsDefaultMenuFooter . $cmsFooterMenu;

?>
<?php
$currentAsset = isset($currentAsset) ? $currentAsset : open20\amos\layout\assets\BiLessAsset::register($this);
?>
<?php

if (isset(\Yii::$app->view->params['showSocialFooter'])) {
    $showSocialFooterCheck = (\Yii::$app->view->params['showSocialFooter']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['showSocialFooter'])) {
        $showSocialFooterCheck = (\Yii::$app->params['layoutConfigurations']['showSocialFooter']);
    } else {
        $showSocialFooterCheck = false;
    }
}

if (isset(\Yii::$app->params['layoutConfigurations']['customCopyleftFooter'])) {
    $customCopyleftFooter = (\Yii::$app->params['layoutConfigurations']['customCopyleftFooter']);
} else {
    $customCopyleftFooter = 'Powered by Open 2.0';
}

?>
<?php
if ((isset(\Yii::$app->params['layoutConfigurations']['customPlatformFooter']))) {
    $customPlatformFooter = \Yii::$app->params['layoutConfigurations']['customPlatformFooter'];
    echo $this->render(
        $customPlatformFooter,
        [
            'currentAsset' => $currentAsset,
            'cmsFooterMenu' => $cmsFooterMenu,
            'showSocial' => $showSocialFooterCheck,
            'customCopyleftFooter' => $customCopyleftFooter,
        ]
    );
} else {
    echo $this->render("bi-less-footer", [
        'currentAsset' => $currentAsset,
        'cmsFooterMenu' => $cmsFooterMenu,
        'showSocial' => $showSocialFooterCheck,
        'customCopyleftFooter' => $customCopyleftFooter,
    ]);
}
?>