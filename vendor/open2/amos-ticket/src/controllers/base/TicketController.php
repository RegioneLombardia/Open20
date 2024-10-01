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
use open2\amos\ticket\models\search\TicketSearch;
use open2\amos\ticket\models\Ticket;
use Yii;
use yii\helpers\Url;

/**
 * Class TicketController
 * TicketController implements the CRUD actions for Ticket model.
 *
 * @property \open2\amos\ticket\models\Ticket $model
 * @property \open2\amos\ticket\models\search\TicketSearch $modelSearch
 *
 * @package open2\amos\ticket\controllers\base
 */
class TicketController extends CrudController
{
    /**
     * Trait used for initialize the ticket dashboard
     */
    use TabDashboardControllerTrait;
    
    /**
     * @var AmosTicket $ticketModule
     */
    protected $ticketModule = null;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();
        $this->ticketModule = AmosTicket::instance();
        
        $this->setModelObj(new Ticket());
        $this->setModelSearch(new TicketSearch());
        
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosTicket::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);
        
        parent::init();
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $newsPageTitle Ticket page title (
     */
    protected function setTitleAndBreadcrumbs($ticketPageTitle)
    {
        //$this->setNetworkDashboardBreadcrumb();
        Yii::$app->session->set('previousTitle', $ticketPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $ticketPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $ticketPageTitle];
    }
    
    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     * @param bool $hideBtn
     */
    protected function setCreateNewBtnLabel($hideBtn = false)
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosTicket::t('amosticket', 'Create new ticket')
        ];
        if ($hideBtn) {
            $this->hideCreateNewBtn();
        }
    }
    
    /**
     * Method useful to hide the create new button.
     */
    protected function hideCreateNewBtn()
    {
        Yii::$app->view->params['createNewBtnParams']['layout'] = '';
    }
    
    /**
     * This method is useful to set all common params for all list views.
     * @param bool $setCurrentDashboard
     */
    protected function setListViewsParams($setCurrentDashboard = true, $hideCreateNewBtn = false)
    {
        $this->setCreateNewBtnLabel($hideCreateNewBtn);
        $this->setUpLayout('list');
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        }
        Yii::$app->session->set(AmosTicket::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosTicket::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }
    
    /**
     * Base operations for list views
     * @param string $pageTitle
     * @return string
     */
    protected function baseListsAction($pageTitle, $hideCreateNewBtn = false)
    {
        Url::remember();
        $this->setTitleAndBreadcrumbs($pageTitle);
        $this->setListViewsParams(true, $hideCreateNewBtn);
        $renderParams = [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ];
        return $this->render('index', $renderParams);
    }
    
    /**
     * This method returns the close url for close button in action view.
     * @return string
     */
    public function getViewCloseUrl()
    {
        return Yii::$app->session->get(AmosTicket::beginCreateNewSessionKey());
    }
    
    /**
     * @return array|string
     */
    public function standardRedirectUrl()
    {
        $sessionUrl = Yii::$app->session->get(AmosTicket::beginCreateNewSessionKey());
        if ($sessionUrl) {
            return $sessionUrl;
        } else {
            return ['/ticket/ticket/ticket-waiting'];
        }
    }
    
    /**
     * Displays a single Ticket model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $this->setUpLayout('main');
        
        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            $model_ticket_forwarded_from = ($this->model->forwarded_from_id) ? Ticket::findOne($this->model->forwarded_from_id) : null;
            $model_ticket_forwarded_to = Ticket::find()->andWhere(['forwarded_from_id' => $this->model->id])->one();
            return $this->render('view', [
                'model' => $this->model,
                'model_ticket_forwarded_from' => $model_ticket_forwarded_from,
                'model_ticket_forwarded_to' => $model_ticket_forwarded_to
            ]);
        }
    }
    
    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        $oldStatus = $this->model->status;
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->status == Ticket::TICKET_WORKFLOW_STATUS_CLOSED && $oldStatus != Ticket::TICKET_WORKFLOW_STATUS_CLOSED) {
                $this->model->closed_by = Yii::$app->getUser()->id;
                $this->model->closed_at = $this->model->updated_at;
            }
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', AmosTicket::t('amoscore', 'Item updated'));
                return $this->redirect($this->standardRedirectUrl());
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosTicket::t('amoscore', 'Item not updated, check data'));
            }
        }
        
        return $this->render('update', [
            'model' => $this->model,
        ]);
    }
    
    /**
     * Deletes an existing Ticket model.
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
                Yii::$app->getSession()->addFlash('success', AmosTicket::t('amoscore', 'Item deleted'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosTicket::t('amoscore', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosTicket::t('amoscore', 'Item not found'));
        }
        return $this->redirect($this->standardRedirectUrl());
    }
}
