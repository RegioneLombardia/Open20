<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\controllers
 * @category   CategoryName
 */

namespace frontend\modules\ticket\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\search\TicketCategorieSearch;
use open2\amos\ticket\models\TicketCategorie;
use open2\amos\ticket\utility\TicketUtility;
use open20\amos\admin\AmosAdmin;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;


/**
 * Class AssistenzaController
 *
 * @property \open2\amos\ticket\models\TicketCategorie $model
 * @property \open2\amos\ticket\models\search\TicketCategorieSearch $modelSearch
 *
 * @packageopen2\amos\ticket\controllers
 */
class AssistenzaController extends  \open2\amos\ticket\controllers\AssistenzaController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'cerca-faq',
                        ],
                        'roles' => ['?']
                    ],
                ]
            ],
        ]);
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosTicket::t('amosticket', 'Cerca FAQ');
            $urlLinkAll   = '';

            $labelSigninOrSignup = AmosTicket::t('amosticket', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosTicket::t(
                'amosticket',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosTicket::t('amosticket', '#beforeActionCtaLogin');
            $titleSignin = AmosTicket::t(
                'amosticket',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );

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
                AmosTicket::t(
                    'amosticket',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosTicket::t('amosticket', 'Cerca FAQ');
            $labelLinkAll = AmosTicket::t('amosticket', 'Tutte le FAQ');
            $urlLinkAll   = '/ticket/ticket-faq/index';
            $titleLinkAll = AmosTicket::t('amosticket', 'Visualizza tutte le FAQ');
            $subTitleSection = Html::tag('p', AmosTicket::t('amosticket', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = AmosTicket::t('amosticket', 'Nuova');
        $titleCreate = AmosTicket::t('amosticket', 'Crea una nuova FAQ');
        $urlCreate   = '/ticket/ticket-faq/create';
        $labelManage = AmosTicket::t('amosticket', 'Gestisci');
        $titleManage = AmosTicket::t('amosticket', 'Gestisci le FAQ');

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'ticket',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'urlCreate' => $urlCreate,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new TicketCategorie());
        $this->setModelSearch(new TicketCategorieSearch());

        $this->setAvailableViews([
            /* 'grid' => [
                 'name' => 'grid',
                 'label' => Yii::t('amoscore', '{iconaTabella}' . Html::tag('p', Yii::t('amoscore', 'Table')), [
                     'iconaTabella' => AmosIcons::show('view-list-alt')
                 ]),
                 'url' => '?currentView=grid'
             ],*/]);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function actionIndex($layout = null)
    {
        return $this->redirect(['/ticket/assistenza/cerca-faq']);
    }

    /**
     * @param int|null $categoriaSelezionataId
     * @return string
     */
    public function actionCercaFaq($categoriaSelezionataId = null)
    {
        Url::remember();


        Yii::$app->session->set(AmosTicket::beginCreateNewSessionKey(), Url::previous());
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->view->params['titleSection'] = AmosTicket::t('amosticket', 'Cerca le FAQ');
        if (Yii::$app->getUser()->can('REFERENTE_TICKET') || Yii::$app->getUser()->can('AMMINISTRATORE_TICKET')) {
                $this->view->params['labelLinkAll'] =AmosTicket::t('amosticket', 'Tutte le FAQ');
                $this->view->params['urlLinkAll']   = '/ticket/ticket-faq/index';
                $this->view->params['titleLinkAll'] = AmosTicket::t('amosticket', 'Visualizza tutte le FAQ');
        }else{
                $this->view->params['urlLinkAll']   ='';
        }

        $categoriaSelezionata = null;
        if ($categoriaSelezionataId) {
            $categoriaSelezionata = TicketCategorie::findOne($categoriaSelezionataId);
        }

        $ticketCategorieArray = $this->getFratelliConPadre($categoriaSelezionataId);
        $ticketCategorieArray = array_reverse($ticketCategorieArray);
        
        $this->view->params['forceBreadcrumbs'][] = [
               'label' => AmosTicket::t('amosticket', 'FAQ'),
               'url' => '/ticket/ticket/index',
               'route' => '/ticket/ticket/index',
        ];
        $this->view->params['forceBreadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Cerca le FAQ')];

        return $this->render('cerca_faq', [
            'ticketCategorieArray' => $ticketCategorieArray,
            'model' => $this->getModelSearch(),
            'categoriaSelezionata' => $categoriaSelezionata,
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }


    /**
     * @return array
     */
    public static function getManageLinks()
    {
        return TicketUtility::getManageLink();
    }
}
