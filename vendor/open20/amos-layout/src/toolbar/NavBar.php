<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

namespace open20\amos\layout\toolbar;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\dashboard\AmosDashboard;
use open20\amos\layout\Module;
use open20\amos\layout\toolbar\NavBarOverriden as YiiNavBar;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Class NavBar - draws the toogle button for hamburger menu (small size screen)
 * @package open20\amos\core\toolbar
 */
class NavBar extends YiiNavBar {

  public $disablePlatformLinks = false;

  /*
   * eliminato il bottone di visualizzazione menu su mobile
   */

  protected function renderToggleButton() {
    if (!CurrentUser::getUserIdentity()) {
      $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
      $benvenuto = Yii::t('amoscore', 'Benvenuto utente');
      if (null !== (CurrentUser::getUserProfile())) {
        $benvenuto = Yii::t('amoscore', '{utente}',
            array(
              'utente' => CurrentUser::getUserProfile()
        ));
      }
      /* Configuration of Slideshow - begin */
      if (\Yii::$app->getModule('slideshow') && isset(\Yii::$app->params['slideshow']) && \Yii::$app->params['slideshow'] === TRUE) {
        $slideshow = new \open20\amos\slideshow\models\Slideshow;
        $route = "/" . \Yii::$app->request->getPathInfo();
        $idSlideshow = $slideshow->hasSlideshow($route);
        $slideshowLabel = ($idSlideshow) ? $slideshow->findOne($idSlideshow)->label : NULL;
      }

      $hasSlideshow = (\Yii::$app->getModule('slideshow') && isset(\Yii::$app->params['slideshow']) && \Yii::$app->params['slideshow'] === TRUE && $idSlideshow) ? TRUE : FALSE;

      if ($hasSlideshow) {
        $itemsSlideshow = ['<li class="divider"></li>',
          [
            'label' => (!empty($slideshowLabel)) ? $slideshowLabel : Yii::t('amoscore',
              'Mostra introduzione'),
            'url' => '#',
            'options' => ['onclick' => new JsExpression('$("#amos-slideshow").modal("show");')]
          ],
          '<li class="divider"></li>',
        ];
      } else {
        $itemsSlideshow = '<li class="divider"></li>';
      }

      /* Configuration of Slideshow - end  */
      /* Configurazione translation */
      $headerMenu = new \open20\amos\core\views\common\HeaderMenu();
      $menuTranslation = $headerMenu->getTranslationField();

      //if there is information page for policy or cookies - display link at the end of menu
      $hasPrivacyLink = isset(\Yii::$app->params['privacyLink']);
      $privacyLink = null;
      if ($hasPrivacyLink) {
        $privacyLink = \Yii::$app->params['privacyLink'];
      }
      $hasCookiesLink = isset(\Yii::$app->params['cookiesLink']);
      $cookiesLink = null;
      if ($hasCookiesLink) {
        $cookiesLink = \Yii::$app->params['cookiesLink'];
      }


      $esclusioni = ['faq', 'fulmine']; //Moduli da escludere per la gestione dei widget

      /** @var UserProfile $model */
      $model = CurrentUser::getUserProfile();
      $url = $model->getAvatarUrl('original');
      Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
      $roundImage = Yii::$app->imageUtility->getRoundImage($model);
      $imgAvatar = Html::img($url,
          [
            'class' => $roundImage['class'],
            'style' => "margin-left: " . $roundImage['margin-left'] . "%; margin-top: " . $roundImage['margin-top'] . "px;",
            'alt' => Yii::t('amoscore', 'Avatar dell\'utente, apertura menu')
      ]);

      $items = [
        '<li class="dropdown-header col-xs-12"><div class="container-round-img-xs">' . $imgAvatar . '</div><p>' . $benvenuto . '</p></li>',
        '<li class="divider"></li>',
      ];

      $items[] = $this->disablePlatformLinks ? '' : ['label' => \Yii::t('amoscore', 'Il mio profilo'),
        'url' => ['/admin/user-profile/update-profile', 'id' => CurrentUser::getUserProfile()->id],
        'linkOptions' => ['title' => \Yii::t('amoscore', 'il mio profilo')],
      ];

      $items[] = [
        'label' => \Yii::t('amoscore', 'Esci'),
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post', 'title' => \Yii::t('amoscore', 'esci')]
      ];

      if (!$this->disablePlatformLinks) {
        $settingsItemsElements = [];
        $atLeastOneSettingsElement = false;

        if (\Yii::$app->controller instanceof \open20\amos\dashboard\controllers\base\DashboardController) {
          $atLeastOneSettingsElement = true;
          $settingsItemsElements[] = [
            'label' => \Yii::t('amoscore', 'Ordinamenti dashboard'),
            'url' => 'javascript:void(0);',
            'options' => ['class' => 'enable_order', 'onclick' => new JsExpression('Dashboard.enableDashboardsSortable()')],
            'linkOptions' => ['title' => \Yii::t('amoscore', 'Ordinamenti dashboard')]
          ];
        }

        if (\Yii::$app->controller->module->id == AmosDashboard::getModuleName() && Yii::$app->user->can('CAN_MANAGE_DASHBOARD')) {
          $atLeastOneSettingsElement = true;
          $settingsItemsElements[] = [
            'label' => \Yii::t('amoscore', 'Gestisci widget'),
            'url' => ['/dashboard/manager',
              'dashboard' => isset(\Yii::$app->controller->module) && \Yii::$app->controller->module->id != 'dashboard' && !in_array(\Yii::$app->controller->module->id, $esclusioni) ? \Yii::$app->controller->module->id : 'primary',
              'slide' => 0
            ],
            'linkOptions' => ['title' => \Yii::t('amoscore', 'gestisci widget')]
          ];
        }

        if ($atLeastOneSettingsElement) {
          $items[] = ('<li class="divider"></li>' .
            '<li class="dropdown-header">' .
            AmosIcons::show('settings',
              [
                'class' => 'am-lg',
            ]) .
            BaseAmosModule::t('amoscore', 'Impostazioni') .
            '</li>' .
            '<li class="divider"></li>');
          $items = ArrayHelper::merge($items, $settingsItemsElements);
        }
      }


      $items[] = $this->disablePlatformLinks ? '' : (($hasSlideshow) ? '<li class="divider"></li>' : '');

      $items[] = ($hasSlideshow) ? ($itemsSlideshow[1]) : '';

      $items[] = ($hasPrivacyLink || $hasCookiesLink) ?
        '<li class="divider"></li><li class="dropdown-header">' .
        AmosIcons::show('info-outline', [
          'class' => 'am-lg',
        ]) .
        Yii::t('amoscore', 'Informative') . '</li><li class="divider"></li>' : '';

      $items[] = ($hasPrivacyLink) ? $privacyLink : '';
      $items[] = ($hasCookiesLink) ? $cookiesLink : '';
      $items[] = '<li class="divider"></li>';
      // Impostare nel params dell'applicazione la versione, per esempio 'versione' => '1.0',
      $items[] = '<li class="dropdown-header pull-right">' . Yii::t('amoscore', 'Versione') . ' ' . ((isset(\Yii::$app->params['versione'])) ? \Yii::$app->params['versione'] : '0.1') . '</li>';

      $menuItems = [
        [
          'label' => AmosIcons::show('menu',
            [
              'class' => 'am-2',
              'title' => 'menu'
          ]),
          'items' => $items,
          'options' => ['class' => 'hamburger context-menu hidden-sm hidden-md hidden-lg'],
          'linkOptions' => ['title' => 'azioni utente']
        ],
      ];
    }
    //Add menu of translation
    if (!empty($menuTranslation)) {
      $menuItems[] = $menuTranslation;
    }
    /**
     * search button
     * check params from platform/backend/config/params.php
     */
    if (isset(\Yii::$app->params['searchNavbar']) && \Yii::$app->params['searchNavbar']) {
      $urlSearch = '/search/search/index';
      $search = [
        'label' => AmosIcons::show('search'),
        'items' => [
          '<li><div class="search-bar-toggle">'
          . '<form class="amosGlobalSearch-js" action="">' //used to trigger evenbt submit search form
          . '<input class="form-control" type="text" name="queryString" />'
          . '<button id="btn-search-submit" class="btn btn-navigation-primary" type="submit">' . AmosIcons::show('search') . '<span class="sr-only">' . Module::t('amoslayout',
            '#search') . '</span></button>'
          . '</form>'
          . '<p>' . Module::t('amoslayout', '#search_description') . '</p>'
          . '<a id="show-advanced-search" href="/search/search?advancedSearch=1">' . Module::tHtml('amossearch', 'Ricerca avanzata') . '</a>'
          . '</div></li>'
        ],
        'options' => [
          'class' => 'btn-toggle-search',
        ],
        'linkOptions' => ['title' => Module::t('amoslayout', '#search')]
      ];
      $menuItems[] = $search;
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
          $menuItems[] = $ticketsLink;	
      }
  }

