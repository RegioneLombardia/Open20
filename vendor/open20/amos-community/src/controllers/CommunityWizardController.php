<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\controllers;

use open20\amos\community\AmosCommunity;
use open20\amos\community\components\PartsFormCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\cwh\models\CwhAuthAssignment;
use open20\amos\community\assets\AmosCommunityAsset;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class CommunityWizardController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'progress_wizard';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(new Community());
        $this->setModelSearch(new CommunitySearch());
        $this->setAvailableViews([]);

        parent::init();
        $this->setUpLayout();

        AmosCommunityAsset::register(Yii::$app->view);
    }

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
                            'introduction',
                            'details',
                            'access-type',
                            'tag',
                            'completion',
                            'new-subcommunity'
                        ],
                        'roles' => ['AMMINISTRATORE_COMMUNITY', 'COMMUNITY_CREATOR', 'COMMUNITY_CREATE']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }

    /**
     * Set view params for the creation wizard.
     */
    private function setParamsForView()
    {
        $parts = new PartsFormCommunity(['model' => $this->model]);
        Yii::$app->view->title = $parts->active['index'] . '. ' . $parts->active['label'];
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => Yii::$app->view->title]
        ];
        Yii::$app->view->params['model'] = $this->model;
        Yii::$app->view->params['partsQuestionario'] = $parts;
        Yii::$app->view->params['hideBreadcrumb'] = true; // This param hide the wizard Breadcrumb.
        Yii::$app->view->params['hidePartsUrl'] = true; // This param disable the progress wizard menu links.
        Yii::$app->view->params['textHelp'] = [
            'filename' => 'community-description'
        ];
    }

    /**
     * Redirect to creation wizard specifying the parent id of the subcommunity
     *
     * @param null $id The community from which a new subcommity will be created
     * @return \yii\web\Response
     */
    public function actionNewSubcommunity($id = null){

        return $this->redirect([
            'introduction',
            'id' => null,
            'parentId' => $id,
        ]);

    }

    /**
     * Renders Introductory step of community progress-wizard form
     *
     * @param int $id - id of community model
     * @param int|null $parentId
     * @return string|\yii\web\Response
     */
    public function actionIntroduction($id = null, $parentId = null)
    {

        $urlPrevious = Url::previous();
        Url::remember();

        if (!is_null($id)) {
            $this->model = $this->findModel($id);
        } else {
            $this->model = AmosCommunity::instance()->createModel('Community');
            $this->model->context = Community::className();
            if (!empty($parentId)) {
                // check on permission to create a community if it is under a specific domain or child of another community/organization
                $cwhModule = Yii::$app->getModule('cwh');
                $classname = Community::className();

                $permissionCreateName = $cwhModule->permissionPrefix . "_CREATE_" . $classname;
                $permissionCreate = CwhAuthAssignment::findOne([
                    'item_name' => $permissionCreateName,
                    'cwh_nodi_id' => 'community-' . $parentId,
                    'user_id' => Yii::$app->user->id
                ]);
                if (!is_null($permissionCreate)) {
                    $this->model->parent_id = $parentId;
                } else {
                    $this->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
                    return $this->redirect($urlPrevious);
                }
            }
        }

        if ($this->model->load(Yii::$app->getRequest()->post())) {
            if (!empty($parentId)) {
                return $this->redirect([
                    'details',
                    'id' => null,
                    'parentId' => $parentId
                ]);
            }
            return $this->goToNextPart();
        }

        $this->setParamsForView();

        return $this->render('introduction', [
            'model' => $this->model,
            'id' => $id,
            'parentId' => $parentId,
        ]);
    }

    /**
     * Renders Detail Information step of progress-wizard form
     *
     * In step information the user sets the community basic information
     *
     * @param int $id - id of community model
     * @param int|null $parentId
     * @return string|\yii\web\Response
     */
    public function actionDetails($id = null, $parentId = null)
    {

        Url::remember();

        $isNewCommunity = false;
        if (isset($id)) {
            $this->model = $this->findModel($id);
        } else {
            $this->model = AmosCommunity::instance()->createModel('Community');
            $this->model->context = Community::className();
            if (!empty($parentId)) {
                // check on permission to create a community if it is under a specific domain or child of another community/organization
                $cwhModule = Yii::$app->getModule('cwh');
                $classname = Community::className();

                $permissionCreateName = $cwhModule->permissionPrefix . "_CREATE_" . $classname;
                $permissionCreate = CwhAuthAssignment::findOne([
                    'item_name' => $permissionCreateName,
                    'cwh_nodi_id' => 'community-' . $parentId,
                    'user_id' => Yii::$app->user->id
                ]);
                if (!is_null($permissionCreate)) {
                    $this->model->parent_id = $parentId;
                } else {
                    $this->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
                    return $this->redirect([
                        'introduction',
                        'id' => null,
                        'parentId' => null,
                    ]);
                }
            }
            $isNewCommunity = true;
        }
        if ($this->model->load(Yii::$app->getRequest()->post())) {
            if (empty($this->model->parent_id) && !empty($parentId)) {
                $this->model->parent_id = $parentId;
            }
            if ($this->model->save(false)) {
                if ($isNewCommunity) {
                    //the loggerd user creating community will be automatically a participant of the community with role community manager
                    $loggedUserId = Yii::$app->getUser()->getId();
                    $userCommunity = new CommunityUserMm();
                    $userCommunity->community_id = $this->model->id;
                    $userCommunity->user_id = $loggedUserId;
                    $userCommunity->status = CommunityUserMm::STATUS_ACTIVE;
                    $userCommunity->role = CommunityUserMm::ROLE_COMMUNITY_MANAGER;
                    // add cwh auth-assignment permission for community/user
                    $this->model->setCwhAuthAssignments($userCommunity);
                    $ok = $userCommunity->save(false);
                }
                return $this->goToNextPart();
            }
        }

        $this->setParamsForView();

        return $this->render('details', [
            'model' => $this->model,
            'id' => $id,
            'parentId' => $parentId,
        ]);
    }

    /**
     * Renders Access Type step of progress-wizard form
     *
     * In this step the user selects the type of community (private, public or reserved)
     *
     * @param $id integer - id of community model
     * @return string|\yii\web\Response
     */
    public function actionAccessType($id)
    {

        Url::remember();

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }

        $this->setParamsForView();

        return $this->render('access-type', [
            'model' => $this->model,
        ]);
    }

    /**
     * Renders Tag step of progress-wizard form
     *
     * In step Tag the user selects the tags to relate to community
     *
     * @param $id integer - id of community model
     * @return string|\yii\web\Response
     */
    public function actionTag($id)
    {

        Url::remember();

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->getRequest()->post()) && $this->model->save(false)) {
            return $this->goToNextPart();
        }

        $this->setParamsForView();

        return $this->render('tag', [
            'model' => $this->model,
        ]);
    }

    /**
     * Renders to the final step of progress-wizard form
     *
     * @param $id integer - id of community model
     * @return string|\yii\web\Response
     */
    public function actionCompletion($id)
    {

        $this->setUpLayout('progress_wizard');

        Url::remember();

        $this->model = $this->findModel($id);

        $hideWorkflow = isset(Yii::$app->params['hideWorkflowTransitionWidget']) && Yii::$app->params['hideWorkflowTransitionWidget'];
        /** @var AmosCommunity $amosCommunity */
        $amosCommunity = Yii::$app->getModule('community');
        $hideWorkflow = $hideWorkflow || $amosCommunity->bypassWorkflow;

        if($hideWorkflow){
            return $this->redirect(['/community/community/publish', 'id' => $this->model->id, 'redirectWizard' => true]);
        }

        $status = null;
        $user = Yii::$app->getUser();

        $canValidateSubdomain = false;
        $isChild = false;
        if ($this->model->parent_id != null) {
            $isChild = true;
            $canValidateSubdomain = $user->can('COMMUNITY_VALIDATE', ['model' => $this->model]);
        }
        //if community is child check for permission validate under parent community domain
        //if community is not child check if user has validator role for community
        $canPublish = ( $canValidateSubdomain || ($user->can('COMMUNITY_VALIDATOR') && !$isChild )) ;

        $this->setParamsForView();

        return $this->render('completion', [
            'model' => $this->model,
            'canPublish' => $canPublish
        ]);
    }

    /**
     * Redirects to the next step of community progress-wizard form
     * @return \yii\web\Response
     */
    public function goToNextPart()
    {
        $partsWizard = new PartsFormCommunity([
            'model' => $this->model,
        ]);
        return $this->redirect([
            $partsWizard->getNext(),
            'id' => $this->model->id
        ]);
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }
}