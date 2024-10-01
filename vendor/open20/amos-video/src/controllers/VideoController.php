<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione\controllers 
 */
 
namespace open20\video\controllers;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\video\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use open20\video\assets\ModuleVideoAsset;
use Yii;

/**
 * Class FormazioneController 
 * This is the class for controller "FormazioneController".
 * @package frontend\modules\formazione\controllers 
 */
class VideoController extends \open20\video\controllers\base\VideoController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $get = \Yii::$app->request->get();

        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosdashboards', 'Video');
            $urlLinkAll   = '';

            $labelSigninOrSignup = Module::t('amosdashboards', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = Module::t(
                'amosdashboards',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = Module::t('amosdashboards', '#beforeActionCtaLogin');
            $titleSignin = Module::t(
                'amosdashboards',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );
            /*
            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                $labelLink,
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]
            );
            $subTitleSection  = Html::tag(
                'p',
                Module::t(
                    'amosdashboards',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );*/
        } else {
            $titleSection = Module::t('amosdashboards', 'Gestisci Video');

        /*    
            $labelLinkAll = Module::t('amosdashboards', 'Tutti i video');
            $urlLinkAll   = '/video/video/all';
            $titleLinkAll = Module::t('amosdashboards', 'Visualizza la lista dei video'); */
            
            $subTitleSection = Html::tag(
                'p',
                Module::t(
                    'prodottiservizi',
                    '#prodottiservizisubtitle'
                )
            );
        }

        $labelCreate = Module::t('amosdashboards', 'Nuovo');
        $titleCreate = Module::t('amosdashboards', 'Crea nuovo elemento');
        $labelManage = Module::t('amosdashboards', 'Gestisci');
        $titleManage = Module::t('amosdashboards', 'Gestisci i video');
        $urlCreate   = '/video/video/create';
        $urlManage   = null;

        $this->view->params = [
            #'breadcrumbs' => $test,
            'isGuest' => \Yii::$app->user->isGuest,
            #'modelLabel' => 'Sidebar',
            'titleSection' => $titleSection,
            'hideCreate' => \Yii::$app->user->isGuest,
            #'subTitleSection' => $subTitleSection,
            #'urlLinkAll' => $urlLinkAll,
            #'labelLinkAll' => $labelLinkAll,
            #'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        // Lasciare qui questo if e return perché se no va in loop...
        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'view',
                            'all'
                        ],
                        'allow' => true,
                        'roles' => ['VIDEO_ADMIN'],
                    ],
                    [
                        'actions' => [
                            'view',
                            'all'
                        ],
                        'allow' => true,
                        'roles' => ['VIDEO_READER'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }
    
    
    public function actionIndex($layout = NULL) {
        $labelLinkAll = Module::t('amosdashboards', 'Tutti i video');
        $urlLinkAll   = '/video/video/all';
        $titleLinkAll = Module::t('amosdashboards', 'Visualizza la lista dei video'); 
        $this->view->params['labelLinkAll'] = $labelLinkAll;
        $this->view->params['urlLinkAll'] = $urlLinkAll;
        $this->view->params['titleLinkAll'] = $titleLinkAll;
        #'urlLinkAll' => $urlLinkAll,
            #'labelLinkAll' => $labelLinkAll,
            #'titleLinkAll' => $titleLinkAll,
        return parent::actionIndex($layout);
    }

    public function actionAll($layout=null) {
        Url::remember();
        ModuleVideoAsset::register(Yii::$app->view);
        $this->setUpLayout('list');
        // Setto solo la view che interessa
        $this->setAvailableViews([
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . \open20\amos\core\helpers\Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),
                'url' => '?currentView=icon'
            ]
        ]);
        $this->setCurrentView($this->viewIcon);
        $this->setDataProvider($this->modelSearch->search(\Yii::$app->request->getQueryParams()));
        $this->view->params['titleSection'] = Module::t('amosformazione', 'Tutti i video');
        return $this->render('index', [
            'model' => $this->getModelSearch(),
            'dataProvider' => $this->getDataProvider(),
            'currentView' => $this->getCurrentView()
        ]);
    }

    /**
     * @return array
     */
    public static function getManageLinks() {
        $links = [];

        if(\Yii::$app->user->can('VIDEO_UPDATE')) {
            $links[] = [
                'title' => Module::t('amoscommunity', 'Gestisci i video'),
                'label' => Module::t('amoscommunity', 'Gestisci / Video'),
                'url' => '/video/video/index'
            ];
        }
        return $links;
    }
}
