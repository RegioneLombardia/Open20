<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\dashboard\AmosDashboard;
use open20\amos\layout\Module;
use open20\amos\layout\toolbar\Nav;
use open20\amos\layout\toolbar\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var $this \yii\web\View
 */
?>

<div class="container-header">

    <?php
    /* Configuration of Slideshow - begin */
    if (\Yii::$app->getModule('slideshow') && isset(\Yii::$app->params['slideshow']) && \Yii::$app->params['slideshow'] === TRUE) {
        $slideshow      = new \open20\amos\slideshow\models\Slideshow;
        $route          = "/".\Yii::$app->request->getPathInfo();
        $idSlideshow    = $slideshow->hasSlideshow($route);
        $slideshowLabel = ($idSlideshow) ? $slideshow->findOne($idSlideshow)->label : NULL;
        echo \open20\amos\slideshow\widgets\SlideshowWidget::widget([]);
    }
    /** @var bool|false $disablePlatformLinks - if true all the links to dashboard, settings, etc are disabled */
    $disablePlatformLinks = isset(\Yii::$app->params['disablePlatformLinks']) ? \Yii::$app->params['disablePlatformLinks']
            : false;

    /** @var bool|false $disableSettings - if true hide the settings link in the navbar */
    $disableSettings = ((isset(\Yii::$app->params['hideSettings']) && (\Yii::$app->params['hideSettings'] === true)) || !\Yii::$app->user->can('VIEW_SETTINGS'));

    $hasSlideshow = (\Yii::$app->getModule('slideshow') && isset(\Yii::$app->params['slideshow']) && \Yii::$app->params['slideshow']
        === TRUE && $idSlideshow) ? TRUE : FALSE;

    if ($hasSlideshow) {
        $itemsSlideshow = ['<li class="divider"></li>',
            [
                'label' => (!empty($slideshowLabel)) ? $slideshowLabel : Yii::t('amoscore', 'Mostra introduzione'),
                'url' => '#',
                'options' => ['onclick' => new JsExpression('$("#amos-slideshow").modal("show");'), 'class' => 'open-slideshow-modal'],
            //'options' => ['class' => 'open-slideshow-modal'] //moved js in global.js
            ],
            '<li class="divider"></li>',
        ];
    } else {
        $itemsSlideshow = '<li class="divider"></li>';
    }

