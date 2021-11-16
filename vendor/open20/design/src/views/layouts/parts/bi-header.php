<?php
/* components */

use open20\amos\core\module\BaseAmosModule;
use open20\design\utility\CmsLanguageUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\CurrentUser;
/* modules */
use open20\amos\dashboard\AmosDashboard;
use open20\amos\chat\AmosChat;
use open20\amos\myactivities\AmosMyActivities;
use open20\amos\admin\AmosAdmin;
use open20\design\Module;
use yii\helpers\Url;

?>

<?php
/** @var bool|false $disablePlatformLinks - if true all the links to dashboard, settings, etc are disabled */
$disablePlatformLinks = isset(\Yii::$app->params['disablePlatformLinks']) ? \Yii::$app->params['disablePlatformLinks'] : false;

/* settings menu */
/** @var bool|false $disableSettings - if true hide the settings link in the navbar */
$canDisablePlatform   = false;
// if the params hideSettings == true or the user as has at least one of the provided roles, hide the link settings
if (isset(\Yii::$app->params['hideSettings']['roles']) && is_array(\Yii::$app->params['hideSettings']['roles'])) {
    $can = false;
    foreach (\Yii::$app->params['hideSettings']['roles'] as $role) {
        $can = $can || \Yii::$app->user->can($role);
    }
    $canDisablePlatform = $can;
}

$disableSettings = (isset(\Yii::$app->params['hideSettings']) && !is_array(\Yii::$app->params['hideSettings']) && \Yii::$app->params['hideSettings'])
    || $canDisablePlatform;

if (!$disableSettings) {
    /* ordinamenti dashboard */
    $ordinamentiDashboard     = (\Yii::$app->controller instanceof \open20\amos\dashboard\controllers\base\DashboardController)
        ?: false;
    $menuOrdinamentiDashboard = Html::tag(
        'li',
        Html::a(
            Html::tag('span', Module::t('amosdesign', 'Ordinamenti dashboard')),
            'javascript:void(0);',
            [
                'id' => 'dashboard-edit-button',
                'class' => 'list-item',
                'title' => Module::t('amosdesign', 'Impostazioni ordinamenti dashboard')
            ]
        )
    );

    /* gestisci widget */
    $gestisciWidget     = ($this->context->module->id == AmosDashboard::getModuleName() && Yii::$app->user->can('CAN_MANAGE_DASHBOARD'))
        ?: false;
    $menuGestisciWidget = Html::tag(
        'li',
        Html::a(
            Html::tag('span', Module::t('amosdesign', 'Gestisci widget')),
            '/dashboard/manager?module=' . AmosDashboard::getModuleName() . '&slide=1',
            [
                'class' => 'list-item',
                'title' => Module::t('amosdesign', 'Impostazioni gestione widget')
            ]
        )
    );
}

/* languages */
$actualLang = CmsLanguageUtility::getAppLanguage();
$languages = CmsLanguageUtility::getTranslationMenu();
$uniqueLang = (count($languages) <= 1);
if (!empty($languages) && ($languages != '')) {
    foreach ($languages as $lang) {
        $menuLanguages .= Html::tag('li', $lang);
    }
}



