<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\controllers\base
 * @category   CategoryName
 */

 namespace frontend\modules\ticket\controllers;



use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\search\TicketFaqSearch;
use open2\amos\ticket\models\TicketFaq;
use open2\amos\ticket\utility\TicketUtility;
use Yii;
use yii\helpers\Url;

/**
 * Class TicketFaqController
 * TicketFaqController implements the CRUD actions for TicketFaq model.
 *
 * @property \open2\amos\ticket\models\TicketFaq $model
 * @property \open2\amos\ticket\models\search\TicketFaqSearch $modelSearch
 *
 * @package open2\amos\ticket\controllers\base
 */
class TicketFaqController extends  \open2\amos\ticket\controllers\TicketFaqController
{
    /**
     * Trait used for initialize the ticket dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new TicketFaq());
        $this->setModelSearch(new TicketFaqSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}' . Html::tag('p', Yii::t('amoscore', 'Table')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {

        $urlCreate = '/ticket/ticket-faq/create';
        $labelCreate = AmosTicket::t('amosticket', 'Nuova');
        $titleSection = AmosTicket::t('amosticket', 'Tutte le FAQ');
        $labelLinkAll = AmosTicket::t('amosticket', 'Categorie');
        $urlLinkAll = '/ticket/ticket-faq/index';
        $labelManage = AmosTicket::t('amosticket', 'Gestisci');
        $titleManage = AmosTicket::t('amosticket', 'Gestisci le FAQ');
        $titleLinkAll = AmosTicket::t('amosticket', 'Visualizza la lista delle categorie');
        $subTitleSection = Html::tag('p', AmosTicket::t('amosticket', '#beforeActionSubtitleSectionLogged'));
        $urlManage = null;

        $this->view->params = [
            'isGuest' => false,
            'modelLabel' => 'ticket',
            'titleSection' => $titleSection,
            'titleLinkAll' => $titleLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'urlLinkAll' => $urlLinkAll,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'subTitleSection' => $subTitleSection,
            'urlCreate' => $urlCreate,
            'labelCreate' => $labelCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }


    /**
     * Lists all TicketFaq models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->view->params['titleSection'] = AmosTicket::t('amosticket', 'Tutte le FAQ');
        $this->view->params['labelLinkAll'] =AmosTicket::t('amosticket', 'Categorie');
        $this->view->params['urlLinkAll']   = '/ticket/ticket-categorie/index';
        $this->view->params['titleLinkAll'] = AmosTicket::t('amosticket', 'Visualizza la lista delle categorie');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->view->params['forceBreadcrumbs'][] = [
               'label' => AmosTicket::t('amosticket', 'FAQ'),
               'url' => '/ticket/ticket/index',
               'route' => '/ticket/ticket/index',
        ];
        $this->view->params['forceBreadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Tutte le FAQ')];
        return parent::actionIndex();
    }


    /**
     * @return array
     */
    public static function getManageLinks()
    {
        return TicketUtility::getManageLink();
    }


}