//if there is information page for policy or cookies - display link at the end of user menu
    $hasPrivacyLink = isset(\Yii::$app->params['privacyLink']);
    $privacyLink    = null;
    if ($hasPrivacyLink) {
        $privacyLink = \Yii::$app->params['privacyLink'];
    }
    $hasCookiesLink = isset(\Yii::$app->params['cookiesLink']);
    $cookiesLink    = null;
    if ($hasCookiesLink) {
        $cookiesLink = \Yii::$app->params['cookiesLink'];
    }

    /* Configuration of Slideshow - end  */

    /* Configuration header menu: field translation */
    $headerMenu      = new open20\amos\core\views\common\HeaderMenu();
    $menuTranslation = $headerMenu->getTranslationField();
    $menuCustom      = $headerMenu->getCustomContent();
    /* echo Translation button */
    $headerMenu->getToggleTranslate();
    ?>

    <header>
        <?php

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS):
            $innerContainerOptions = [
                'class' => 'no-container'
            ];
        else:
            $innerContainerOptions = [];
        endif;

        NavBar::begin([
            'options' => [
                'class' => 'navbar-default',
            ],
            'disablePlatformLinks' => $disablePlatformLinks,
            'innerContainerOptions' => $innerContainerOptions,
        ]);

        if (!CurrentUser::getUserIdentity()) {
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $benvenuto = Yii::t('amoscore', 'Benvenuto utente');
            if (NULL !== (CurrentUser::getUserProfile())) {
                if (CurrentUser::getUserProfile()->sesso == 'Maschio') {
                    $benvenuto = Yii::t('amoscore', 'Benvenuto {utente}',
                            array(
                            'utente' => CurrentUser::getUserProfile()
                    ));
                } elseif (CurrentUser::getUserProfile()->sesso == 'Femmina') {
                    $benvenuto = Yii::t('amoscore', 'Benvenuta {utente}',
                            array(
                            'utente' => CurrentUser::getUserProfile()
                    ));
                }
            }

            $model                                     = CurrentUser::getUserProfile();
            $url                                       = $model->getAvatarUrl('original');
            Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
            $roundImage                                = Yii::$app->imageUtility->getRoundImage($model);

            if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS):
                $style = "";
            else:
                $style = "margin-left: ".$roundImage['margin-left']."%; margin-top: ".$roundImage['margin-top']."%;";
            endif;

            $imgAvatar                                 = Html::img($url,
                    [
                    'class' => $roundImage['class'],
                    'style' => $style,
                    'alt' => Yii::t('amoscore', 'Avatar dell\'utente')
            ]);


            $items = [];

            /**
             * search button
             * check params from platform/backend/config/params.php
             */
            if (isset(\Yii::$app->params['searchNavbar']) && \Yii::$app->params['searchNavbar']) {
                $urlSearch = '/search/search/index';
                $search    = [
                    'label' => AmosIcons::show('search').Html::tag('p', Module::t('amoslayout', '#search')),
                    'items' => [
                        '<li><div class="search-bar-toggle">'
                        .'<form class="amosGlobalSearch-js" action="">' //used to trigger evenbt submit search form
                        .'<input class="form-control" type="text" name="queryString" />'
                        .'<button id="btn-search-submit" class="btn btn-navigation-primary" type="submit">'.AmosIcons::show('search').'<span class="sr-only">'.Module::t('amoslayout',
                            '#search').'</span></button>'
                        .'</form>'
                        .'<p>'.Module::t('amoslayout', '#search_description').'</p>'
                        .'<a id="show-advanced-search" href="/search/search?advancedSearch=1">'.Module::tHtml('amossearch', 'Ricerca avanzata').'</a>'
                        .'</div></li>'
                    ],
                    'options' => [
                        'class' => 'btn-toggle-search',
                    ],
                    'linkOptions' => ['title' => Module::t('amoslayout', '#search')]
                ];
                $items[]   = $search;
            }

            if ((isset(\Yii::$app->params['enableTickectNavbarHeader'])) && (\Yii::$app->params['enableTickectNavbarHeader'] == true)){		
                if (\Yii::$app->getModule('tickets')) {
                    $ticketsLink    = Html::tag('li',
                            Html::a(
                                AmosIcons::show("help-outline")
                                , '/ticket/assistenza/cerca-faq',
                                ['title' => \Yii::t('tickets', 'Faq')]
                            ), ['class' => 'header-plugin-icon']
                    );
                    $items[] = $ticketsLink;	
                }
            }
            
            $btnsLogout = '';
            $btnsEsci = '';
            $btnLogoutUrl = ['/admin/security/logout'];
            /** @var \open20\amos\socialauth\Module $socialAuthModule */
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if (!is_null($socialAuthModule) && ($socialAuthModule->enableSpid === true)) {
                $btnLogoutUrl['backTo'] = Url::to([
                    '/Shibboleth.sso/Logout',
                    'return' => 'https://idpcwrapper.crs.lombardia.it/PublisherMetadata/Logout?dest=' . urlencode(Url::to('/', true))
                ], true);
            }
            $btnEsciUrl = $btnLogoutUrl;
            $btnEsciUrl['goToFrontPage'] = true;
            if(Yii::$app->getModule('layout')->advancedLogoutActions) {
                // Logout button (after logout redirects to login page)
                $btnsLogout = [
                    'label' => Yii::t('amoscore', 'Logout'),
                    'url' => $btnLogoutUrl,
                    'linkOptions' => ['data-method' => 'post', 'title' => Yii::t('amoscore', 'logout')]
                ];
                // Exit button (after logout redirects to main frontend page set in params['platform']['frontendUrl'])
                $btnsEsci = [
                    'label' => Yii::t('amoscore', 'Esci'),
                    'url' => $btnEsciUrl,
                    'linkOptions' => ['data-method' => 'post', 'title' => Yii::t('amoscore', 'esci')]
                ];
            } else {
                $btnsEsci = [
                    'label' => Yii::t('amoscore', 'Esci'),
                    'url' => $btnLogoutUrl,
                    'linkOptions' => ['data-method' => 'post', 'title' => Yii::t('amoscore', 'esci')]
                ];
            }

        $disableMyprofile = (!empty(\Yii::$app->params['disableMenuUser']) && \Yii::$app->params['disableMenuUser'] == true);

            $userMenu = [
                'label' => '<div class="container-round-img-xs">'.$imgAvatar.'</div>'
//                'label' => AmosIcons::show('account', [
//                    'class' => 'am-2',
//                    'alt' => $this->title
//                ])
                .'<p>'.Yii::t('amoscore', '{utente}',
                    array(
                    'utente' => CurrentUser::getUserProfile()
                )).'</p>',
                'items' => [
                    '<li class="dropdown-header">'.$benvenuto.'</li>',
                    '<li class="divider"></li>',
                    //'<li class="dropdown-header">Azioni</li>',
                    'myProfile' => ($disablePlatformLinks || $disableMyprofile) ? '' : ([
                    'label' => Yii::t('amoscore', 'Il mio profilo'),
                    'url' => ['/admin/user-profile/update', 'id' => CurrentUser::getUserProfile()->id],
                    'linkOptions' => ['title' => Yii::t('amoscore', 'Il mio profilo')]
                    ]),
                    $btnsLogout,
                    $btnsEsci,
                    ($hasPrivacyLink || $hasCookiesLink) ?
                    '<li class="divider"></li>
                     <li class="dropdown-header">'.Yii::t('amoscore', 'Informative').'</li>
                     <li class="divider"></li>' : '',
                    ($hasPrivacyLink) ? $privacyLink : '',
                    ($hasCookiesLink) ? $cookiesLink : '',
                ],
                'options' => ['class' => 'user-menu'],
                'linkOptions' => ['title' => Yii::t('amoscore', 'azioni utente')]
            ];

            $settingsItems         = [
                '<li class="dropdown-header">'.Yii::t('amoscore', 'Impostazioni').'</li>',
            ];
            $settingsItemsElements = [];
            $atLeastOneElement     = false;
            if (\Yii::$app->controller instanceof \open20\amos\dashboard\controllers\base\DashboardController) {
                $atLeastOneElement       = true;
                $settingsItemsElements[] = [
                    'label' => Yii::t('amoscore', 'Ordinamenti dashboard'),
                    'url' => 'javascript:void(0);',
                    'options' =>
                    [
                        'class' => 'enable_order',
                        'id' => 'dashboard-edit-button',
                    ],
                    'linkOptions' => ['title' => Yii::t('amoscore', 'Impostazioni')]
                ];
            }
            if ($this->context->module->id == AmosDashboard::getModuleName() && Yii::$app->user->can('CAN_MANAGE_DASHBOARD')) {
                $atLeastOneElement       = true;
                $settingsItemsElements[] = [
                    'label' => Yii::t('amoscore', 'Gestisci widget'),
                    'url' => [
                        '/dashboard/manager',
                        'module' => $this->context->module->id,
                        'slide' => 1
                    ],
                    'linkOptions' => ['title' => Yii::t('amoscore', 'Gestisci widget')],
                ];
            }
            if ($atLeastOneElement) {
                $settingsItems[] = '<li class="divider"></li>';
                $settingsItems   = ArrayHelper::merge($settingsItems, $settingsItemsElements);
            }
            $settingsItems[] = ($hasSlideshow) ? '<li class="divider"></li>' : '';
            $settingsItems[] = ($hasSlideshow) ? ($itemsSlideshow[1]) : '';
            $settingsItems[] = '<li class="divider"></li>';
            //Impostare nel params dell'applicazione la versione, per esempio 'versione' => '1.0',
            $settingsItems[] = '<li class="dropdown-header pull-right">'.Yii::t('amoscore', 'Versione').' '.((isset(\Yii::$app->params['versione']))
                    ? \Yii::$app->params['versione'] : '0.1').'</li>';

            $settings = [
                'label' => AmosIcons::show('settings', [
                    'class' => 'am-2',
                ]).'<span class="sr-only">'.Yii::t('amoscore', 'impostazioni').'</span>',
                'items' => $settingsItems,
                'options' => ['class' => 'context-menu'],
                'linkOptions' => ['title' => Yii::t('amoscore', 'Impostazioni')]
            ];

            $deimpersonate = [
                'label' => AmosIcons::show('assignment-account',
                    [
                    'class' => 'am-2',
                ]).'<span class="sr-only">'.Yii::t('amoscore', 'De-Impersonate').'</span>',
                'url' => '/'.AmosAdmin::getModuleName().'/security/deimpersonate',
                'options' => ['class' => 'impersonate'],
                'linkOptions' => [
                    'title' => Yii::t('amoscore', 'De-impersonate')
                ]
            ];

            if (!$disablePlatformLinks && !$disableSettings) {
                $items[] = $settings;
            }

            if (Yii::$app->session->has('IMPERSONATOR')) {
                $items[] = $deimpersonate;
            }

            $items[] = $userMenu;


            $menuItems = $items;

            //Add menu of translation
            if (!empty($menuTranslation)) {
                $menuItems[] = $menuTranslation;
            }
            if (!empty($menuCustom)) {
                $menuItems[] = $menuCustom;
            }




            if (\Yii::$app->getModule('chat')) {
                $widget      = new \open20\amos\chat\widgets\icons\WidgetIconChat();
                $bulletCount = $widget->getBulletCount();
                $chatLink    = Html::tag('li',
                        Html::a(
                            AmosIcons::show('comments-o', [], 'dash')."<span class='badge'>".(($bulletCount > 0 ) ? $bulletCount
                                : "" )."</span>"
                            , '/messages',
                            ['title' => \open20\amos\chat\AmosChat::t('amoschat', 'Messaggi privati')]
                        ), ['class' => 'header-plugin-icon']
                );
                $menuItems[] = $chatLink;
            }

            if (\Yii::$app->getModule('myactivities') && !\Yii::$app->user->isGuest && Yii::$app->user->can('MYACTIVITIES_READ')) {
                $widget      = new \open20\amos\myactivities\widgets\icons\WidgetIconMyActivities();
                if (
                    (isset(\Yii::$app->params['disableBulletCounters']) && (\Yii::$app->params['disableBulletCounters'] === true)) &&
                    (!isset(\Yii::$app->params['enableMyActivitiesBulletCounters']) || (isset(\Yii::$app->params['enableMyActivitiesBulletCounters']) && (\Yii::$app->params['enableMyActivitiesBulletCounters'] === false)))
                ) {
                    $bulletCount = 0;
                } else {
                    $bulletCount = $widget->getBulletCount();
                }
                $myActivitiesLink    = Html::tag('li',
                        Html::a(
                            AmosIcons::show('bell', [], 'dash')."<span class='badge'>".(($bulletCount > 0 ) ? $bulletCount
                                : "" )."</span>"
                            , '/myactivities/my-activities/index',
                            ['title' => \open20\amos\myactivities\AmosMyActivities::t('amosmyactivities',
                                'My activities')]
                        ), ['class' => 'header-plugin-icon']
                );
                $menuItems[] = $myActivitiesLink;
            }



            /**
             * link to frontend
             * check params from platform/backend/config/params.php
             */
            if (isset(\Yii::$app->params['toFrontendLink']) && \Yii::$app->params['toFrontendLink']) {
                /**
                 * get params from platform/common/config/params-local.php
                 */

                if (isset(\Yii::$app->params['toFrontendLinkNoBlank']) && \Yii::$app->params['toFrontendLinkNoBlank']) {
                    $frontendLink = Html::tag('li',
                        Html::a(
                            AmosIcons::show('globe-alt') . Html::tag('p', Yii::t('amoscore', '#frontend'))
                            , Url::to(\Yii::$app->params['platform']['frontendUrl']),
                            ['title' => Yii::t('amoscore', '#frontend')]
                        ), ['class' => 'toFrontend']
                    );
                    $menuItems[] = $frontendLink;
                } else {
                    $frontendLink = Html::tag('li',
                        Html::a(
                            AmosIcons::show('globe-alt') . Html::tag('p', Yii::t('amoscore', '#frontend'))
                            , Url::to(\Yii::$app->params['platform']['frontendUrl']),
                            ['title' => Yii::t('amoscore', '#frontend'), 'target' => '_blank']
                        ), ['class' => 'toFrontend']
                    );
                    $menuItems[] = $frontendLink;
                }
            } /* end link frontend */

            /**
             * link to dashboard
             * check params from platform/backend/config/params.php
             */
            if (isset(\Yii::$app->params['toDashboardLink']) && \Yii::$app->params['toDashboardLink']) {
                /**
                 * get params from platform/common/config/params-local.php
                 */
                $dashboardLink = Html::tag('li',
                        Html::a(
                            AmosIcons::show('apps').Html::tag('p', Yii::t('amoscore', '#to_dashboard_link_text'))
                            , Url::to(['/dashboard']), ['title' => Yii::t('amoscore', '#to_dashboard_link_text')]
                        ), ['class' => 'toDashboard']
                );
                $menuItems[]   = $dashboardLink;
            } /* end link dashboard */
        }

        $disableHeaderMenu = isset(\Yii::$app->params['disableHeaderMenu']) ? \Yii::$app->params['disableHeaderMenu'] : false;
        if ($disableHeaderMenu) {
            $menuItems = [];
        }

        echo Nav::widget([
            'options' => [
                'class' => 'navbar-nav navbar-right',
            ],
            'encodeLabels' => false,
            'dropDownCaret' => '',
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>

    </header>
</div>
