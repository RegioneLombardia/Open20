<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\assets\PrismJSAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$PrismJSAsset = PrismJSAsset::register($this);
?>


<div class="mt-3 container-fluid">
    <div class="row">
        <div id="jsMenu" class="col-12 col-lg-3">
            <nav class="navbar it-navscroll-wrapper navbar-expand-lg it-bottom-navscroll it-right-side py-3 py-lg-0">
                <button class="custom-navbar-toggler" type="button" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" data-target="#navbarNav">
                    <span class="it-list"></span>Apri menu
                </button>
                <div class="navbar-collapsable" id="navbarNav" style="display: none;">
                    <div class="overlay" style="display: none;"></div>
                    <div class="close-div sr-only">
                        <button class="btn close-menu" type="button">
                            <span class="it-close"></span>close
                        </button>
                    </div>
                    <a class="it-back-button" href="#" style="display: none;">
                        <svg class="icon icon-sm icon-primary align-top">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-chevron-left"></use>
                        </svg>
                        <span>Indietro</span>
                    </a>
                    <div class="menu-wrapper">
                        <div class="link-list-wrapper">
                            <ul class="link-list">
                                <li>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="#configurazioni-assistenza"><span>assistance</span>
                                        </a>
                                        <a href="#collapseOne" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                                            <svg class="icon icon-xs icon-primary right">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                            </svg>
                                        </a>
                                    </div>

                                    <ul class="link-sublist collapse " id="collapseOne">

                                        <li>
                                            <a class="list-item" href="#configurazioni-assistenza"><span>hideAssistance</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-assistenza"><span>email</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-assistenza"><span>type</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-assistenza"><span>url</span></a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a class="list-item" href="#configurazioni-layout"><span>layoutConfigurations</span></a>
                                        <a class="list-item large medium right-icon" href="#collapseTwo" data-toggle="collapse" aria-expanded="false" aria-controls="collapseTwo">
                                            <svg class="icon icon-xs icon-primary right">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                            </svg>
                                        </a>
                                    </div>


                                    <ul class="link-sublist collapse " id="collapseTwo">
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>customCopyleftFooter </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>customPlatformFooter </span></a>
                                        </li>
										<li>
                                            <a class="list-item" href="#configurazioni-layout"><span>customPlatformGuide </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>customPlatformHead </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>customPlatformPrivacyCheck </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>customUserMenuHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>customUserNotLoggedHeader </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>disableSmallHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>disableThemeLightHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>enableBtnModifyCmsPage </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>enableHeaderStickyHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>enableMenuDoubleAction </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#list-allegati"><span>fluidContainerHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideBreadcrumbs </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideCmsMenuPluginHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideCookieBar </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideGlobalSearchHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideGlobalSearchHeaderForGuest </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>enableGlobalSearchForm </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideHamburgerMenuHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideLangSwitchMenuHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideSpidDescriptionLogin </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>hideUserMenuHeader </span></a>
                                        </li>


                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>showAlwaysHamburgerMenuHeader </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>showLiteModeLogin </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>showSecondaryMenuHeader </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#list-allegati"><span>showSocialFooter </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>showSocialHeader </span></a>
                                        </li>


                                        <li>
                                            <a class="list-item" href="#configurazioni-layout"><span>url</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a class="list-item" href="#configurazioni-logo"><span>logoConfigurations</span></a>
                                        <a class="list-item large medium right-icon" href="#collapseFive" data-toggle="collapse" aria-expanded="false" aria-controls="collapseFive">
                                            <svg class="icon icon-xs icon-primary right">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                            </svg>
                                        </a>
                                    </div>


                                    <ul class="link-sublist collapse " id="collapseFive">
                                        <li>
                                            <a class="list-item" href="#configurazioni-logo"><span>logoAlt </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-logo"><span>logoClass</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-logo"><span>logoImg </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-logo"><span>logoText </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-logo"><span>logoTitle </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-logo"><span>logoUrl </span></a>
                                        </li>



                                        <li>
                                            <a class="list-item" href="#configurazioni-logo"><span>logoUrlTarget </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-logo"><span>positionTop </span></a>
                                        </li>

                                    </ul>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a class="list-item" href="#configurazioni-link"><span>linkConfigurations</span></a>
                                        <a class="list-item large medium right-icon" href="#collapseThree" data-toggle="collapse" aria-expanded="false" aria-controls="collapseThree">
                                            <svg class="icon icon-xs icon-primary right">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <ul class="link-sublist collapse " id="collapseThree">
                                        <li>
                                            <a class="list-item" href="#configurazioni-link"><span>cookiePolicyLinkCommon </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-link"><span>forgotPasswordLinkCommon </span></a>
                                        </li>


                                        <li>
                                            <a class="list-item" href="#configurazioni-link"><span>loginLinkCommon </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-link"><span>logoutLinkCommon </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-link"><span>pageSearchLinkCommon </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-link"><span>privacyPolicyLinkCommon </span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#configurazioni-link"><span>registrationLinkCommon </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-link"><span>userProfileLinkCommon </span></a>
                                        </li>

                                    </ul>
                                </li>

                                <li>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a class="list-item" href="#configurazioni-cms"><span>menuCmsConfigurations</span></a>
                                        <a class="list-item large medium right-icon" href="#collapseFour" data-toggle="collapse" aria-expanded="false" aria-controls="collapseFour">
                                            <svg class="icon icon-xs icon-primary right">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <ul class="link-sublist collapse " id="collapseFour">
                                        <li>
                                            <a class="list-item" href="#configurazioni-cms"><span>footerCmsMenu </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-cms"><span>mainCmsMenu </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-cms"><span>secondaryCmsMenu </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-cms"><span>myOpenCmsMenu </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-cms"><span>customPlatformMenu </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-cms"><span>mainEngCmsMenu </span></a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a class="list-item" href="#configurazioni-social"><span>socialConfigurations</span></a>
                                        <a class="list-item large medium right-icon" href="#collapseSix" data-toggle="collapse" aria-expanded="false" aria-controls="collapseSix">
                                            <svg class="icon icon-xs icon-primary right">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <ul class="link-sublist collapse " id="collapseSix">
                                        <li>
                                            <a class="list-item" href="#configurazioni-social"><span>facebook </span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-social"><span>instagram</span></a>
                                        </li>

                                    </ul>
                                </li>

                                <li>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a class="list-item" href="#configurazioni-mail"><span>layoutMailConfigurations</span></a>
                                        <a class="list-item large medium right-icon" href="#collapseSeven" data-toggle="collapse" aria-expanded="false" aria-controls="collapseSeven">
                                            <svg class="icon icon-xs icon-primary right">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <ul class="link-sublist collapse " id="collapseSeven">
                                        <li>
                                            <a class="list-item" href="#configurazioni-social"><span>logoMail</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-social"><span>bgPrimary</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-social"><span>bgPrimaryDark</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-social"><span>textContrastBgPrimary</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#configurazioni-social"><span>textContrastBgPrimaryDark</span></a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>

            </nav>
        </div>
        <div id="jsMain" class="col-12 col-lg-9 it-page-sections-container">
            <h1>LISTA CONFIGURAZIONI DESIGN</h1>
            <p class="equal-font-size-h4">Le configurazioni vanno inserite nel file <strong>common/common/config/params.php</strong> e sostituiscono le precedenti.</p>
            <p>Viene visualizzato il badge <span class="badge badge-success">Attivo</span> se la configurazione è attiva nella piattaforma corrente</p>
            <hr>
            <!-- Gestione loghi-->
            <section class="py-5">
                <h2 class="it-page-section" id="gestione-loghi">Gestione loghi in header</h2>
                <p>Array unico di configurazione denominato <strong>logoConfigurations</strong></p>
                <p>Ogni array di primo livello rappresenta un <strong>logo</strong> che sarà visualizzato nella zona header (parte alta se positionTop = TRUE).</p>
                <div class="row variable-gutters mt-5">
                    <div class="col-md-5">
                        <p class="text-uppercase font-weight-bold">Lista configurazioni</p>
                        <hr>
                        <ul>
                            <li>
                                <strong>logoImg</strong> : rappresenta il path del file (attualmente deve essere presente sia in backend/web che in frontend/web)
                            </li>
                            <li>
                                <strong>logoText</strong> : rappresenta la stringa stampata
                            </li>
                            <li>
                                <strong>logoAlt</strong> : attributo alt
                            </li>
                            <li>
                                <strong>logoClass</strong> : classe aggiuntiva
                            </li>
                            <li>
                                <strong>logoUrl</strong> : link associato; se passato 'frontendUrl' andrà a rimpiazzarsi con Yii::$app->params['platform']['frontendUrl'], stessa cosa per 'backendUrl'
                            </li>
                            <li>
                                <strong>logoTitle</strong> : title link
                            </li>
                            <li>
                                <strong>logoUrlTarget</strong> : target link
                            </li>
                            <li>
                                <strong>positionTop</strong> => TRUE: posiziona il logo nella parte alta
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-7">
                        <p class="text-uppercase">Esempio</p>
                        <hr>
                        <pre>
                            <code class="language-php">
