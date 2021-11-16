<?php
/* components */

use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\CurrentUser;
/* modules */

use open20\amos\dashboard\AmosDashboard;
use open20\amos\chat\AmosChat;
use open20\amos\myactivities\AmosMyActivities;

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
    $ordinamentiDashboard = (\Yii::$app->controller instanceof \open20\amos\dashboard\controllers\base\DashboardController)
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
    $gestisciWidget = ($this->context->module->id == AmosDashboard::getModuleName() && Yii::$app->user->can('CAN_MANAGE_DASHBOARD'))
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
$actualLang = \open20\amos\core\utilities\HeaderUtility::getAppLanguage();
$languages = \open20\amos\core\utilities\HeaderUtility::getTranslationMenu();
if (!empty($languages) && ($languages != '')) {
    foreach ($languages as $lang) {
        $menuLanguages .= Html::tag('li', $lang);
    }
}


/* deimpersonate */
$deimpersonate = (Yii::$app->session->has('IMPERSONATOR')) ?: false;
$menuDeimpersonate = Html::tag(
    'li',
    Html::a(
        Html::tag('span', Yii::t('amoscore', 'De-impersonate')),
        '/admin/security/deimpersonate',
        [
            'class' => 'list-item',
            'title' => Yii::t('amoscore', 'De-impersonate')
        ]
    )
);

/* user menu */
$disableMyprofile = (!empty(\Yii::$app->params['disableMenuUser']) && \Yii::$app->params['disableMenuUser'] == true);
if (!$disableMyprofile && CurrentUser::getUserIdentity()) {
    $userModule = CurrentUser::getUserProfile();
    /* info generiche */
    $userImage = $userModule->getAvatarUrl('original');
    $userNomeCognome = $userModule->getNomeCognome();
    if ($userModule->sesso == 'Maschio') {
        $userWelcomeMessage = Yii::t('amoscore', 'Benvenuto<br>') . ' ' . $userNomeCognome;
    } else if ($userModule->sesso == 'Femmina') {
        $userWelcomeMessage = Yii::t('amoscore', 'Benvenuta<br>') . ' ' . $userNomeCognome;
    } else {
        $userWelcomeMessage = false;
    }
    $menuUserWelcomeMessage = Html::tag('li', Html::tag('h3', $userWelcomeMessage));
    /* il mio profilo */
    if (!empty(\Yii::$app->params['disableMenuUser']) && \Yii::$app->params['disableMenuUser'] == true) {
        $menuMyProfile = false;
    } else {
        $menuMyProfile = Html::tag(
            'li',
            Html::a(
                Html::tag('span', Yii::t('amoscore', 'Il mio profilo')),
                ['/admin/user-profile/update', 'id' => $userModule->id],
                [
                    'class' => 'list-item',
                    'title' => Yii::t('amoscore', 'Il mio profilo')
                ]
            )
        );
    }
    /* logout */
    $btnLogoutUrl = ['/admin/security/logout'];
    if (YII_ENV_PROD && !is_null($socialAuthModule) && ($socialAuthModule->enableSpid === true)) {
        $btnLogoutUrl['backTo'] = \yii\helpers\Url::to([
            '/Shibboleth.sso/Logout',
            'return' => 'https://idpcwrapper.crs.lombardia.it/PublisherMetadata/Logout?dest=' . urlencode(\yii\helpers\Url::to('/', true))
        ], true);
    } else {
        $btnLogoutUrl = ['/admin/security/logout'];
    }
    /* privacy policy */
    $privacyPolicy = isset(\Yii::$app->params['privacyLink']);
    if ($privacyPolicy) {
        $menuPrivacy = Html::tag(
            'li',
            Html::a(
                Html::tag('span', Yii::t('amoscore', 'Informativa sulla privacy')),
                \Yii::$app->params['privacyLink'],
                [
                    'class' => 'list-item',
                    'title' => Yii::t('amoscore', 'Informativa sulla privacy')
                ]
            )
        );
    }
    /* cookie policy */
    $cookiePolicy = isset(\Yii::$app->params['cookiesLink']);
    if ($cookiePolicy) {
        $menuCookie = Html::tag(
            'li',
            Html::a(
                Html::tag('span', Yii::t('amoscore', 'Informativa sui cookies')),
                \Yii::$app->params['cookiesLink'],
                [
                    'class' => 'list-item',
                    'title' => Yii::t('amoscore', 'Informativa sui cookies')
                ]
            )
        );
    }
}
?>

