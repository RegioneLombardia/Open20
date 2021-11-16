<?php
/* components */

use open20\design\utility\CmsLanguageUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\CurrentUser;
/* modules */
use open20\amos\dashboard\AmosDashboard;
use open20\amos\chat\AmosChat;
use open20\amos\myactivities\AmosMyActivities;
use open20\amos\admin\AmosAdmin;
use open20\amos\layout\Module;
use yii\helpers\Url;

?>

<?php
/** @var bool|false $disablePlatformLinks - if true all the links to dashboard, settings, etc are disabled */
$disablePlatformLinks = isset(\Yii::$app->params['disablePlatformLinks']) ? \Yii::$app->params['disablePlatformLinks'] : false;

/* settings menu */
/** @var bool|false $disableSettings - if true hide the settings link in the navbar */
$canDisablePlatform = false;
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
            Html::tag('span', Yii::t('amoscore', 'Ordinamenti dashboard')),
            'javascript:void(0);',
            [
                'id' => 'dashboard-edit-button',
                'class' => 'list-item',
                'title' => Yii::t('amoscore', 'Impostazioni ordinamenti dashboard')
            ]
        )
    );

    /* gestisci widget */
    $gestisciWidget     = ($this->context->module->id == AmosDashboard::getModuleName() && Yii::$app->user->can('CAN_MANAGE_DASHBOARD'))
        ?: false;
    $menuGestisciWidget = Html::tag(
        'li',
        Html::a(
            Html::tag('span', Yii::t('amoscore', 'Gestisci widget')),
            '/dashboard/manager?module=' . AmosDashboard::getModuleName() . '&slide=1',
            [
                'class' => 'list-item',
                'title' => Yii::t('amoscore', 'Impostazioni gestione widget')
            ]
        )
    );
}

/* languages */
$actualLang = CmsLanguageUtility::getAppLanguage();
$languages  = CmsLanguageUtility::getTranslationMenu();
$uniqueLang = true;
if (!empty($languages) && ($languages != '')) {
    foreach ($languages as $lang) {
        $uniqueLang    = false;
        $menuLanguages .= Html::tag('li', $lang);
    }
}