/* user menu */
if (!$hideUserMenu && !Yii::$app->user->isGuest) {
    $userModule      = CurrentUser::getUserProfile();
    /* info generiche */
    $userImage       = str_replace("/it", "", $userModule->getAvatarUrl('table_small'));
    $userNomeCognome = $userModule->getNomeCognome();
    $userAltImg = strtoupper(substr($userModule->nome, 0, 1) . substr($userModule->cognome, 0, 1));

    if ($userModule->sesso == 'Maschio') {
        $userWelcomeMessage = BaseAmosModule::t('amoscore', 'Benvenuto') . '<br> ' . $userNomeCognome;
    } else if ($userModule->sesso == 'Femmina') {
        $userWelcomeMessage = BaseAmosModule::t('amoscore', 'Benvenuta') . '<br> ' . $userNomeCognome;
    } else {
        $userWelcomeMessage = BaseAmosModule::t('amoscore', 'Benvenuto/a') . '<br> ' . $userNomeCognome;
    }

    $menuUser = Html::tag(
        'li',
        Html::tag(
            'p',
            $userWelcomeMessage,
            [
                'class' => 'font-weight-bold'
            ]
        )
    );

    /* il mio profilo */
    $menuUser .= Html::tag(
        'li',
        Html::a(
            Html::tag('span', Module::t('amosdesign', 'Il mio profilo')),
            [(isset($customUserProfileLink) ? $customUserProfileLink : '/' . AmosAdmin::getModuleName() . '/user-profile/update'), 'id' => $userModule->id],
            [
                'class' => 'list-item p-0',
                'title' => Module::t('amosdesign', 'Il mio profilo')
            ]
        ),
        [
            'class' => 'myprofile-menu'
        ]
    );

    /* logout */
    if (!empty($customUserMenuLogoutLink)) {
        $btnLogoutUrl = [$customUserMenuLogoutLink];
        $redirectLogoutParam = 'redir';
    } elseif (Yii::$app->isCmsApplication()) {
        $btnLogoutUrl = ['/site/logout'];
        $redirectLogoutParam = 'redir';
    } else {
        $btnLogoutUrl = ['/' . AmosAdmin::getModuleName() . '/security/logout'];
        $redirectLogoutParam = 'backTo';
    }
    /** @var \open20\amos\socialauth\Module $socialAuthModule */
    $socialAuthModule = Yii::$app->getModule('socialauth');
    if (YII_ENV_PROD && !is_null($socialAuthModule) && ($socialAuthModule->enableSpid === true)) {
        $btnLogoutUrl[$redirectLogoutParam] = Url::to([
            '/Shibboleth.sso/Logout',
            'return' => 'https://idpcwrapper.crs.lombardia.it/PublisherMetadata/Logout?dest=' . urlencode(Url::to('/', true))
        ], true);
    }
    $menuUser .= Html::tag(
        'li',
        Html::a(
            Html::tag('span', Module::t('amosdesign', 'Esci')) .
                '<svg class="icon icon-primary right"><use xlink:href="' . $currentAsset->baseUrl . '/sprite/material-sprite.svg#exit-to-app"></use></svg>',
            $btnLogoutUrl,
            [
                'class' => 'list-item p-0',
                'title' => Module::t('amosdesign', 'Esci')
            ]
        ),
        [
            'class' => 'exit-menu'
        ]
    );

    if ((isset($privacyPolicyLink)) || (isset($cookiePolicyLink))) {
        $menuUser .= Html::tag('li', Html::tag('span', '', ['class' => 'divider']));
    }
    /* privacy policy */
    if (isset($privacyPolicyLink)) {
        $menuUser .= Html::tag(
            'li',
            Html::a(
                Html::tag('span', Module::t('amosdesign', 'Privacy Policy')),
                $privacyPolicyLink,
                [
                    'class' => 'list-item p-0',
                    'title' => Module::t('amosdesign', 'Informativa sulla privacy')
                ]
            ),
            [
                'class' => 'privacy-menu'
            ]
        );
    }
    /* cookie policy */
    if (isset($cookiePolicyLink)) {
        $menuUser .= Html::tag(
            'li',
            Html::a(
                Html::tag('span', Module::t('amosdesign', 'Cookie Policy')),
                $cookiePolicyLink,
                [
                    'class' => 'list-item p-0',
                    'title' => Module::t('amosdesign', 'Informativa sui cookies')
                ]
            ),
            [
                'class' => 'cookie-menu'
            ]
        );
    }

    /* deimpersonate */
    if (Yii::$app->session->has('IMPERSONATOR')) {
        $menuUser .= Html::tag('li', Html::tag('span', '', ['class' => 'divider']));
        $menuUser .= Html::tag(
            'li',
            Html::a(
                Html::tag('span', Module::t('amosdesign', 'De-impersonate'), ['class' => 'text-danger font-weight-bold']),
                '/' . AmosAdmin::getModuleName() . '/security/deimpersonate',
                [
                    'class' => 'list-item p-0',
                    'title' => Module::t('amosdesign', 'De-impersonate')
                ]
            ),
            [
                'class' => 'deimpersonate-menu'
            ]
        );
    }
}
?>

