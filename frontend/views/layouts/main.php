<?php

use app\modules\seo\frontend\behaviors\LuyaSeoBehavior;
use app\components\CmsHelper;
use open20\design\Module;
use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\helpers\Html;
use luya\admin\models\Lang;
use Yii;
use app\components\CmsMenu;
use luya\helpers\Url;

/* @var $this luya\web\View */
/* @var $content string */

$this->attachBehavior('seo', LuyaSeoBehavior::className());

$currentAsset = BootstrapItaliaDesignAsset::register($this);
$iconSubmenu = '<svg class="icon icon-expand icon-sm"><use xlink:href="' . $currentAsset->baseUrl . '/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use></svg>';

$searchPage = (isset(\Yii::$app->params['linkConfigurations']['pageSearchLinkCommon'])) ? \Yii::$app->params['linkConfigurations']['pageSearchLinkCommon'] : '/it/ricerca';

$myOpenMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['myOpenCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['myOpenCmsMenu'] : 'myopen';
$mainMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['mainCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['mainCmsMenu'] : 'default';
$mainEngMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'] : 'default-eng';
$secondaryMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu'] : 'secondary';
$footerMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['footerCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['footerCmsMenu'] : 'footer';


$cmsMyOpenMenuCustomClass = 'cms-menu-container-myopen';
$cmsDefaultMenuCustomClass = 'cms-menu-container-default';
$cmsDefaultEngMenuCustomClass = 'cms-menu-container-default cms-menu-container-default-eng';
$cmsSecondaryMenuCustomClass = 'cms-menu-container-secondary';
$cmsFooterMenuCustomClass = 'cms-menu-container-footer';
$cmsPluginMenuCustomClass = 'cms-menu-container-plugin';


$menu1                = new CmsMenu();
$cmsMyOpenMenu        = $menu1->luyaMenu(
    $myOpenMenu, $iconSubmenu, false, $currentAsset
);
$menu2                = new CmsMenu();
$cmsDefaultMenu       = $menu2->luyaMenu(
    $mainMenu, $iconSubmenu, false, $currentAsset
);
$menu3                = new CmsMenu();
$cmsDefaultMenuFooter = $menu3->luyaMenu(
    $mainMenu, $iconSubmenu, true, $currentAsset
);
$menu4                = new CmsMenu();
$cmsFooterMenu        = $menu4->luyaMenu(
    $footerMenu, $iconSubmenu, true
);

if (!\Yii::$app->params['layoutConfigurations']['hideCmsMenuPluginHeader']) {
    $cmsPluginMenu = open20\amos\core\module\AmosModule::getModulesFrontEndMenus();
}

if ( isset(\Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu']) || \Yii::$app->params['layoutConfigurations']['showSecondaryMenuHeader'] ) {
    $menu5            = new CmsMenu();
    $cmsSecondaryMenu = $menu5->luyaMenu(
        $secondaryMenu, $iconSubmenu, false
    );
}

/**
 * if eng tree default menu is different to ita tree default menu
 */
if (isset(\Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'])) {
    $menu6                   = new CmsMenu();
    $cmsEngDefaultMenu       = $menu6->luyaMenu(
        $mainEngMenu, $iconSubmenu, false
    );
    $menu7                   = new CmsMenu();
    $cmsEngDefaultMenuFooter = $menu7->luyaMenu(
        $mainEngMenu, $iconSubmenu, true, $currentAsset
    );
}

/**
 * check lang for default menu and footer menu
 */
$language_code = Yii::$app->composition['langShortCode'];
$language = Lang::findOne(['short_code' => $language_code]);

if ($language_code == 'en') {
    $cmsDefaultMenu = Html::tag('ul', $cmsEngDefaultMenu, ['class' => 'navbar-nav' . ' ' . $cmsDefaultEngMenuCustomClass]);
    $cmsDefaultMenuFooter = Html::tag('ul', $cmsEngDefaultMenuFooter, ['class' => 'footer-list link-list' . ' ' . $cmsDefaultEngMenuCustomClass]);
} else {
    $cmsDefaultMenu = Html::tag('ul', $cmsDefaultMenu, ['class' => 'navbar-nav' . ' ' . $cmsDefaultMenuCustomClass]);
    $cmsDefaultMenuFooter = Html::tag('ul', $cmsDefaultMenuFooter, ['class' => 'footer-list link-list' . ' ' . $cmsDefaultMenuCustomClass]);
}

/**
 * myOpenMenu first default menu
 */

$cmsMyOpenMenu = Html::tag('ul', $cmsMyOpenMenu, ['class' => 'navbar-nav' . ' ' . $cmsMyOpenMenuCustomClass]);

$cmsDefaultMenu = $cmsMyOpenMenu . $cmsDefaultMenu;

$cmsSecondaryMenu = Html::tag('ul', $cmsSecondaryMenu, ['class' => 'navbar-nav' . ' ' . $cmsSecondaryMenuCustomClass]);

$cmsPluginMenu = Html::tag('ul', $cmsPluginMenu, ['class' => 'navbar-nav' . ' ' . $cmsPluginMenuCustomClass]);

/**
 * footer menu = first level default menu + footer
 */
$cmsFooterMenu = Html::tag('ul', $cmsFooterMenu, ['class' => 'footer-list link-list' . ' ' . $cmsFooterMenuCustomClass]);
$cmsFooterMenu = $cmsDefaultMenuFooter . $cmsFooterMenu;

/**
 * if hideTopHeaderForGuestUser is enable, auto add platform access in menu
 */
if (
    isset(\Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser']) &&
    \Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser']
) {

    $labelSigninOrSignup = Module::t('amosdesign', 'Accedi o Registrati');
    $titleSigninOrSignup = Module::t('amosdesign', 'Accedi o registrati alla piattaforma {platformName}', ['platformName' => \Yii::$app->name]);
    $socialAuthModule = Yii::$app->getModule('socialauth');
    if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
        $labelSigninOrSignup = Module::t('amosdesign', 'Accedi');
        $titleSigninOrSignup = Module::t('amosdesign', 'Accedi alla piattaforma {platformName}', ['platformName' => \Yii::$app->name]);
    }
    $labelLogout = Module::t('amosdesign','Esci');
    $titleLogout = Module::t('amosdesign','Esci dalla piattaforma {platformName}',['platformName' => \Yii::$app->name]);

    $iconLogin      = '<span class="mdi mdi-key-variant icon-login ml-auto pl-1"></span>';
    $iconLogout      = '<span class="mdi mdi-exit-to-app icon-login ml-auto pl-1"></span>';

    if (Yii::$app->user->isGuest) {
        $actionLoginMenu = Html::a(
            $labelSigninOrSignup . $iconLogin,
            [
                \Yii::$app->params['linkConfigurations']['loginLinkCommon']
            ],
            [
                'title' => $titleSigninOrSignup,
                'class' => 'nav-link'
            ]
        );
    } else {
        $actionLoginMenu = Html::a(
            $labelLogout . $iconLogout,
            [
                \Yii::$app->params['linkConfigurations']['logoutLinkCommon']
            ],
            [
                'title' => $titleLogout,
                'class' => 'nav-link'
            ]
        );
    }

    $alternativeLoginMenuClass = 'cms-menu-container-hide-top-header-guest';

    $alternativeLoginMenuItem = Html::tag(
        'li',
        $actionLoginMenu,
        [
            'class' => 'nav-item'
        ]
    );

    $alternativeLoginMenu = Html::tag('ul', $alternativeLoginMenuItem, ['class' => 'navbar-nav' . ' ' . $alternativeLoginMenuClass]);

    $cmsDefaultMenu = $cmsDefaultMenu . $alternativeLoginMenu;
}

$currentPageUrl = Url::toInternal(['/admin/default/index', '#' => '!/template/cmsadmin~2Fdefault~2Findex/update/' . Yii::$app->menu->current->navId], true);

$controllerCmsBackend = (isset($this->params['angularCmsBackend']) && !\Yii::$app->user->isGuest) ? $this->params['angularCmsBackend'] : false;
$hideBtnModifyCmsPage = isset($this->params['hideBtnModifyCmsPage']) ? $this->params['hideBtnModifyCmsPage'] : false;
        
echo Yii::$app->get('layoutmanager')->renderMainLayout([
    'cmsDefaultMenu' => $cmsDefaultMenu,
    'cmsSecondaryMenu' => $cmsSecondaryMenu,
    'cmsFooterMenu' => $cmsFooterMenu,
    'cmsPluginMenu' => $cmsPluginMenu,
    'customBeavior' => $customBehavior,
    'currentAsset' => $currentAsset,
    'content' => $content,
    'searchAction' => $searchPage,
    'currentPageUrl' => $currentPageUrl,
    'angularCmsBackend' => $controllerCmsBackend,
    'hideBtnModifyCmsPage' => $hideBtnModifyCmsPage
]);
