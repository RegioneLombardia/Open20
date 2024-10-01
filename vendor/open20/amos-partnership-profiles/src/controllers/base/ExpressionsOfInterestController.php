<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\controllers\base
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\record\Record;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\partnershipprofiles\events\PartnershipProfilesWorkflowEvent;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\utility\ExpressionsOfInterestUtility;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesUtility;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard;
use Yii;
use yii\base\Event;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Class ExpressionsOfInterestController
 * ExpressionsOfInterestController implements the CRUD actions for ExpressionsOfInterest model.
 *
 * @property \open20\amos\partnershipprofiles\models\ExpressionsOfInterest $model
 * @property \open20\amos\partnershipprofiles\models\search\ExpressionsOfInterestSearch $modelSearch
 *
 * @package open20\amos\partnershipprofiles\controllers\base
 */
class ExpressionsOfInterestController extends CrudController
{
    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @var Module $partnerProfModule
     */
    public $partnerProfModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->partnerProfModule = Yii::$app->getModule(Module::getModuleName());

        $this->setActionFromUrl();

        $this->initDashboardTrait();

        $this->setModelObj($this->partnerProfModule->createModel('ExpressionsOfInterest'));
        $this->setModelSearch($this->partnerProfModule->createModel('ExpressionsOfInterestSearch'));

