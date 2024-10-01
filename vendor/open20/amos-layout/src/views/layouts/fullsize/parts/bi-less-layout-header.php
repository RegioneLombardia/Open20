<?php

use app\components\CmsHelper;
use open20\amos\core\helpers\Html;
use open20\amos\layout\Module;
use luya\admin\models\Lang;
use app\components\CmsMenu;
?>

<?php
$iconSubmenu    = '<span class="am am-chevron-right am-4"> </span>';

$myOpenMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['myOpenCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['myOpenCmsMenu'] : 'myopen';
$mainMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['mainCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['mainCmsMenu'] : 'default';
$mainEngMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'] : 'default-eng';
$secondaryMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu'] : 'secondary';

$cmsMyOpenMenuCustomClass = 'cms-menu-container-myopen';
$cmsDefaultMenuCustomClass = 'cms-menu-container-default';
$cmsDefaultEngMenuCustomClass = 'cms-menu-container-default cms-menu-container-default-eng';
$cmsSecondaryMenuCustomClass = 'cms-menu-container-secondary';
$cmsPluginMenuCustomClass = 'cms-menu-container-plugin';

$menu1                = new CmsMenu();
$cmsMyOpenMenu        = $menu1->luyaMenu(
    $myOpenMenu,
    $iconSubmenu,
    false,
    $currentAsset
);

$menu2                = new CmsMenu();
$cmsDefaultMenu        = $menu2->luyaMenu(
    $mainMenu,
    $iconSubmenu,
    false,
    $currentAsset
);


if (!\Yii::$app->params['layoutConfigurations']['hideCmsMenuPluginHeader']) {
    $cmsPluginMenu = open20\amos\core\module\AmosModule::getModulesFrontEndMenus();
}

if (isset(\Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu']) || \Yii::$app->params['layoutConfigurations']['showSecondaryMenuHeader']) {
    $menu3                = new CmsMenu();
    $cmsSecondaryMenu        = $menu3->luyaMenu(
        $secondaryMenu,
        $iconSubmenu,
        false
    );
}

/**
 * if eng tree default menu is different to ita tree default menu
 */
if (isset(\Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu'])) {

    $menu4                = new CmsMenu();
    $cmsEngDefaultMenu        = $menu4->luyaMenu(
        $mainEngMenu,
        $iconSubmenu,
        false
    );
}

/**
 * check lang for default menu and footer menu
 */
$language_code = Yii::$app->composition['langShortCode'];
$language = Lang::findOne(['short_code' => $language_code]);

if ($language_code == 'en') {
    $cmsDefaultMenu = Html::tag('ul', $cmsEngDefaultMenu, ['class' => 'navbar-nav' . ' ' . $cmsDefaultEngMenuCustomClass]);
} else {
    $cmsDefaultMenu = Html::tag('ul', $cmsDefaultMenu, ['class' => 'navbar-nav' . ' ' . $cmsDefaultMenuCustomClass]);
}

/**
 * myOpenMenu first default menu
 */

$cmsMyOpenMenu = Html::tag('ul', $cmsMyOpenMenu, ['class' => 'navbar-nav' . ' ' . $cmsMyOpenMenuCustomClass]);

$cmsDefaultMenu = $cmsMyOpenMenu . $cmsDefaultMenu;

$cmsSecondaryMenu = Html::tag('ul', $cmsSecondaryMenu, ['class' => 'navbar-nav' . ' ' . $cmsSecondaryMenuCustomClass]);

$cmsPluginMenu = Html::tag('ul', $cmsPluginMenu, ['class' => 'navbar-nav' . ' ' . $cmsPluginMenuCustomClass]);

/**
 * if hideTopHeaderForGuestUser is enable, auto add platform access in menu
 */
if (
    isset(\Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser']) &&
    \Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser']
) {

    $labelSigninOrSignup = Module::t('amoslayout', 'Accedi o Registrati');
    $titleSigninOrSignup = Module::t('amoslayout', 'Accedi o registrati alla piattaforma {platformName}', ['platformName' => \Yii::$app->name]);
    $socialAuthModule = Yii::$app->getModule('socialauth');
    if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
        $labelSigninOrSignup = Module::t('amoslayout', 'Accedi');
        $titleSigninOrSignup = Module::t('amoslayout', 'Accedi alla piattaforma {platformName}', ['platformName' => \Yii::$app->name]);
    }
    $labelLogout = Module::t('amoslayout', 'Esci');
    $titleLogout = Module::t('amoslayout', 'Esci dalla piattaforma {platformName}', ['platformName' => \Yii::$app->name]);

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

?>
<?php
$currentAsset = isset($currentAsset) ? $currentAsset : open20\amos\layout\assets\BiLessAsset::register($this);
?>
<?php
if (isset(\Yii::$app->view->params['hideHamburgerMenuHeader'])) {
    $hideHamburgerMenuHeaderCheck = (\Yii::$app->view->params['hideHamburgerMenuHeader']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['hideHamburgerMenuHeader'])) {
        $hideHamburgerMenuHeaderCheck = (\Yii::$app->params['layoutConfigurations']['hideHamburgerMenuHeader']);
    } else {
        $hideHamburgerMenuHeaderCheck = false;
    }
}

if (isset(\Yii::$app->view->params['hideGlobalSearchHeader'])) {
    $hideGlobalSearchHeaderCheck = (\Yii::$app->view->params['hideGlobalSearchHeader']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeader'])) {
        $hideGlobalSearchHeaderCheck = (\Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeader']);
    } else {
        $hideGlobalSearchHeaderCheck = false;
    }
}

if (isset(\Yii::$app->view->params['enableGlobalSearchForm'])) {
    $enableGlobalSearchFormCheck = (\Yii::$app->view->params['enableGlobalSearchForm']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['enableGlobalSearchForm'])) {
        $enableGlobalSearchFormCheck = (\Yii::$app->params['layoutConfigurations']['enableGlobalSearchForm']);
    } else {
        $enableGlobalSearchFormCheck = false;
    }
}

if (isset(\Yii::$app->view->params['hideUserMenuHeader'])) {
    $hideUserMenuHeaderCheck = (\Yii::$app->view->params['hideUserMenuHeader']);
} else {
    $hideUserMenuHeaderCheck = (\Yii::$app->params['layoutConfigurations']['hideUserMenuHeader']);
    if (isset(\Yii::$app->params['layoutConfigurations']['hideUserMenuHeader'])) {
        $check = 0;
        if (\Yii::$app->user->can('ADMIN')) {
            $check = 1;
        }
        if (!$check && isset(\Yii::$app->params['layoutConfigurations']['hideUserMenuHeaderPermissions'])) {
            $permissions = \Yii::$app->params['layoutConfigurations']['hideUserMenuHeaderPermissions'];
            foreach ($permissions as $pr) {
                if (\Yii::$app->user->can($pr)) {
                    $check = 1;
                }
            }
        }
        if ($check == 1) {
            $hideUserMenuHeaderCheck = false;
        }
    }
}

if (isset(\Yii::$app->view->params['fluidContainerHeader'])) {
    $fluidContainerHeaderCheck = (\Yii::$app->view->params['fluidContainerHeader']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['fluidContainerHeader'])) {
        $fluidContainerHeaderCheck = (\Yii::$app->params['layoutConfigurations']['fluidContainerHeader']);
    } else {
        $fluidContainerHeaderCheck = false;
    }
}

if (isset(\Yii::$app->view->params['hideCookieBar'])) {
    $hideCookieBarCheck = (\Yii::$app->view->params['hideCookieBar']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['hideCookieBar'])) {
        $hideCookieBarCheck = (\Yii::$app->params['layoutConfigurations']['hideCookieBar']);
    } else {
        $hideCookieBarCheck = false;
    }
}

if (isset(\Yii::$app->view->params['hideAssistance'])) {
    $hideAssistanceCheck = (\Yii::$app->view->params['hideAssistance']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['hideAssistance'])) {
        $hideAssistanceCheck = (\Yii::$app->params['layoutConfigurations']['hideAssistance']);
    } else {
        $hideAssistanceCheck = false;
    }
}

if (isset(\Yii::$app->view->params['customPlatformGuide'])) {
    $customPlatformGuideCheck = (\Yii::$app->view->params['customPlatformGuide']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['customPlatformGuide'])) {
        $customPlatformGuideCheck = (\Yii::$app->params['layoutConfigurations']['customPlatformGuide']);
    } else {
        $customPlatformGuideCheck = false;
    }
}