<?php if (!($hideGlobalSearch)) : ?>
    <?php
    //MODALE DI RICERCA NON UTILIZZATA - TODO DELETE
    $this->render(
        "bi-header-search",
        [
            'currentAsset' => $currentAsset,
        ]
    );
    ?>
<?php endif ?>

<?php if (!($hideHamburgerMenu) && $alwaysHamburgerMenu) : ?>
    <!-- MODALE PER HAMBURGER MENU -->
    <div class="modal-always-hamburger-menu modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="alwaysHamburgerMenu">
        <div class="modal-dialog modal-dialog-left" role="document">
            <div class="modal-content">
                <div class="modal-header px-4">
                    <div class="it-brand-wrapper d-flex align-items-center">
                        <?= $this->render("bi-logo"); ?>
                    </div>

                    <button class="close ml-0 ml-auto" type="button" data-dismiss="modal" aria-label="Close">
                        <svg class="icon">
                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#close-circle-outline"></use>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $cmsDefaultMenu ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<div id="headerContent" class="it-header-wrapper <?= ($enableHeaderSticky) ? 'it-header-sticky' : 'position-fixed' ?> w-100 z-index-8 shadow-sm">
    <div class="it-header-slim-wrapper d-flex align-items-center py-0 bg-primary">
        <div class="<?= ($fluidContainerHeader) ? 'container-fluid' : 'container' ?>">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-slim-wrapper-content">
                        <div class="navbar-brand">
                            <?= $this->render("bi-logo-navbar"); ?>
                        </div>
                        <div class="it-header-slim-right-zone">
                            <?php if (!$disableSettings && !$disablePlatformLinks && ($ordinamentiDashboard || $gestisciWidget)) : ?>
                                <div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" data-toggle-second="tooltip" data-placement="left" aria-expanded="false" title="<?= Module::t('amosdesign', 'Impostazioni') ?>">
                                        <svg class="icon">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_settings"></use>
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="link-list-wrapper">
                                                    <ul class="link-list">
                                                        <?php
                                                        if ($ordinamentiDashboard) {
                                                            echo $menuOrdinamentiDashboard;
                                                        }
                                                        if ($gestisciWidget) {
                                                            echo $menuGestisciWidget;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- CHAT MODULE -->
                            <?php if (!Yii::$app->user->isGuest) : ?>
                                <?php if (\Yii::$app->getModule('chat')) : ?>
                                    <?php
                                    $chatModuleWidget          = new \open20\amos\chat\widgets\icons\WidgetIconChat();
                                    $chatModuleBulletCount     = $chatModuleWidget->getBulletCount();
                                    $menuChatModuleBulletCount = ($chatModuleBulletCount > 0) ? Html::tag(
                                        'span',
                                        $chatModuleBulletCount,
                                        ['class' => 'badge badge-pill badge-danger']
                                    ) : '';
                                    ?>
                                    <div class="nav-item">
                                        <a class="nav-link" href="/site/to-menu-url?url=/messages" data-toggle="tooltip" data-placement="bottom" title="<?= AmosChat::t('amoschat', 'Messaggi privati') ?>">
                                            <svg class="icon">
                                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#forum"></use>
                                            </svg>
                                            <?= $menuChatModuleBulletCount ?>
                                            <span class="sr-only"><?= AmosChat::t('amoschat', 'Messaggi privati') ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>


                                <!-- MY ACTIVITIES MODULE -->
                                <?php if (\Yii::$app->getModule('myactivities')) : ?>
                                    <?php
                                    $myactivitiesModuleWidget          = new \open20\amos\myactivities\widgets\icons\WidgetIconMyActivities();
                                    $myactivitiesModuleBulletCount     = $myactivitiesModuleWidget->getBulletCount();
                                    $menuMyActivitiesModuleBulletCount = ($myactivitiesModuleBulletCount > 0) ? Html::tag(
                                        'span',
                                        $myactivitiesModuleBulletCount,
                                        ['class' => 'badge badge-pill badge-danger']
                                    ) : '';
                                    ?>
                                    <div class="nav-item">
                                        <a class="nav-link" href="/site/to-menu-url?url=/myactivities/my-activities/index" data-toggle="tooltip" data-placement="bottom" title="<?= AmosMyActivities::t('amosmyactivities', 'My activities') ?>">
                                            <svg class="icon">
                                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#bell"></use>
                                            </svg>
                                            <?= $menuMyActivitiesModuleBulletCount ?>
                                            <span class="sr-only"><?= AmosChat::t('amoschat', 'Notifiche sulle mie attività') ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- USER -->
                            <?php if (!$hideUserMenu) : ?>

                                <?php if (Yii::$app->user->isGuest) : ?>
                                    <?php if ($customUserMenuLoginLink) {
                                        $loginUrl = $customUserMenuLoginLink;
                                    } else {
                                        $loginUrl = \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login';
                                    }
                                    ?>
                                    <div class="it-access-top-wrapper">
                                        <?php if ($customUserNotLogged) : ?>
                                            <?php
                                            echo $this->render($customUserNotLogged, [
                                                'currentAsset' => $currentAsset,
                                            ]);
                                            ?>
                                        <?php else : ?>
                                            <a href="<?= $loginUrl ?>" class="btn btn-icon btn-full  bg-primary mr-0" title="Accedi o Registrati al portale">
                                                <span class="rounded-icon border border-white bg-transparent p-1">
                                                    <svg class="icon icon-white">
                                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#key-variant"></use>
                                                    </svg>
                                                </span>
                                                <span class="d-none d-sm-block"><?= Module::t('amosdesign', 'Accedi o Registrati') ?></span>
                                            </a>
                                        <?php endif ?>
                                    </div>
                                <?php else : ?>
                                    <div class="dropdown menu-profile">
                                        <a id="dropdownMenuProfile" href="#" class="btn btn-primary btn-icon btn-full dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?= Module::t('amosdesign', 'Apri menu utente') ?>">
                                            <span class="rounded-icon">
                                                <img class="icon icon-primary rounded-circle" src="<?= $userImage ?>" alt="<?= $userAltImg ?>">
                                            </span>
                                            <span class="d-none d-lg-block text-capitalize"><?= $userNomeCognome ?></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuProfile">
                                            <?php if ($customUserMenu) : ?>
                                                <?php
                                                echo $this->render($customUserMenu, [
                                                    'currentAsset' => $currentAsset,
                                                ]);
                                                ?>
                                            <?php else : ?>
                                                <div class="link-list-wrapper">
                                                    <ul class="link-list">
                                                        <?= $menuUser ?>
                                                    </ul>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif ?>

                            <!-- LANGUAGES -->
                            <?php if (!$hideLangSwitchMenu) : ?>
                                <div class="nav-item dropdown menu-translation border-left border-white pl-2">
                                    <a class="nav-link dropdown-toggle pr-0" href="javascript::void(0)" title="Lingua corrente <?= $actualLang ?>" id="dropdownMenuTranslation" data-toggle="dropdown" aria-expanded="false">
                                        <?= $actualLang ?>
                                        <svg class="icon icon-sm">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuTranslation">
                                        <div class="link-list-wrapper">
                                            <ul class="link-list">
                                                <?php if ($uniqueLang) : ?>
                                                    <li>
                                                        <p class="p-2 mb-0"><?= Module::t('amosdesign', 'Non sono disponibili altre lingue') ?></p>
                                                    </li>
                                                <?php else : ?>
                                                    <?= $menuLanguages ?>
                                                <?php endif ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="it-nav-wrapper">
        <div class="it-header-center-wrapper <?= ($disableThemeLight) ? '' : 'theme-light' ?> <?= ($disableSmallHeader) ? '' : 'it-small-header' ?>">
            <div class="<?= ($fluidContainerHeader) ? 'container-fluid' : 'container' ?>">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-center-content-wrapper">
                            <div class="it-brand-wrapper d-flex <?= ($hideHamburgerMenu) ? 'pl-0' : (($fluidContainerHeader) ? 'pl-lg-0' : 'pl-lg-0') ?>">
                                <?= $this->render("bi-logo"); ?>
                            </div>
                            <div class="it-right-zone">
                                <?php if ($showSocial) : ?>
                                    <?= $this->render("bi-header-social", [
                                        'currentAsset' => $currentAsset,
                                    ]); ?>
                                <?php endif ?>
                                <?php if (!($hideGlobalSearch)) : ?>
                                    <?php if (isset($pageSearchLink)): ?>
                                        <div class="it-search-wrapper">
                                            <span class="d-none d-md-block"><?= Module::t('amosdesign', 'Cerca') ?></span>
                                            <a class="search-link rounded-icon" aria-label="<?= Module::t('amosdesign', 'Cerca') ?>" title="<?= Module::t('amosdesign', 'Vai alla pagina di ricerca della piattaforma')  . ' ' . \Yii::$app->name ?>" href="<?= $pageSearchLink ?>">
                                                <svg class="icon">
                                                    <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-search"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    <?php endif ?>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!($hideHamburgerMenu)) : ?>
            <?php if (!$alwaysHamburgerMenu) : ?>
                <div class="it-header-navbar-wrapper <?= ($disableThemeLight) ? '' : 'theme-light-desk' ?> shadow-none">
                    <div class="<?= ($fluidContainerHeader) ? 'container-fluid' : 'container' ?>">
                        <div class="row">
                            <div class="col-12">
                                <!--start nav-->
                                <nav class="navbar navbar-expand-lg has-megamenu">
                                    <button class="custom-navbar-toggler" type="button" aria-controls="hamburgerMenu" aria-expanded="false" aria-label="Toggle navigation" data-target="#hamburgerMenu">
                                        <svg class="icon <?= ($disableThemeLight) ? 'icon-white' : '' ?>">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#menu"></use>
                                        </svg>
                                    </button>
                                    <div class="navbar-collapsable" id="hamburgerMenu" style="display: none;">
                                        <div class="overlay" style="display: none;"></div>
                                        <div class="menu-wrapper z-index-1">
                                            <div class="header-logo-hamburger close-div">
                                                <div class="it-brand-wrapper d-flex flex-column flex-sm-row">
                                                    <?= $this->render("bi-logo"); ?>
                                                </div>
                                                <button class="btn close-menu" type="button">
                                                    <span class="it-close sr-only">close</span>
                                                    <svg class="icon">
                                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#close"></use>
                                                    </svg>
                                                </button>
                                            </div>
                                            <?= $cmsDefaultMenu ?> 
                                            <?php
                                            if($customPlatformPluginMenu):
                                                echo $this->render($customPlatformPluginMenu, [
                                                    'currentAsset' => $currentAsset,
                                                ]);
                                                endif;
                                            ?>
                                       
                                            <?php if ($showSecondaryMenu) : ?>
                                                <?= $cmsSecondaryMenu ?>
                                            <?php endif ?>

                                        </div>

                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <button class="btn btn-icon btn-always-hamburger-menu" type="button" data-toggle="modal" data-target="#alwaysHamburgerMenu" aria-label="Menu Principale">
                    <svg class="icon <?= ($disableThemeLight) ? 'icon-white' : '' ?>">
                        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#menu"></use>
                    </svg>
                </button>
            <?php endif ?>
        <?php endif; ?>
    </div>
</div>
