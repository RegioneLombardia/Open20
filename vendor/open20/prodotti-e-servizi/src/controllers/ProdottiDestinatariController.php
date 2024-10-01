<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\controllers 
 */
 
namespace open20\prodottiservizi\controllers;

use open20\cms\dashboard\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use Yii;

/**
 * Class ProdottiDestinatariController 
 * This is the class for controller "ProdottiDestinatariController".
 * @package open20\prodottiservizi\controllers
 */
class ProdottiDestinatariController extends \open20\prodottiservizi\controllers\base\ProdottiDestinatariController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $get = Yii::$app->request->get();

        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosdashboards', 'Destinatari');
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
            $titleSection = Module::t('amosdashboards', 'Gestisci Destinatari');

            $subTitleSection = Html::tag(
                'p',
                Module::t(
                    'amosdashboards',
                    '#destinatarisubtitle'
                )
            );
        }

        $labelCreate = Module::t('amosdashboards', 'Nuovo');
        $titleCreate = Module::t('amosdashboards', 'Crea nuovo elemento');
        $labelManage = Module::t('amosdashboards', 'Gestisci');
        $titleManage = Module::t('amosdashboards', 'Gestisci la sidebar');
        $urlCreate   = '/prodotti-e-servizi/prodotti-destinatari/create';
        $urlManage   = null;

        $this->view->params = [
            #'breadcrumbs' => $test,
            'isGuest' => \Yii::$app->user->isGuest,
            #'modelLabel' => 'Sidebar',
            'titleSection' => $titleSection,
            #'hideCreate' => true,
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
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['PRODOTTIDESTINATARI_ADMIN'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     *
     * @return array
     */
    public static function getManageLinks() {
        if(Yii::$app->user->can('PRODOTTISERVIZI_ADMIN')) {
            $links[] = [
                'title' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Amministra i prodotti e servizi'),
                'label' => Module::t('prodottiservizi', 'Amministra Prodotti e Servizi'),
                'url' => '/prodotti-e-servizi/prodotti-servizi/amministra'
            ];
            $links[] = [
                'title' => Module::t('prodottiservizi', 'Gestisci le soluzioni'),
                'label' => Module::t('prodottiservizi', 'Gestisci Soluzioni'),
                'url' => '/prodotti-e-servizi/prodotti-soluzioni/index'
            ];
            $links[] = [
                'title' => Module::t('prodottiservizi', 'Gestisci le finalità'),
                'label' => Module::t('prodottiservizi', 'Gestisci Finalità'),
                'url' => '/prodotti-e-servizi/prodotti-finalita/index'
            ];
        }
        return $links;
    }
}