if (isset(\Yii::$app->view->params['showSocialHeader'])) {
    $showSocialHeaderCheck = (\Yii::$app->view->params['showSocialHeader']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['showSocialHeader'])) {
        $showSocialHeaderCheck = (\Yii::$app->params['layoutConfigurations']['showSocialHeader']);
    } else {
        $showSocialHeaderCheck = false;
    }
}

if (isset(\Yii::$app->view->params['alwaysHamburgerMenuRight'])) {
    $alwaysHamburgerMenuRightCheck = (\Yii::$app->view->params['alwaysHamburgerMenuRight']);
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['alwaysHamburgerMenuRight'])) {
        $alwaysHamburgerMenuRightCheck = (\Yii::$app->params['layoutConfigurations']['alwaysHamburgerMenuRight']);
    } else {
        $alwaysHamburgerMenuRightCheck = false;
    }
}

if (isset(\Yii::$app->view->params['hideTopHeaderForGuestUser']) && is_bool(\Yii::$app->view->params['hideTopHeaderForGuestUser'])) {
    $hideTopHeaderForGuestUserCheck = \Yii::$app->view->params['hideTopHeaderForGuestUser'];
} else {
    if (isset(\Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser']) && is_bool(\Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser'])) {
        $hideTopHeaderForGuestUserCheck = \Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser'];
    } else {
        $hideTopHeaderForGuestUserCheck = false;
    }
}
$hideTopHeaderForGuestUser = (($hideTopHeaderForGuestUserCheck == true) && \Yii::$app->user->isGuest);
?>
<?= $this->render("bi-less-header", [
    'iconSubmenu' => $iconSubmenu,
    'currentAsset' => $currentAsset,
    'cmsDefaultMenu' => $cmsDefaultMenu,
    'cmsSecondaryMenu' => $cmsSecondaryMenu,
    'privacyPolicyLink' => \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'],
    'cookiePolicyLink' => \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon'],
    'hideHamburgerMenu' => $hideHamburgerMenuCheck,
    'alwaysHamburgerMenu' => \Yii::$app->params['layoutConfigurations']['showAlwaysHamburgerMenuHeader'],
    'alwaysHamburgerMenuRight' => $alwaysHamburgerMenuRightCheck,
    'hideLangSwitchMenu' => \Yii::$app->params['layoutConfigurations']['hideLangSwitchMenuHeader'],
    'hideGlobalSearch' => $hideGlobalSearchHeaderCheck,
    'enableGlobalSearchForm' => $enableGlobalSearchFormCheck,
    'hideUserMenu' => $hideUserMenuHeaderCheck,
    'fluidContainerHeader' => $fluidContainerHeaderCheck,
    'customUserMenu' => \Yii::$app->params['layoutConfigurations']['customUserMenuHeader'],
    'customUserNotLogged' => \Yii::$app->params['layoutConfigurations']['customUserNotLoggedHeader'],
    'customUserMenuLoginLink' => \Yii::$app->params['linkConfigurations']['loginLinkCommon'],
    'customUserMenuLogoutLink' => \Yii::$app->params['linkConfigurations']['logoutLinkCommon'],
    'customUserProfileLink' => \Yii::$app->params['linkConfigurations']['userProfileLinkCommon'],
    'customPlatformMenu' => \Yii::$app->params['menuCmsConfigurations']['customPlatformMenu'],
    'showSocial' => $showSocialHeaderCheck,
    'showSecondaryMenu' => \Yii::$app->params['layoutConfigurations']['showSecondaryMenuHeader'],
    'disableThemeLight' => \Yii::$app->params['layoutConfigurations']['disableThemeLightHeader'],
    'disableSmallHeader' => \Yii::$app->params['layoutConfigurations']['disableSmallHeader'],
    'enableHeaderSticky' => \Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader'],
    'pageSearchLink' => \Yii::$app->params['linkConfigurations']['pageSearchLinkCommon'],
    'hideAssistance' => $hideAssistanceCheck,
    'customPlatformGuide' => $customPlatformGuideCheck,
    'hideTopHeaderForGuestUser' => $hideTopHeaderForGuestUser,
]);
?>