<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
/**
 */
return [
    //old TODO CHECK
    'user.passwordResetTokenExpire' => 86400,
    'loginTimeout' => 3600 * 3, //3 Hours
    'forceUpdateUrlTranslations' => false,
    'forms-purify-data' => false,
    'disableAfterFindPurify' => true,
    'disableBulletCounters' => false,
    'supportEmail' => 'helpfrontend@example.com',
    'dashboardEngine' => 'rows',
    //new
    'befe' => true,
    'assistance' => [
        'enabled' => true,
        'type' => 'mail',
        'email' => 'ticket@test.example.it',
        'url' => ''
    ],
    'favicon' => '/img/faviconRL.png',
    'logoMail' => '/img/RL_logo.png',
    'logoConfigurations' => [
        'firstLogo' => [
            'logoImg' => '/img/RL_logo.png',
            'logoAlt' => 'logo Regione Lombardia',
            'logoUrl' => '/',
            'logoUrlTarget' => '_blank',
            'logoTitle' => 'Vai al portale di Regione Lombardia',
            'logoClass' => 'd-md-block logo-RL-design',
        ],
        'secondLogo' => [
            'logoText' => 'Open 2.0',
            'logoUrl' => '/site/to-menu-url?url=/',
            'logoTitle' => 'Vai alla homepage di Open 2.0',
            'logoClass' => 'logo-text-platform-design',
        ],
    ],
    'linkConfigurations' => [
        'privacyPolicyLinkCommon' => '/informativa-privacy',
        'cookiePolicyLinkCommon' => '/informativa-cookie',
        'loginLinkCommon' => '/it/login',
        'logoutLinkCommon' => '/site/logout',
        'registrationLinkCommon' => '/preferences/user',
        'pageSearchLinkCommon' => '/ricerca'
    ],
    'layoutConfigurations' => [
        'hideCmsMenuPluginHeader' => true,
        'hideLangSwitchMenuHeader' => true,
        'showAlwaysHamburgerMenuHeader' => true,
        'enableBtnModifyCmsPage' => true,
        'enableMenuDoubleAction' => false,
        'showSidebarRedattore' => true,
        'hideGlobalSearchHeaderForGuest' => false,
        'showSidebarRedattorePermission' => ['ADMIN', 'REDATTORECMS', 'CAPOREDATTORECMS'],
        'hideAssistance' => true,
        'fluidContainerHeader' => true,
        'showSocialHeader' => true,
        'hideUserMenuHeaderPermissions' => ['ADMIN', 'REDATTORECMS', 'CAPOREDATTORECMS'],
        'customPlatformFooter' => '@common/views/layouts/parts/footer-custom',
    ],
    'menuCmsConfigurations' => [
    ],
    'twitter-x-enabled' => true,
    'layoutMailConfigurations' => [
        'logoMail' => [
            'logoImg' => '/img/RL_logo.png',
            'logoImgAlt' => 'logo Open 2.0',
            'logoImgWidth' => '194', //not insert px string
            'logoImgHeight' => '54', //not insert px string
        ],
        'bgPrimary' => '#297a38',
        'bgPrimaryDark' => '#1c5426', //darken 10% primary
        'textContrastBgPrimary' => '#FFFFFF',
        'textContrastBgPrimaryDark' => '#FFFFFF'
    ],
    'retention-levels' => [
        [4, 11, 3],
        [0, 0, 1]
    ],
    'disableLinkContentCreator' => true,
    'forms-purify-data-enable-amp' => true
];
