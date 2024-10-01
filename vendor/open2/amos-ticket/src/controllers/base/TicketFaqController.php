<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\controllers\base
 * @category   CategoryName
 */

namespace open2\amos\ticket\controllers\base;

use open20\amos\core\controllers\CrudController;
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
class TicketFaqController extends CrudController
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
        $titleSection = AmosTicket::t('amosticket', 'Gestione FAQ');

        $module = \Yii::$app->getModule('ticket');
        $disableTicketManagement = (!empty($module) ? $module->disableTicketManagement : false);

        if (!$disableTicketManagement) {
            $labelLinkAll = AmosTicket::t('amosticket', 'Gestione ticket');
            if (Yii::$app->getUser()->can('REFERENTE_TICKET') || Yii::$app->getUser()->can('AMMINISTRATORE_TICKET')) {
                $urlLinkAll = '/ticket/ticket/index';
            } else {
                $urlLinkAll = '/ticket';
            }
            $labelManage = AmosTicket::t('amosticket', 'Gestisci');
            $titleManage = AmosTicket::t('amosticket', 'Gestione FAQ');
            $titleLinkAll = AmosTicket::t('amosticket', 'Visualizza la lista dei ticket');
            $hideManage = false;
        } else {
            $labelLinkAll = AmosTicket::t('amosticket', 'Tutte le FAQ');
            $urlLinkAll = '/ticket/assistenza/cerca-faq';
            $titleLinkAll = AmosTicket::t('amosticket', 'Tutte le FAQ');
            $hideManage = true;
        }

        // $subTitleSection = Html::tag('p', AmosTicket::t('amosticket', '#beforeActionSubtitleSectionLogged'));
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
            // 'subTitleSection' => $subTitleSection,
            'urlCreate' => $urlCreate,
            'labelCreate' => $labelCreate,
            'urlManage' => $urlManage,
            'hideManage' => $hideManage
        ];

        // var_dump((\Yii::$app->user->can(WidgetIconTicketFaq::class)));die;

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


        return parent::actionIndex();
    }

    /**
     * Displays a single TicketFaq model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        return $this->render('view', ['model' => $this->model]);
    }

    /**
     * Creates a new TicketFaq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $this->model = new TicketFaq();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TicketFaq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TicketFaq model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item deleted'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not found'));
        }
        return $this->redirect(['index']);
    }

    /**
     * @return array
     */
    public static function getManageLinks()
    {
        return TicketUtility::getManageLink();
    }
}