<div class="it-header-wrapper position-fixed w-100 z-index-99 shadow-sm">
    <div class="it-header-slim-wrapper d-flex align-items-center py-0 bg-primary">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-slim-wrapper-content d-flex align-items-center">
                        <div class="navbar-brand">
                            <?= $this->render("bi-logo-navbar"); ?>
                        </div>
                        <div class="it-header-slim-right-zone ml-auto">
                            <?php if (!$disableSettings && !$disablePlatformLinks && ($ordinamentiDashboard || $gestisciWidget)) : ?>
                                <div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                                       data-toggle-second="tooltip" data-placement="left" aria-expanded="false"
                                       title="<?= Yii::t('amoscore', 'Impostazioni') ?>">
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
                            <?php if (\Yii::$app->getModule('chat')) : ?>
                                <?php
                                $chatModuleWidget = new \open20\amos\chat\widgets\icons\WidgetIconChat();
                                $chatModuleBulletCount = $chatModuleWidget->getBulletCount();
                                $menuChatModuleBulletCount = ($chatModuleBulletCount > 0) ? Html::tag(
                                    'span',
                                    $chatModuleBulletCount,
                                    ['class' => 'badge badge-pill badge-danger']
                                ) : '';
                                ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="/messages" data-toggle="tooltip" data-placement="bottom"
                                       title="<?= AmosChat::t(
                                           'amoschat',
                                           'Messaggi privati'
                                       )
                                       ?>">
                                        <svg class="icon">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_forum"></use>
                                        </svg>
                                        <?= $menuChatModuleBulletCount ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <!-- MY ACTIVITIES MODULE -->
                            <?php if (\Yii::$app->getModule('myactivities') && !\Yii::$app->user->isGuest) : ?>
                                <?php
                                $myactivitiesModuleWidget = new \open20\amos\myactivities\widgets\icons\WidgetIconMyActivities();
                                $myactivitiesModuleBulletCount = $myactivitiesModuleWidget->getBulletCount();
                                $menuMyActivitiesModuleBulletCount = ($myactivitiesModuleBulletCount > 0) ? Html::tag(
                                    'span',
                                    $myactivitiesModuleBulletCount,
                                    ['class' => 'badge badge-pill badge-danger']
                                ) : '';
                                ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="/myactivities/my-activities/index" data-toggle="tooltip"
                                       data-placement="bottom" title="<?= AmosMyActivities::t(
                                        'amosmyactivities',
                                        'My activities'
                                    )
                                    ?>">
                                        <?php if (
                                            !((isset(\Yii::$app->params['disableBulletCounters']) && (\Yii::$app->params['disableBulletCounters'] === true)) &&
                                            (!isset(\Yii::$app->params['enableMyActivitiesBulletCounters']) || (isset(\Yii::$app->params['enableMyActivitiesBulletCounters']) && (\Yii::$app->params['enableMyActivitiesBulletCounters'] === false)))
                                        )) : ?>
                                            <?= $menuMyActivitiesModuleBulletCount ?>
                                        <?php endif; ?>
                                        <svg class="icon">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_notifications"></use>
                                        </svg>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php
                            if (!empty($menuLanguages)):
                            ?>
                            <div class="nav-item dropdown menu-translation mr-1">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuTranslation"
                                   data-toggle="dropdown" aria-expanded="false">
                                    <?= $actualLang ?>
                                    <svg class="icon-expand icon icon-sm">
                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                    </svg>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuTranslation">
                                    <div class="link-list-wrapper">
                                        <ul class="link-list">
                                            <?= $menuLanguages ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                            endif;
                            ?>

                            <!-- TO FRONTEND LINK -->
                            <?php if (isset(\Yii::$app->params['toFrontendLink']) && \Yii::$app->params['toFrontendLink']) : ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="<?= \Yii::$app->params['platform']['frontendUrl'] ?>"
                                       title="<?= Yii::t(
                                           'amoscore',
                                           '#frontend'
                                       )
                                       ?>" <?= (isset(\Yii::$app->params['toFrontendLinkNoBlank']) && \Yii::$app->params['toFrontendLinkNoBlank'])
                                        ? 'target="_blank"' : ''
                                    ?>>
                                        <svg class="icon">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_exit_to_app"></use>
                                        </svg>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <!-- TO DASHBOARD LINK -->
                            <?php if (isset(\Yii::$app->params['toDashboardLink']) && \Yii::$app->params['toDashboardLink']) : ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="/dashboard" title="<?= Yii::t(
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
                            <?php if ((isset(\Yii::$app->params['enableTickectNavbarHeader'])) && (\Yii::$app->params['enableTickectNavbarHeader'] == true)
                                && (\Yii::$app->getModule('tickets'))
                            ) :
                                ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="/ticket/assistenza/cerca-faq" title="<?= Module::t(
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
                            <?php if (!CurrentUser::getUserIdentity()) : ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="/site/login"
                                       title="<?= Yii::t('amoscore', 'Login') ?>"><?= Yii::t('amoscore', 'Login') ?></a>
                                </div>
                            <?php else : ?>
                                <div class="dropdown menu-profile">
                                    <a href="#" class="btn btn-primary btn-icon btn-full dropdown-toggle" role="button"
                                       id="dropdownMenuProfile" data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false">
                                        <span class="rounded-icon">
                                            <img class="icon icon-primary rounded-circle" src="<?= $userImage ?>"
                                                 alt="<?= $userNomeCognome ?>">
                                        </span>
                                        <span class="d-none d-lg-block text-capitalize"><?= $userNomeCognome ?></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="dropdownMenuProfile">
                                        <div class="link-list-wrapper">
                                            <ul class="link-list">
                                                <!-- DEIMPERSONATE -->
                                                <?php
                                                if ($deimpersonate) {
                                                    echo $menuDeimpersonate . Html::tag(
                                                            'li',
                                                            Html::tag('span', '', ['class' => 'divider'])
                                                        );
                                                }
                                                ?>
                                                <!-- WELCOME MESSAGE -->
                                                <?php
                                                if ($userWelcomeMessage) {
                                                    echo $menuUserWelcomeMessage . Html::tag(
                                                            'li',
                                                            Html::tag('span', '', ['class' => 'divider'])
                                                        );
                                                }
                                                ?>
                                                <?= ($menuMyProfile) ? $menuMyProfile
                                                    : ''
                                                ?>
                                                <?php
                                                if ($btnLogoutUrl) {
                                                    echo Html::tag(
                                                        'li',
                                                        Html::a(
                                                            Html::tag('span', Yii::t('amoscore', 'Esci')),
                                                            $btnLogoutUrl,
                                                            [
                                                                'data' => ['method' => 'post'],
                                                                'class' => 'list-item',
                                                                'title' => Yii::t('amoscore', 'Esci dalla piattaforma')
                                                            ]
                                                        )
                                                    );
                                                }
                                                ?>
                                                <?php
                                                if ($privacyPolicy || $cookiePolicy) {
                                                    echo Html::tag('li', Html::tag('span', '', ['class' => 'divider']));
                                                    echo Html::tag('li', Html::tag('h3', Yii::t('amoscore', 'Informative')));
                                                    echo Html::tag('li', Html::tag('span', '', ['class' => 'divider']));
                                                    echo $menuPrivacy . $menuCookie;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="it-nav-wrapper d-flex align-items-center">
        <?php if (!(isset($disableToggleSidebar) && !empty($disableToggleSidebar) && ($disableToggleSidebar == true))) : ?>
            <div class="it-header-navbar-wrapper theme-light-desk d-block d-lg-none position-relative mt-0">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <nav class="navbar pr-0">
                                <a class="icon-menu" data-toggle="collapse" href="#sidebarLeftMenu" role="button"
                                   aria-expanded="true" aria-controls="sidebarLeftMenu">
                                    <svg class="icon">
                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_menu"></use>
                                    </svg>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="it-header-center-wrapper pt-0 theme-light it-small-header w-100">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-center-content-wrapper">
                            <?= $this->render("bi-logo"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>