/* user menu */
if (!$hideUserMenu && !CurrentUser::isPlatformGuest()) {
    $userModule      = CurrentUser::getUserProfile();
    /* info generiche */
    $userImage       = str_replace(".it/it/", ".it/", $userModule->getAvatarUrl('table_small'));
    $userNomeCognome = $userModule->getNomeCognome();
    $userAltImg      = strtoupper(substr($userModule->nome, 0, 1) . substr($userModule->cognome, 0, 1));

    if ($userModule->sesso == 'Maschio') {
        $userWelcomeMessage = Yii::t('amoscore', 'Benvenuto<br>') . ' ' . $userNomeCognome;
    } else if ($userModule->sesso == 'Femmina') {
        $userWelcomeMessage = Yii::t('amoscore', 'Benvenuta<br>') . ' ' . $userNomeCognome;
    } else {
        $userWelcomeMessage = Yii::t('amoscore', 'Benvenuto/a<br>') . ' ' . $userNomeCognome;
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
            Html::tag('span', Yii::t('amoscore', 'Il mio profilo')),
            ['/' . AmosAdmin::getModuleName() . '/user-profile/update', 'id' => $userModule->id],
            [
                'class' => 'list-item p-0',
                'title' => Yii::t('amoscore', 'Il mio profilo')
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
            Html::tag('span', Yii::t('amoscore', 'Esci')),
            $btnLogoutUrl,
            [
                'class' => 'list-item p-0',
                'title' => Yii::t('amoscore', 'Esci')
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
                Html::tag('span', Yii::t('amoscore', 'Privacy Policy')),
                $privacyPolicyLink,
                [
                    'class' => 'list-item p-0',
                    'title' => Yii::t('amoscore', 'Informativa sulla privacy')
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
                Html::tag('span', Yii::t('amoscore', 'Cookie Policy')),
                $cookiePolicyLink,
                [
                    'class' => 'list-item p-0',
                    'title' => Yii::t('amoscore', 'Informativa sui cookies')
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
                Html::tag('span', Yii::t('amoscore', 'De-impersonate'), ['class' => 'text-danger font-weight-bold']),
                '/' . AmosAdmin::getModuleName() . '/security/deimpersonate',
                [
                    'class' => 'list-item p-0',
                    'title' => Yii::t('amoscore', 'De-impersonate')
                ]
            ),
            [
                'class' => 'deimpersonate-menu'
            ]
        );
    }
}
?>





<?php if (!($hideHamburgerMenu)) : ?>
    <!-- MODALE PER HAMBURGER MENU -->
    <div class="modal-always-hamburger-menu modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="alwaysHamburgerMenu">
        <div class="modal-dialog modal-dialog-left" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="it-brand-wrapper flexbox">
                        <?= $this->render("bi-less-logo"); ?>
                    </div>
                    <button class="close ml-0 mr-auto" type="button" data-dismiss="modal" aria-label="Close">
                        <span class="text-muted am am-close-circle-o"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $cmsDefaultMenu ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>


<div id="headerContent" class="it-header-wrapper <?= ($enableHeaderSticky) ? 'it-header-sticky' : 'navbar-fixed-top' ?>">
    <div class="it-header-slim-wrapper flexbox align-items-center py-0">
        <div class="<?= ($fluidContainerHeader) ? 'container-fluid' : 'container' ?>">
            <!--fino qui ok-->
            <div class="row flexbox">
                <div class="col-12">
                    <div class="it-header-slim-wrapper-content flexbox">
                        <div class="navbar-brand small">
                            <?= $this->render("bi-less-logo-navbar"); ?>
                        </div>
                        <div class="it-header-slim-right-zone flexbox">
                            <?php
                            if (!$disableSettings && !$disablePlatformLinks && ($ordinamentiDashboard || $gestisciWidget)) :
                            ?>
                                <div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" data-toggle-second="tooltip" data-placement="left" aria-expanded="false" title="<?=
                                                                                                                                                                                        Yii::t('amoscore', 'Impostazioni')
                                                                                                                                                                                        ?>">
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
                            <?php if (!CurrentUser::isPlatformGuest()) : ?>
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
                                        <a class="nav-link" href="/site/to-menu-url?url=/messages" data-toggle="tooltip" data-placement="bottom" title="<?=
                                                                                                                                                        AmosChat::t('amoschat', 'Messaggi privati')
                                                                                                                                                        ?>">
                                            <span class="am am-comments"></span>
                                            <?= $menuChatModuleBulletCount ?>
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
                                        <a class="nav-link pl-5" href="/site/to-menu-url?url=/myactivities/my-activities/index" data-toggle="tooltip" data-placement="bottom" title="<?=
                                                                                                                                                                                        AmosMyActivities::t('amosmyactivities', 'My activities')
                                                                                                                                                                                        ?>">
                                            <span class="dash dash-bell"></span>
                                            <?= $menuMyActivitiesModuleBulletCount ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- TO FRONTEND LINK -->
                            <?php if (isset(\Yii::$app->params['toFrontendLink']) && \Yii::$app->params['toFrontendLink']) : ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="<?= \Yii::$app->params['platform']['frontendUrl'] ?>" title="<?=
                                                                                                                            Yii::t(
                                                                                                                                'amoscore',
                                                                                                                                '#frontend'
                                                                                                                            )
                                                                                                                            ?>" <?=
                                                                                                                                (isset(\Yii::$app->params['toFrontendLinkNoBlank']) && \Yii::$app->params['toFrontendLinkNoBlank'])
                                                                                                                                    ? 'target="_blank"' : ''
                                                                                                                                ?>>
                                        <svg class="icon">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_exit_to_app"></use>
                                        </svg>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <!-- TO DASHBOARD LINK -->
                            <?php if (isset(\Yii::$app->params['toDashboardLink']) && \Yii::$app->params['toDashboardLink']) :
                            ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="/dashboard" title="<?=
                                                                                    Yii::t(
                                                                                        'amoscore',
                                                                                        '#to_dashboard_link_text'
                                                                                    )
                                                                                    ?>">
                                        <svg class="icon">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_home"></use>
                                        </svg>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- TICKETS MODULE -->
                            <?php
                            if ((isset(\Yii::$app->params['enableTickectNavbarHeader'])) && (\Yii::$app->params['enableTickectNavbarHeader']
                                    == true) && (\Yii::$app->getModule('tickets'))
                            ) :
                            ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="/ticket/assistenza/cerca-faq" title="<?=
                                                                                                    Module::t(
                                                                                                        'tickets',
                                                                                                        'Faq'
                                                                                                    )
                                                                                                    ?>">
                                        <svg class="icon">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_help_outline"></use>
                                        </svg>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- USER -->
                            <?php if (!$hideUserMenu) : ?>
                                <?php if (CurrentUser::isPlatformGuest()) : ?>
                                    <?php
                                    if ($customUserMenuLoginLink) {
                                        $loginUrl = $customUserMenuLoginLink;
                                    } else {
                                        $loginUrl = \Yii::$app->params['platform']['backendUrl'] . '/admin/security/login';
                                    }
                                    ?>
                                    <div class="it-access-top-wrapper">
                                        <?php if ($customUserNotLogged) : ?>
                                            <?php
                                            echo $this->render(
                                                $customUserNotLogged,
                                                [
                                                    'currentAsset' => $currentAsset,
                                                ]
                                            );
                                            ?>
                                        <?php else : ?>
                                            <a href="<?= $loginUrl ?>" class="btn btn-icon btn-full btn-primary mr-0 flexbox" title="Accedi o Registrati al portale">
                                                <span class="icon mdi mdi-key-variant light flexbox"> </span>
                                                <span class="d-none d-sm-block text-uppercase">Accedi o Registrati</span>
                                            </a>
                                        <?php endif ?>
                                    </div>
                                <?php else : ?>
                                    <div class="dropdown menu-profile">
                                        <a href="#" class="btn btn-primary btn-icon btn-full dropdown-toggle flexbox" role="button" id="dropdownMenuProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="rounded-icon">
                                                <img class="icon icon-primary rounded-circle" src="<?=
                                                                                                    (!empty($userImage) ? $userImage : \Yii::$app->params['platform']['frontendUrl'] . '/img/defaultProfiloM.png')
                                                                                                    ?>" alt="AS">
                                                <!--<span class="ic ic-user am-4"> </span>-->
                                            </span>
                                            <span class="d-none d-lg-block text-capitalize"><?= $userNomeCognome ?></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuProfile">
                                            <?php if ($customUserMenu) : ?>
                                                <?php
                                                echo $this->render(
                                                    $customUserMenu,
                                                    [
                                                        'currentAsset' => $currentAsset,
                                                    ]
                                                );
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
                                    <a class="nav-link dropdown-toggle" href="javascript::void(0)" title="Lingua corrente <?= $actualLang ?>" id="dropdownMenuTranslation" data-toggle="dropdown" aria-expanded="false">
                                        <?= $actualLang ?>
                                        <!--<svg class="icon-expand icon icon-sm">
                                            <use xlink:href="< ?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                        </svg>-->
                                        <span class="am am-chevron-down" style="color:white;"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuTranslation">
                                        <div class="link-list-wrapper">
                                            <ul class="link-list">
                                                <?php if ($uniqueLang) : ?>
                                                    <li>
                                                        <p class="p-2 mb-0"><?=
                                                                            Yii::t('amoscore', 'Non sono disponibili altre lingue')
                                                                            ?></p>
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
        <div class="it-header-center-wrapper flexbox <?= ($disableThemeLight) ? '' : 'theme-light' ?> <?=
                                                                                                        ($disableSmallHeader) ? '' : 'it-small-header'
                                                                                                        ?>">
            <div class="<?= ($fluidContainerHeader) ? 'container-fluid' : 'container' ?>">
                <div class="row flexbox">
                    <div class="col-12">
                        <div class="it-header-center-content-wrapper flexbox">
                            <div class="it-brand-wrapper flexbox <?= ($hideHamburgerMenu) ? 'pl-0' : 'pl-md-0' ?>">
                                <?= $this->render("bi-less-logo"); ?>
                            </div>
                            <div class="it-right-zone flexbox">
                                <?php if ($showSocial) : ?>
                                    <?php
                                    echo $this->render(
                                        "bi-less-header-social",
                                        [
                                            'currentAsset' => $currentAsset,
                                        ]
                                    );
                                    ?>

                                <?php endif ?>
                                <?php if (!($hideGlobalSearch)) : ?>
                                    <?php if (isset($pageSearchLink)) { ?>
                                        <?=
                                        $this->render("bi-less-header-search", [
                                            'currentAsset' => $currentAsset,
                                            'pageSearchLink' => $pageSearchLink
                                        ]);
                                        ?>
                                    <?php }; ?>
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
                                    <button class="custom-navbar-toggler" type="button" aria-controls="hamburgerMenu" aria-expanded="false" aria-label="Toggle navigation" data-toggle="collapse" data-target="#hamburgerMenu">
                                        <span class="am am-menu"> </span>
                                    </button>

                                    <!-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> -->
                                    <div class="navbar-collapsable" id="hamburgerMenu" style="display: none;">
                                        <div class="overlay" style="display: none;"></div>
                                        <div class="menu-wrapper z-index-1">

                                            <div class="header-logo-hamburger close-div">
                                                <div class="it-brand-wrapper flexbox <?= ($hideHamburgerMenu) ? 'pl-0' : 'pl-lg-0' ?>">
                                                    <?= $this->render("bi-less-logo"); ?>
                                                </div>

                                                <button class="btn close-menu" type="button" aria-controls="hamburgerMenu" aria-expanded="flase" aria-label="Toggle navigation" data-toggle="collapse" data-target="#hamburgerMenu">
                                                    <span class="text-muted am am-close-circle-o"> </span>
                                                </button>
                                            </div>

                                            <?= $cmsDefaultMenu ?>
                                            <?php if($customPlatformPluginMenu):
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
                    <span class="am am-menu am-1"> </span>
                </button>

            <?php endif ?>
        <?php endif; ?>

    </div>

</div>
<!-- ASSISTANCE -->
<?php if (!$hideAssistance) : ?>
    <?php
    $isMail = ((isset(Yii::$app->params['assistance']['type']) && Yii::$app->params['assistance']['type'] == 'email') || (!isset(Yii::$app->params['assistance']['type']) && isset(\Yii::$app->params['email-assistenza']))) ? true : false;
    $mailAddress = isset(Yii::$app->params['assistance']['email']) ? Yii::$app->params['assistance']['email'] : (isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : '');
    $urlAssistance = !empty(Yii::$app->params['assistance']['url']) ? \Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->params['assistance']['url']) : '#';
    ?>
    <?= $this->render("bi-less-assistance", [
        'currentAsset' => $currentAsset,
        'isMail' => $isMail,
        'mailAddress' => $mailAddress,
        'urlAssistance' => $urlAssistance
    ]); ?>

<?php endif ?>
<?= $this->render("bi-backtotop-button"); ?>