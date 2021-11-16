<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\controllers\base;

use open20\amos\community\AmosCommunity;
use open20\amos\community\assets\AmosCommunityAsset;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use Yii;
use yii\helpers\Url;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class CommunityController
 * CommunityController implements the CRUD actions for Community model.
 * @package open20\amos\community\controllers\base
 */
class CommunityController extends CrudController
{
    /**
     * Uso il trait per inizializzare la dashboard a tab
     */
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'list';
    
    /**
     * @var AmosCommunity|null $communityModule
     */
    public $communityModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(AmosCommunity::instance()->createModel('Community'));
        $this->setModelSearch(AmosCommunity::instance()->createModel('CommunitySearch'));

        AmosCommunityAsset::register(Yii::$app->view);
    
        $this->communityModule = Yii::$app->getModule(AmosCommunity::getModuleName());
    
        $this->viewIcon = [
            'name' => 'icon',
            'label' => AmosIcons::show('view-list') . Html::tag('p', AmosCommunity::t('amoscommunity', 'Card')),
            'url' => '?currentView=icon'
        ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosCommunity::t('amoscommunity', 'Table')),
            'url' => '?currentView=grid'
        ];
    
        $this->forceDefaultViewType = $this->communityModule->forceDefaultViewType;
        $defaultViews = [
            'icon' => $this->viewIcon,
            'grid' => $this->viewGrid,
        ];
        $availableViews = [];
        foreach ($this->communityModule->defaultListViews as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }
    
        $this->setAvailableViews($availableViews);

        parent::init();

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->view->pluginIcon = 'ic ic-community';
        }

        $this->setUpLayout('list');
    }

    /**
     * Lists all Community models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        $this->setUpLayout('list');

        Url::remember();
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single Community model.
     * @param integer $id
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id, $tabActive = null)
    {
        $community = Community::basicFind()->andWhere(['id' => $id])->one();
        if (!is_null($community) && (strlen($community->deleted_at) > 0)) {
            return $this->redirect(['deleted-community', 'id' => $id]);
        }
        Url::remember();

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            Yii::$app->view->params['CommunityParams']['outsideCommunity'] = true;
            $this->setUpLayout('main_community');

            $moduleCwh = \Yii::$app->getModule('cwh');
            if (isset($moduleCwh)) {
                $moduleCwh->setCwhScopeInSession([
                    'community' => $id,
                ],
                    [
                        'mm_name' => 'community_user_mm',
                        'entity_id_field' => 'community_id',
                        'entity_id' => $id
                    ]);
            }
        }else {
            $this->setUpLayout('main');
        }

        $this->model = $this->findModel($id);
        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', [
                'model' => $this->model,
                'tabActive' => $tabActive,
            ]);
        }
    }


    /**
     * Creates a new Community model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        Yii::$app->view->params['textHelp']['filename'] = 'create_community_dashboard_description';
        $model = new Community;
        $model->context = Community::className();

        $parentId = Yii::$app->request->getQueryParam('parentId');
        if(!is_null($parentId)){
            $model->parent_id = $parentId;
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if (Yii::$app->getModule('community')->forceWorkflow($model)) {
                $model->validated_once = 1;
            }
            $validateOnSave = true;
            if($model->status == Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE){
                $model->status = Community::COMMUNITY_WORKFLOW_STATUS_DRAFT;
                $model->save();
                $model->status = Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE;
                $validateOnSave = false;
            }
            if($model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED){
                $model->status = Community::COMMUNITY_WORKFLOW_STATUS_DRAFT;
                $model->save();
                $model->status = Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED;
                $validateOnSave = false;
            }
            if(!empty($model->status) && $model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED){
                $model->validated_once = 1;
            }
            if ($model->save($validateOnSave)) {
                $model->saveDashboardCommunity();
                //the loggerd user creating community will be automatically a participant of the community with role community manager
                $loggedUserId = Yii::$app->getUser()->getId();
                $userCommunity = new CommunityUserMm();
                $userCommunity->community_id = $model->id;
                $userCommunity->user_id = $loggedUserId;
                $userCommunity->status = CommunityUserMm::STATUS_ACTIVE;
                $userCommunity->role = CommunityUserMm::ROLE_COMMUNITY_MANAGER;
                // add cwh auth-assignment permission for community/user
                $model->setCwhAuthAssignments($userCommunity);
                $ok = $userCommunity->save(false);
                $this->addFlash('success',
                    AmosCommunity::t('amoscommunity', 'Community created successfully.'));
                return  $this->redirectOnCreate($model);
            } else {
                $this->addFlash('danger',
                    AmosCommunity::t('amoscommunity', 'Community not created. Please, check data entry.'));
                return $this->render('create', [
                    'model' => $model,
                    'fid' => null,
                    'dataField' => null,
                    'dataEntity' => null,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
            ]);
        }
    }

    /**
     * Creates a new Community model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $model = new Community;

        if (\Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    return json_encode($model->toArray());
                } else {
                    return $this->renderAjax('_formAjax', [
                        'model' => $model,
                        'fid' => $fid,
                        'dataField' => $dataField
                    ]);
                }
            }
        } else {
            return $this->renderAjax('_formAjax', [
                'model' => $model,
                'fid' => $fid,
                'dataField' => $dataField
            ]);
        }
    }

    /**
     * Updates an existing Community model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @param bool $visibleOnEdit
     * @param string $tabActive
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id, $visibleOnEdit = false, $tabActive = null)
    {
        Url::remember();
        $this->setUpLayout('form');

        Yii::$app->view->params['textHelp']['filename'] = 'create_community_dashboard_description';
        /** @var Community $model */
        $model = $this->findModel($id);
        $communityModule = Yii::$app->getModule('community');
        $previousStatus = $model->status;
        if ($model->load(Yii::$app->request->post())) {
            if(!$communityModule->forceWorkflow($model) && $model->backToEdit && $model->status != Community::COMMUNITY_WORKFLOW_STATUS_DRAFT && $model->status != Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE){
                if($model->validated_once) {
                    $model->status = Community::COMMUNITY_WORKFLOW_STATUS_DRAFT;
                }
            }
            if(!empty($model->status) && $model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED){
                $model->validated_once = 1;
            }

            if ($model->validate()) {
                if ($model->save()) {
                    $model->saveDashboardCommunity();
                    $this->addFlash('success', AmosCommunity::t('amoscommunity', 'Community updated successfully.'));
                    return $this->redirectOnUpdate($model, $previousStatus);
                } else {
                    $this->addFlash('danger', AmosCommunity::t('amoscommunity', 'Community not updated. Please, check data entry.'));
                }
            }
        }
        $linkreferrer = \Yii::$app->request->referrer;
        return (!empty($linkreferrer) && strpos($linkreferrer, 'myactivities') !== false) ?
                $this->redirect(\Yii::$app->request->referrer) :
                $this->render('update', [
                    'model' => $model,
                    'fid' => NULL,
                    'dataField' => NULL,
                    'dataEntity' => NULL,
                    'visibleOnEdit' => $visibleOnEdit,
                    'tabActive' => $tabActive,
        ]);
    }

    /**
     * Deletes an existing Community model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * in Community model beforeDelete is overwritten to allow deletion of related models
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $communityParentId = null)
    {
        $model = $this->findModel($id);
        if ($model) {
            try{
                $model->delete();
                $this->addFlash('success', AmosCommunity::t('amoscommunity', 'Community deleted successfully.'));
            } catch (\Exception $exception) {
                $this->addFlash('danger', $exception->getMessage());
            }
        } else {
            $this->addFlash('danger', AmosCommunity::t('amoscommunity', 'Community not found.'));
        }
        if(!empty($communityParentId)) {
            return $this->redirect(['update', 'id' => $communityParentId]);
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $model
     * @param null $previousStatus
     * @return \yii\web\Response
     */
    protected function redirectOnUpdate($model, $previousStatus = null){
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page
        if($model->status == Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE) {
            if(Yii::$app->getUser()->can('COMMUNITY_VALIDATOR')) {
                return $this->redirect(['/community/community/update', 'id' => $model->id]);
            } else {
                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
            }
        } else {
            return $this->redirect(['/community/community/update', 'id' => $model->id]);
        }
//        $redirectToUpdatePage = false;
//        if(Yii::$app->getUser()->can('COMMUNITY_UPDATE', ['model' => $model])) {
//            $redirectToUpdatePage = true;
//        }
//        if(Yii::$app->getUser()->can(Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED)) {
//            $redirectToUpdatePage = true;
//        }
//        if($redirectToUpdatePage)
//        {
//            if($model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED) {
//                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
//            }
//            elseif (($model->status == Community::COMMUNITY_WORKFLOW_STATUS_DRAFT ) && ($previousStatus == Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE ) ) {
//                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
//            }
//            else {
//                return $this->redirect(['/community/community/update', 'id' => $model->id]);
//            }
//        }else{
//            return $this->redirect(['/community/community/join','id' => $model->id]);
//        }
    }

    /**
     * @param $model
     * @return \yii\web\Response
     */
    protected function redirectOnCreate($model){
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page with the contents created by you
        $redirectToUpdatePage = false;

        if(Yii::$app->getUser()->can('COMMUNITY_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if(Yii::$app->getUser()->can('COMMUNITY_VALIDATOR', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if($redirectToUpdatePage)
        {
            return $this->redirect(['/community/community/update', 'id' => $model->id]);
        }
        else
        {
            return $this->redirect('/community/community/created-by-communities');
        }
    }
}