'logoConfigurations' => [
    'firstLogo' => [
        'logoImg' => '/img/Logo-Preference-Center.svg',
        'logoAlt' => 'logo Lombardia Informa',
    ],
    'topLogo' => [
        'logoImg' => '/img/logo_regione_lombardia-bianco.svg',
        'logoAlt' => 'logo Regione Lombardia',
        'logoPosition' => 'd-xs-block logo-rl-top',
        'logoUrl' => 'https://regione.lombardia.it',
        'logoTitle' => 'Vai al portale di Regione Lombardia',
        'logoUrlTarget' => '_blank',
        'positionTop' => true,
    ]
],
                            </code>
                        </pre>
                    </div>

                </div>
            </section>

            <!-- Configurazione layout-->
            <section class="py-5">
                <h2 class="it-page-section" id="configurazioni-layout">Configurazioni di layout</h2>
                <p>Array unico di configurazione denominato <strong>layoutConfigurations</strong></p>
                <div class="row variable-gutters mt-5">
                    <div class="col-md-5">
                        <p class="text-uppercase font-weight-bold">Lista configurazioni</p>
                        <hr>
                        <h3>Head</h3>
                        <ul>
                            <li>

                                <strong>
                                    customPlatformHead
                                    <?php if (\Yii::$app->params['layoutConfigurations']['customPlatformHead']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : path view custom
                            </li>

                        </ul>
                        <h3>Header</h3>
                        <ul>
                            <li>
                                <strong>
                                    disableThemeLightHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['disableThemeLightHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => disabilita classe theme-light
                            </li>
                            <li>
                                <strong>
                                    hideTopHeaderForGuestUser
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide color top header for guest user and auto enable alternative login link in menu
                            </li>
                            <li>
                                <strong>
                                    fluidContainerHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['fluidContainerHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : if true switch container class with container-fluid, also enabled as a <u>view parameter</u>
                            </li>
                            <li>
                                <strong>
                                    customUserNotLoggedHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['customUserNotLoggedHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : path list link user not logged view custom
                            </li>
                            <li>
                                <strong>
                                    hideGlobalSearchHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide global search
                            </li>
                            <li>
                                <strong>
                                    hideGlobalSearchHeaderForGuest
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeaderForGuest']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide global search for guest users
                            </li>
                            <li>
                                <strong>
                                    enableGlobalSearchForm
                                    <?php if (\Yii::$app->params['layoutConfigurations']['enableGlobalSearchForm']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => show direct search form brandbook compliant
                            </li>
                            <li>
                                <strong>
                                    disableSmallHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['disableSmallHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => disable class it-small-header
                            </li>
                            <li>
                                <strong>
                                    showSocialHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['showSocialHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => show social in header
                            </li>
                            <li>
                                <strong>
                                    enableHeaderStickyHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => abilita sticky header
                            </li>
                        </ul>
                        <h3>Menu</h3>
                        <ul>
                            <li>
                                <strong>
                                    hideHamburgerMenuHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideHamburgerMenuHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide hamburger menu
                            </li>
                            <li>
                                <strong>
                                    showAlwaysHamburgerMenuHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['showAlwaysHamburgerMenuHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hamburger menu persistent for all media
                            </li>
                            <li>
                                <strong>
                                    enableMenuDoubleAction
                                    <?php if (\Yii::$app->params['layoutConfigurations']['enableMenuDoubleAction']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true enable double action on dropdown menu (label + icon)
                            </li>
                            <li>
                                <strong>
                                    alwaysHamburgerMenuRight
                                    <?php if (\Yii::$app->params['layoutConfigurations']['alwaysHamburgerMenuRight']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hamburger menu right modal
                            </li>
                            <li>
                                <strong>
                                    hideLangSwitchMenuHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideLangSwitchMenuHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide language switcher menu
                            </li>
                            <li>
                                <strong>
                                    customUserMenuHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['customUserMenuHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : path list link user menu view custom
                            </li>
                            <li>
                                <strong>
                                    hideUserMenuHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideUserMenuHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide user menu, also enabled as a <u>view parameter</u>
                            </li>
                            <li>
                                <strong>
                                    showSecondaryMenuHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['showSecondaryMenuHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => visualizza un menu secondario sulla parte destra della navbar
                            </li>
                            <li>
                                <strong>
                                    hideCmsMenuPluginHeader
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideCmsMenuPluginHeader']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => nasconde il menu di default dei plugin (attualmente configurato in common/frontend/configs/modules.php nell'array 'backendobjects')
                            </li>
                        </ul>
                        <h3>Pagina</h3>
                        <ul>
                            <li>
                                <strong>
                                    enableBtnModifyCmsPage
                                    <?php if (\Yii::$app->params['layoutConfigurations']['enableBtnModifyCmsPage']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => enable edit cms button in page
                            </li>
                            <li>
                                <strong>
                                    showSidebarRedattore
                                    <?php if (\Yii::$app->params['layoutConfigurations']['showSidebarRedattore']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => enable sidebar action for redattore
                            </li>
                        </ul>
                        <h3>Footer</h3>
                        <ul>
                            <li>

                                <strong>
                                    customPlatformFooter
                                    <?php if (\Yii::$app->params['layoutConfigurations']['customPlatformFooter']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : path view custom
                            </li>
                            <li>

                                <strong>
                                    customCopyleftFooter
                                    <?php if (\Yii::$app->params['layoutConfigurations']['customCopyleftFooter']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : copyleft string
                            </li>

                            <li>
                                <strong>
                                    showSocialFooter
                                    <?php if (\Yii::$app->params['layoutConfigurations']['showSocialFooter']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => show social in footer
                            </li>
                            <li>

                                <strong>
                                    hideCookieBar
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideCookieBar']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide cookiebar
                            </li>
                        </ul>
                        <h3>Login</h3>
                        <ul>
                            <li>

                                <strong>
                                    hideSpidDescriptionLogin
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideSpidDescriptionLogin']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide description of SPID button
                            </li>
                            <li>

                                <strong>
                                    showLiteModeLogin
                                    <?php if (\Yii::$app->params['layoutConfigurations']['showLiteModeLogin']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide login form and render only login buttons
                            </li>
                        </ul>
                        <h3>Breadcrumbs</h3>
                        <ul>
                            <li>
                                <strong>
                                    hideBreadcrumbs
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideBreadcrumbs']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : hide breadcrumbs in layout, also enabled as a <u>view parameter</u>
                            </li>
                        </ul>
                        <h3>Privacy Pilicy</h3>
                        <ul>
                            <li>
                                <strong>
                                    customPlatformPrivacyCheck
                                    <?php if (\Yii::$app->params['layoutConfigurations']['customPlatformPrivacyCheck']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : path view custom
                            </li>
                        </ul>
                        <h3>Assistance</h3>
                        <ul>
                            <li>
                                <strong>
                                    hideAssistance
                                    <?php if (\Yii::$app->params['layoutConfigurations']['hideAssistance']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : true => hide assistance in layout, also enabled as a <u>view parameter</u>
                            </li>
                        </ul>
                        <h3>Guide</h3>
                        <ul>
                            <li>
                                <strong>
                                    customPlatformGuide
                                    <?php if (\Yii::$app->params['layoutConfigurations']['customPlatformGuide']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : path view custom, also enabled as a <u>view parameter</u>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-7">
                        <p class="text-uppercase">Esempio</p>
                        <hr>
                        <pre>
                            <code class="language-php">
'layoutConfigurations' => [
    'customPlatformHead' => '@common/views/layouts/parts/bi-head',
    'customPlatformFooter' => '@common/views/layouts/parts/footer-custom',
    'customUserMenuHeader' => '@common/views/layouts/parts/header-usermenu-custom',
    'fluidContainerHeader' => false,
    'hideHamburgerMenuHeader' => false,
    'showAlwaysHamburgerMenuHeader' => true,
    'hideGlobalSearchHeader' => true,
    'hideUserMenuHeader' => false,
    'hideSpidDescriptionLogin' => true,
    'hideIdpcButtonInfo' => true,
    'hideCookieBar' => true,
    'showLiteModeLogin' => false,
    'showSocialHeader' => false,
    'disableSmallHeader' => true,
    'hideAssistance' => true
    ],
                            </code>
                        </pre>
                    </div>
                </div>
            </section>

            <!-- Configurazione di link-->
            <section class="py-5">
                <h2 class="it-page-section" id="configurazioni-link">Configurazioni di link</h2>
                <p>Array unico di configurazione denominato <strong>linkConfigurations</strong> (gli override valgono per tutta la piattaforma)</p>
                <div class="row variable-gutters mt-5">
                    <div class="col-md-6">
                        <p class="text-uppercase font-weight-bold">Lista configurazioni</p>
                        <hr>
                        <ul>
                            <li>
                                <strong>
                                    privacyPolicyLinkCommon
                                    <?php if (\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override privacy policy url
                            </li>
                            <li>

                                <strong>
                                    cookiePolicyLinkCommon
                                    <?php if (\Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override cookie policy url
                            </li>
                            <li>

                                <strong>
                                    registrationLinkCommon
                                    <?php if (\Yii::$app->params['linkConfigurations']['registrationLinkCommon']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override registration url
                            </li>
                            <li>
                                <strong>
                                    loginLinkCommon
                                    <?php if (\Yii::$app->params['linkConfigurations']['loginLinkCommon']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override login url
                            </li>
                            <li>
                                <strong>
                                    logoutLinkCommon
                                    <?php if (\Yii::$app->params['linkConfigurations']['logoutLinkCommon']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override logout url
                            </li>
                            <li>
                                <strong>
                                    forgotPasswordLinkCommon
                                    <?php if (\Yii::$app->params['layoutConfigurations']['forgotPasswordLinkCommon']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override forget password url
                            </li>
                            <li>
                                <strong>
                                    userProfileLinkCommon
                                    <?php if (\Yii::$app->params['layoutConfigurations']['userProfileLinkCommon']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override user profile url
                            </li>
                            <li>
                                <strong>
                                    pageSearchLinkCommon
                                    <?php if (\Yii::$app->params['linkConfigurations']['pageSearchLinkCommon']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : search page url
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p class="text-uppercase">Esempio</p>
                        <hr>
                        <pre>
                            <code class="language-php">
'linkConfigurations' => [
    'privacyPolicyLinkCommon' => '/it/privacy-policy',
    'cookiePolicyLinkCommon' => '/it/cookie-policy',
    'registrationLinkCommon' => '/preferenceuser/registration/preferences',
    'loginLinkCommon' => '/preferenceuser/preference/settings',
],
                            </code>
                        </pre>
                    </div>
                </div>
            </section>

            <!-- contenitori menu CMS-->
            <section class="py-5">
                <h2 class="it-page-section" id="configurazioni-cms">Configurazioni menu CMS</h2>
                <p>Array unico di configurazione denominato <strong>menuCmsConfigurations</strong> (gli override valgono per tutta la piattaforma)</p>
                <div class="row variable-gutters mt-5">
                    <div class="col-md-6">
                        <p class="text-uppercase font-weight-bold">Lista configurazioni</p>
                        <hr>
                        <ul>
                            <li>
                                <strong>
                                    mainCmsMenu
                                    <?php if (\Yii::$app->params['menuCmsConfigurations']['mainCmsMenu']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override main cms menu container (default is 'default')
                            </li>
                            <li>
                                <strong>
                                    mainEngCmsMenu
                                    <?php if (\Yii::$app->params['menuCmsConfigurations']['mainEngCmsMenu']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override mainCmsMenu with alternative EN container (default is 'default-eng')
                            </li>
                            <li>
                                <strong>
                                    myOpenCmsMenu
                                    <?php if (\Yii::$app->params['menuCmsConfigurations']['myOpenCmsMenu']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override main cms menu container (default is 'myopen')
                            </li>
                            <li>
                                <strong>
                                    secondaryCmsMenu
                                    <?php if (\Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override secondary cms menu container (default is 'secondary')
                            </li>
                            <li>
                                <strong>
                                    customPlatformMenu
                                    <?php if (\Yii::$app->params['menuCmsConfigurations']['customPlatformMenu']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : path view custom platform menu
                            </li>
                            <li>
                                <strong>
                                    footerCmsMenu
                                    <?php if (\Yii::$app->params['menuCmsConfigurations']['footerCmsMenu']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : override footer cms menu container (default is 'footer')
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p class="text-uppercase">Esempio</p>
                        <hr>
                        <pre>
                            <code class="language-php">
'menuCmsConfigurations' => [
    'mainCmsMenu' => 'mainMenuDesign',
    'footerCmsMenu' => 'footerMenuDesign'
    'customPlatformMenu' => '@common/views/layouts/parts/plugin-menu-custom',
],
                            </code>
                        </pre>
                    </div>
                </div>
            </section>

            <!-- contenitori assistenza-->
            <section class="py-5">
                <h2 class="it-page-section" id="configurazioni-assistenza">Configurazioni assistenza</h2>
                <p>Array unico di configurazione denominato <strong>assistance</strong> (gli override valgono per tutta la piattaforma)</p>
                <div class="row variable-gutters mt-5">
                    <div class="col-md-6">
                        <p class="text-uppercase font-weight-bold">Lista configurazioni</p>
                        <hr>
                        <ul>
                            <li>
                                <strong>
                                    type
                                    <?php if (\Yii::$app->params['assistance']['type']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong>: <?= \Yii::$app->params['assistance']['type'] ?> => decide la modalità di uso dell'assistenza: url o email
                            </li>
                            <li>
                                <strong>
                                    email
                                    <?php if (\Yii::$app->params['assistance']['email']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : <?= \Yii::$app->params['assistance']['email'] ?> => indirizzo email dell'asistenza
                            </li>
                            <li>
                                <strong>
                                    url
                                    <?php if (\Yii::$app->params['assistance']['url']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : <?= \Yii::$app->params['assistance']['url'] ?> => url della pagina di assistenza
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p class="text-uppercase">Esempio</p>
                        <hr>
                        <pre>
                            <code class="language-php">
'assistance' => [
    'type' => 'url', //Assistance button mode: url or email
    'email' => 'ticket@openinnovationlombardia.it', //Assistance mail
    'url' => 'tickets' //Url to the assistance page
],
                            </code>
                        </pre>
                    </div>
                </div>
            </section>

            <!-- contenitori social-->
            <section class="py-5">
                <h2 class="it-page-section" id="configurazioni-social">Configurazioni social</h2>
                <p>Array unico di configurazione denominato <strong>socialConfigurations</strong> (gli override valgono per tutta la piattaforma)</p>
                <div class="row variable-gutters mt-5">
                    <div class="col-md-6">
                        <p class="text-uppercase font-weight-bold">Lista configurazioni</p>
                        <hr>
                        <ul>
                            <li>
                                <strong>
                                    facebook
                                    <?php if (\Yii::$app->params['socialConfigurations']['facebook']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : link a facebook
                            </li>
                            <li>
                                <strong>
                                    instagram
                                    <?php if (\Yii::$app->params['socialConfigurations']['instagram']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : link a instagram
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p class="text-uppercase">Esempio</p>
                        <hr>
                        <pre>
                            <code class="language-php">
'socialConfigurations' => [
    'facebook' => 'https://facebook.com',
    'instagram' => 'https://instagram.com',
]
                            </code>
                        </pre>
                    </div>
                </div>
            </section>
            <section class="py-5">
                <h2 class="it-page-section" id="configurazioni-social">Configurazioni mail</h2>
                <p>Array unico di configurazione denominato <strong>layoutMailConfigurations</strong> (gli override valgono per tutta la piattaforma)</p>
                <div class="row variable-gutters mt-5">
                    <div class="col-md-6">
                        <p class="text-uppercase font-weight-bold">Lista configurazioni</p>
                        <hr>
                        <ul>
                            <li>
                                <strong>
                                    logoMail['logoImg']
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImg']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : immagine del logo da visualizzare nella mail
                            </li>
                            <li>
                                <strong>
                                    logoMail['logoImgAlt']
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgAlt']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : alt dell'immagine del logo da visualizzare nella mail
                            </li>
                            <li>
                                <strong>
                                    logoMail['logoImgWidth']
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgWidth']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : larghezza in px dell'immagine del logo da visualizzare nella mail
                            </li>
                            <li>
                                <strong>
                                    logoMail['logoImgHeight']
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgHeight']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : altezza in px dell'immagine del logo da visualizzare nella mail
                            </li>
                            <li>
                                <strong>
                                    logoMail['logoText']
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['logoMail']['logoText']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : testo da visualizzare nella mail come logo
                            </li>

                            <li>
                                <strong>
                                    bgPrimary
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['bgPrimary']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : colore sfondo primario mail
                            </li>

                            <li>
                                <strong>
                                    bgPrimaryDark
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['bgPrimaryDark']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : colore sfondo primario più scuro
                            </li>
                            <li>
                                <strong>
                                    textContrastBgPrimary
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['textContrastBgPrimary']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : colore testo a contrato del primario
                            </li>
                            <li>
                                <strong>
                                    textContrastBgPrimaryDark
                                    <?php if (\Yii::$app->params['layoutMailConfigurations']['textContrastBgPrimaryDark']) : ?>
                                        <span class="badge badge-success">Attivo</span>
                                    <?php endif ?>
                                </strong> : colore testo a contrato del primario scuro
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p class="text-uppercase">Esempio</p>
                        <hr>
                        <pre>
                            <code class="language-php">
'layoutMailConfigurations' => [
    'logoMail' => [
        'logoImg' => '/img/logoRL.png',
        'logoImgAlt' => 'logo Regione Lombardia',
        'logoImgWidth' => '194px',
        'logoImgHeight' => '54px',
        'logoText' => 'Open 2.0'
    ],
    'bgPrimary' => '#297a38',
    'bgPrimaryDark' => '#1c5426', //darken 10% primary
    'textContrastBgPrimary' => '#FFFFFF',
    'textContrastBgPrimaryDark' => '#FFFFFF'
]
                            </code>
                        </pre>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>