        $this->viewList = [
            'name' => 'list',
            'label' => AmosIcons::show('view-list') . Html::tag('p', Module::tHtml('amospartnershipprofiles', 'List')),
            'url' => '?currentView=list'
        ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::tHtml('amospartnershipprofiles', 'Table')),
            'url' => '?currentView=grid'
        ];

        $defaultViews = [
            'list' => $this->viewList,
            'grid' => $this->viewGrid,
        ];

        $availableViews = [];

        foreach ($this->partnerProfModule->defaultListViewsExprOfInt as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }

        $this->setAvailableViews($availableViews);

        parent::init();

        $this->layout = 'main';
        $this->setUpLayout();
    }

    /**
     * This method save the actual action id in a class variable using the pathInfo.
     */
    protected function setActionFromUrl()
    {
        $splittedPathInfo = explode('/', Yii::$app->request->pathInfo);
        $this->actionByUrl = end($splittedPathInfo);
    }

    /**
     * This method checks if must be set the custom module.
     */
    protected function setCustomModule()
    {
        $this->customModule = ExpressionsOfInterest::EXPRESSIONSOFINTEREST;
    }

    /**
     * Listen events. To call only when the model of the controller is instanced.
     */
    protected function listenEvents()
    {
        Event::on(
            $this->partnerProfModule->model('ExpressionsOfInterest'),
            'afterChangeStatusFrom{' . ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_ACTIVE . '}to{' . ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE . '}',
            [new PartnershipProfilesWorkflowEvent(), 'updatePartnershipProfileStatus'],
            $this->model
        );
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->view->title = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
        $this->view->params['titleSection'] = $pageTitle;
        }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('amospartnershipprofiles', 'Create new expression of interest'),
            'layout' => '' // To always hide create new button. Create allowed only from partnership profile.
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     * @param bool $setCurrentDashboard
     */
    protected function setListViewsParams($setCurrentDashboard = true, $child_of = null)
    {
        $this->child_of = (($child_of === null) ? WidgetIconExpressionsOfInterestDashboard::className() : $child_of);
        $this->setCreateNewBtnLabel();
        $this->setUpLayout('list');
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        }
        Yii::$app->session->set(Module::beginCreateNewSessionKeyExprOfInt(), Url::previous());
        Yii::$app->session->set(Module::beginCreateNewSessionKeyExprOfIntDateTime(), date("Y-m-d H:i:s"));
    }

    /**
     * @param ExpressionsOfInterest $model
     * @return string
     */
    public function viewReadAllBtn($model)
    {
        return (Yii::$app->user->can('EXPRESSIONSOFINTEREST_READ', ['model' => $model]));
    }

    /**
     * This method returns the close url for close button in action view.
     * @return string
     */
    public function getViewCloseUrl()
    {
        return PartnershipProfilesUtility::getActionsRedirectLink(Yii::$app->session->get(Module::beginCreateNewSessionKeyExprOfInt()));
    }

    /**
     * This method returns the close url for close button in form.
     * @return string
     */
    public function getFormCloseUrl()
    {
        return PartnershipProfilesUtility::getActionsRedirectLink(Yii::$app->session->get(Module::beginCreateNewSessionKeyExprOfInt()));
    }

    /**
     * Lists all ExpressionsOfInterest models.
     * @param string|null $layout
     * @return string
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(Module::t('amospartnershipprofiles', 'All expressions of interest'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }
        return parent::actionIndex();
    }

    /**
     * Displays a single ExpressionsOfInterest model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws \HttpInvalidParamException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        /** @var ExpressionsOfInterest model */
        $this->model = $this->findModel($id);

        $this->model->setScenario(ExpressionsOfInterest::SCENARIO_VIEW);
        $this->model->detachBehavior('cwhBehavior');
        $this->listenEvents();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save(false)) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new ExpressionsOfInterest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int|null $partnership_profile_id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionCreate($partnership_profile_id = null)
    {
        /** @var PartnershipProfiles $partnershipProfilesModel */
        $partnershipProfilesModel = $this->partnerProfModule->createModel('PartnershipProfiles');

        // START Check permission
        $partnershipProfile = $partnershipProfilesModel::findOne($partnership_profile_id);
        if (empty($partnershipProfile)) {
            throw new InvalidParamException();
        }
        $ids = PartnershipProfilesUtility::getOwnInterestPartnershipProfiles(true);
        if (!($partnershipProfile->expressionOfInterestAllowed($ids))) {
            throw new ForbiddenHttpException();
        }
        // END Check permission

        if (empty($partnership_profile_id)) {
            Yii::$app->getSession()->addFlash('danger', Module::tHtml('amospartnershipprofiles', 'Missing partnership profile id'));
            return $this->redirect(PartnershipProfilesUtility::getActionsRedirectLink(Yii::$app->session->get(Module::beginCreateNewSessionKeyExprOfInt())));
        }

        $this->setUpLayout('form');
        $this->model = $this->partnerProfModule->createModel('ExpressionsOfInterest');
        $this->model->partnership_profile_id = $partnership_profile_id;

        /** @var PartnershipProfiles $partnershipProfilesModel */
        $partnershipProfilesModel = $this->partnerProfModule->createModel('PartnershipProfiles');

        $partnershipProfile = $partnershipProfilesModel::findOne($partnership_profile_id);

        if ($this->model->load(Yii::$app->request->post())) {
            $moduleCwh = Yii::$app->getModule('cwh');
            if (!is_null($moduleCwh)) {
                $splitUserNetworkReference = explode('-', $this->model->user_network_reference);
                /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
                $cwhConfig = \open20\amos\cwh\models\CwhConfig::findOne(['tablename' => $splitUserNetworkReference[0]]);
                if (!is_null($cwhConfig)) {
                    $this->model->user_network_reference_classname = $cwhConfig->classname;
                    $this->model->user_network_reference_id = $splitUserNetworkReference[1];
                }
            }
            if ($this->model->validate()) {
                $validateOnSave = true;
                if ($this->model->status == ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE) {
                    $this->model->status  = ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT;
                    $this->model->save();
                    $this->model->status  = ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_ACTIVE;
                    $this->model->save();
                    $this->model->status  = ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE;
                    $validateOnSave = false;
                }

                if ($this->model->status == ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT) {
                    $this->model->status  = ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT;
                    $this->model->save();
                    $this->model->status  = ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_ACTIVE;
                    $this->model->save();
                    $this->model->status  = ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE;
                    $this->model->save();
                    $this->model->status  = ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT;
                    $validateOnSave = false;
                }
                if ($this->model->save($validateOnSave)) {
                    Yii::$app->getSession()->addFlash('success', Module::tHtml('amospartnershipprofiles', 'Element successfully created.'));
                    return $this->redirect(['update', 'id' => $this->model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', Module::tHtml('amospartnershipprofiles', 'Element not created, check the data entered.'));
                }
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'partnershipProfile' => $partnershipProfile,
        ]);
    }

    /**
     * Updates an existing ExpressionsOfInterest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);
        /** @var Record $userNetworkReferenceClassName */
        $userNetworkReferenceClassName = $this->model->user_network_reference_classname;
        if ($userNetworkReferenceClassName) {
            $this->model->user_network_reference = $userNetworkReferenceClassName::tableName() . '-' . $this->model->user_network_reference_id;
        }
        $partnershipProfile = $this->model->partnershipProfile;
        $this->listenEvents();
        if ($this->model->load(Yii::$app->request->post())) {
            $moduleCwh = Yii::$app->getModule('cwh');
            if (!is_null($moduleCwh)) {
                $splitUserNetworkReference = explode('-', $this->model->user_network_reference);
                /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
                $cwhConfig = \open20\amos\cwh\models\CwhConfig::findOne(['tablename' => $splitUserNetworkReference[0]]);
                if (!is_null($cwhConfig)) {
                    $this->model->user_network_reference_classname = $cwhConfig->classname;
                    $this->model->user_network_reference_id = $splitUserNetworkReference[1];
                }
            }
            if ($this->model->validate()) {
                if ($this->model->save()) {
                    Yii::$app->getSession()->addFlash('success', Module::tHtml('amospartnershipprofiles', 'Element successfully updated.'));
                    if (Yii::$app->user->can('EXPRESSIONSOFINTEREST_UPDATE', ['model' => $this->model])) {
                        return $this->redirect(['update', 'id' => $this->model->id]);
                    } else {
                        return $this->redirect(PartnershipProfilesUtility::getActionsRedirectLink(Yii::$app->session->get(Module::beginCreateNewSessionKeyExprOfInt())));
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger', Module::tHtml('amospartnershipprofiles', 'Element not updated, check the data entered.'));
                }
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'partnershipProfile' => $partnershipProfile,
        ]);
    }

    /**
     * Deletes an existing ExpressionsOfInterest model.
     * If deletion is successful, the browser will be redirected to the previous list page.
     * @param int $id
     * @return \yii\web\Response
     * @throws \Exception
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
                Yii::$app->getSession()->addFlash('success', Module::t('amospartnershipprofiles', 'Expression of interest deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amospartnershipprofiles', 'You are not authorised to delete this expression of interest.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::tHtml('amospartnershipprofiles', 'Expression of interest not found.'));
        }
        return $this->redirect(Yii::$app->session->get(Module::beginCreateNewSessionKeyExprOfInt()));
    }

    /**
     * @return array
     */
    public function getReferenceCommunityOrOrganizationList()
    {
        return ExpressionsOfInterestUtility::getReferenceCommunityOrOrganizationList();
    }

    /**
     * @return bool
     */
    public function viewCommunityOrOrganizationList()
    {
        return ExpressionsOfInterestUtility::viewCommunityOrOrganizationList();
    }
}