    if (\Yii::$app->getModule('chat')) {
      $widget = new \open20\amos\chat\widgets\icons\WidgetIconChat();
      $bulletCount = $widget->getBulletCount();
      
      $chatLink = Html::tag(
        'li',
        Html::a(
          AmosIcons::show('comments-o', [], 'dash') . "<span class='badge'>" . (($bulletCount > 0 ) ? $bulletCount : "" ) . "</span>",
          '/messages',
          ['title' => \open20\amos\chat\AmosChat::t('amoschat', 'Messaggi privati')]
        ), 
        ['class' => 'header-plugin-icon']
      );
      $menuItems[] = $chatLink;
    }
    


    if (\Yii::$app->getModule('myactivities') && !\Yii::$app->user->isGuest) {
      $widget = new \open20\amos\myactivities\widgets\icons\WidgetIconMyActivities();
        if (isset(\Yii::$app->params['disableBulletCounters']) && (\Yii::$app->params['disableBulletCounters'] === true)) {
            $bulletCount = 0;
        }
        else {
            $bulletCount =  $widget->getBulletCount();
        }
      
      $chatLink = Html::tag('li',
          Html::a(
            AmosIcons::show('bell', [], 'dash') . "<span class='badge'>" . (($bulletCount > 0 ) ? $bulletCount : "" ) . "</span>"
            , '/myactivities/my-activities/index',
            ['title' => \open20\amos\myactivities\AmosMyActivities::t('amosmyactivities',
                'My activities')]
          ), ['class' => 'header-plugin-icon']
      );
      $menuItems[] = $chatLink;
    }

    /**
     * link to frontend
     * check params from platform/backend/config/params.php
     */
    if (isset(\Yii::$app->params['toFrontendLink']) && \Yii::$app->params['toFrontendLink']) {
      /**
       * get params from platform/common/config/params-local.php
       */
      $frontendLink = Html::tag('li',
          Html::a(
            AmosIcons::show('globe-alt')
            , Url::to(\Yii::$app->params['platform']['frontendUrl']),
            ['title' => Yii::t('amoscore', 'frontend'), 'target' => '_blank']
          ), ['class' => 'toFrontend']
      );
      $menuItems[] = $frontendLink;
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
            AmosIcons::show('apps')
            , Url::to(['/dashboard']), ['title' => Yii::t('amoscore', '#to_dashboard_link_text')]
          ), ['class' => 'toDashboard']
      );
      $menuItems[] = $dashboardLink;
    } /* end link dashboard */


    return Nav::widget([
        'options' => [
          'class' => 'navbar-nav navbar-right hidden-lg hidden-md hidden-sm',
        ],
        'encodeLabels' => false,
        'dropDownCaret' => '',
        'items' => $menuItems,
    ]);
  }

}
