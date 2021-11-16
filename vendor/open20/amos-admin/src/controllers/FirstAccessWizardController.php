<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace open20\amos\admin\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\admin\components\FirstAccessWizardParts;
use open20\amos\admin\interfaces\OrganizationsModuleInterface;
use open20\amos\admin\models\search\UserProfileAreaSearch;
use open20\amos\admin\models\search\UserProfileRoleSearch;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\utilities\ArrayUtility;
use Yii;
use yii\base\Event;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class FirstAccessWizardController
 *
 * @property \open20\amos\admin\models\UserProfile $model
 *
 * @package open20\amos\admin\controllers
 */
class FirstAccessWizardController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;

    /**
     * Working user ID
     */
    protected $userProfileId;

    /**
     * @var AmosAdmin $adminModule
     */
    protected $adminModule;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(AmosAdmin::instance()->createModel('UserProfile'));
        $this->setModelSearch(AmosAdmin::instance()->createModel('UserProfileSearch'));
        $this->setAvailableViews([]);

        parent::init();
        $this->setUpLayout();

        ModuleAdminAsset::register(Yii::$app->view);

        $this->setUpLayout('progress_wizard');
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'My Profile'));
        $this->setStartObjClassName(AmosAdmin::instance()->model('UserProfile'));
        $this->setTargetObjClassName(AmosAdmin::instance()->model('UserProfile'));

        $this->on(M2MEventsEnum::EVENT_BEFORE_ASSOCIATE_ONE2MANY, [$this, 'beforeAssociateOneToMany']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_RENDER_ASSOCIATE_ONE2MANY, [$this, 'beforeRenderOneToMany']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_CANCEL_ASSOCIATE_M2M, [$this, 'beforeCancelAssociateM2m']);
        $this->on(M2MEventsEnum::EVENT_AFTER_ASSOCIATE_ONE2MANY, [$this, 'afterAssociateOneToMany']);

        //Set current user id
        $this->userProfileId = Yii::$app->getUser()->identity->profile->id;

        $this->adminModule = AmosAdmin::instance();
    }

    /**
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'introduction',
                                'introducing-myself',
                                'role-and-area',
                                'interests',
                                'partnership',
                                'finish',
                                'annulla-m2m',
                                'associate-facilitator',
                                'associate-prevalent-partnership'
                            ],
                            'roles' => ['@']
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
            ]
        );

        return $behaviors;
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
    }

    /**
     * Set view params for the event creation wizard.
     */
    private function setParamsForView()
    {
        $parts = new FirstAccessWizardParts(['model' => $this->model]);
        Yii::$app->view->title = $parts->active['index'] . '. ' . $parts->active['label'];
        Yii::$app->view->params['model'] = $this->model;
        Yii::$app->view->params['partsQuestionario'] = $parts;
        Yii::$app->view->params['hidePartsLabel'] = true; // This param hide the second title under the wizard progress bar.
        Yii::$app->view->params['disablePlatformLinks'] = true;
        Yii::$app->view->params['hideBreadcrumb'] = true; // This param hide the breadcrumb in the wizard layout.
        Yii::$app->view->params['hidePartsUrl'] = true; // This param disable the progress wizard menu links.
    }

    /**
     * @return \yii\web\Response
     */
    public function goToNextPart()
    {
        $parts = new FirstAccessWizardParts(['model' => $this->model]);

        return $this->redirect([$parts->getNext()]);
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeAssociateOneToMany($event)
    {
        $this->setUpLayout('main');
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeRenderOneToMany($event)
    {
        $this->setParamsForView();
    }

    /**
     * @param $event
     */
    public function afterAssociateOneToMany($event)
    {
        try {
            $userprofile_class = $this->adminModule->model('UserProfile');

            if (!empty($event->sender) && is_object($event->sender) && $event->sender instanceof $userprofile_class) {
                if (!empty($event->sender->prevalent_partnership_id)) {
                    /** @var  $organizationsModule OrganizationsModuleInterface */
                    $organizationsModule = \Yii::$app->getModule($this->adminModule->getOrganizationModuleName());
                    $organizationsModule->saveOrganizationUserMm(Yii::$app->user->id, $event->sender->prevalent_partnership_id);
                }
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param Event $event
     */
    public function beforeCancelAssociateM2m($event)
    {
        $get = Yii::$app->getRequest()->get();
        if (isset($get['action'])) {
            switch ($get['action']) {
                case 'associate-facilitator':
                    $this->setRedirectAction('introducing-myself');
                    break;
                case 'associate-prevalent-partnership':
                    $this->setRedirectAction('partnership');
                    break;
            }
        }
    }

    /**
     * @return string
     */
    public function actionAssociateFacilitator()
    {
        $this->setMmTargetKey('facilitatore_id');
        $this->setRedirectAction('introducing-myself');
        $this->setTargetUrl('associate-facilitator');
        return $this->actionAssociateOneToMany($this->userProfileId);
    }

    /**
     * @return string
     */
    public function actionAssociatePrevalentPartnership()
    {
        $this->setMmTargetKey('prevalent_partnership_id');
        $this->setRedirectAction('partnership');
        $this->setTargetUrl('associate-prevalent-partnership');
        return $this->actionAssociateOneToMany($this->userProfileId);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIntroduction()
    {
        Url::remember();

        $this->model = $this->findModel($this->userProfileId);
        $this->model->setScenario(UserProfile::SCENARIO_INTRODUCTION);
        if (Yii::$app->getRequest()->post()) {
            return $this->goToNextPart();
        }

        // If the user has never accessed to the first access wizard, this will create a new array (jsonified)
        // that will be saved in the db and saves the steps opened once at least
        if ($this->model->first_access_wizard_steps_accessed == "") {
            $parts = [];
            $firstAccessWizardParts = (new FirstAccessWizardParts(['model' => $this->model]));
            foreach ($firstAccessWizardParts::$map as $partName => $partValue) {
                $parts[$partName] = false;
            }

            $this->model->first_access_wizard_steps_accessed = Json::encode($parts);
            $this->model->save(false);
        }

        $this->setAccessFirstTime(FirstAccessWizardParts::PART_INTRODUCTION);

        $this->setParamsForView();

        return $this->render(
            'introduction',
            [
                'model' => $this->model
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIntroducingMyself()
    {
        Url::remember();

        $this->model = $this->findModel($this->userProfileId);

        // Set default facilitator if an other facilitator is not present.
        if (!$this->model->facilitatore_id && !is_null($this->model->getDefaultFacilitator())) {
            $this->model->facilitatore_id = $this->model->getDefaultFacilitator()->id;
            $this->model->save(false);
        }

        $this->model->setScenario(UserProfile::SCENARIO_INTRODUCING_MYSELF);
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            if(!empty(\Yii::$app->request->get('gotoFacilitator'))){
                return $this->redirect(['/'.AmosAdmin::getModuleName().'/first-access-wizard/associate-facilitator', 'id' => $this->model->id, 'viewM2MWidgetGenericSearch' => true]);
            }
            return $this->goToNextPart();
        }

        $this->setAccessFirstTime(FirstAccessWizardParts::PART_INTRODUCING_MYSELF);

        $this->setParamsForView();

        return $this->render(
            'introducing_myself',
            [
                'model' => $this->model,
                'facilitatorUserProfile' => $this->model->facilitatore
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionRoleAndArea()
    {
        Url::remember();

        $this->model = $this->findModel($this->userProfileId);

        if (Yii::$app->getRequest()->post()) {
            $this->model->setScenario(UserProfile::SCENARIO_ROLE_AND_AREA);
            if ($this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
                return $this->goToNextPart();
            }
        }

        $this->setAccessFirstTime(FirstAccessWizardParts::PART_ROLE_AND_AREA);

        $this->setParamsForView();
        $this->model->setScenario(UserProfile::SCENARIO_ROLE_AND_AREA);

        return $this->render(
            'role_and_area',
            [
                'model' => $this->model
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionInterests()
    {
        Url::remember();

        $this->model = $this->findModel($this->userProfileId);

        if (Yii::$app->getRequest()->post()) {
            $this->model->setScenario(UserProfile::SCENARIO_INTERESTS);
            if ($this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
                return $this->goToNextPart();
            }
        }

        if ($this->model->hasErrors()) {
            foreach ($this->model->getErrors() as $errors) {
                foreach ($errors as $error) {
                    Yii::$app->getSession()->addFlash('danger', $error);
                }
            }
        }

        $this->setAccessFirstTime(FirstAccessWizardParts::PART_INTERESTS);

        $this->setParamsForView();
        $this->model->setScenario(UserProfile::SCENARIO_INTERESTS);

        return $this->render(
            'interests',
            [
                'model' => $this->model
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionPartnership()
    {
        Url::remember();

        $this->model = $this->findModel($this->userProfileId);

        if (Yii::$app->getRequest()->post()) {
            $this->model->setScenario(UserProfile::SCENARIO_PARTNERSHIP);
            if ($this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
                return $this->goToNextPart();
            }
        }

        $this->setAccessFirstTime(FirstAccessWizardParts::PART_PARTNERSHIP);

        $this->setParamsForView();
        $this->model->setScenario(UserProfile::SCENARIO_PARTNERSHIP);

        return $this->render(
            'partnership',
            [
                'model' => $this->model
            ]
        );
    }

    /**
     * @param int $id The user profile id.
     * @return string|\yii\web\Response
     */
    public function actionFinish()
    {
        Url::remember();
        $this->model = $this->findModel($this->userProfileId);

        $this->model->status = ($this->adminModule->bypassWorkflow ?
            UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED :
            UserProfile::USERPROFILE_WORKFLOW_STATUS_TOVALIDATE);
        $this->model->save(false);

        $this->setAccessFirstTime(FirstAccessWizardParts::PART_FINISH);

        $this->setParamsForView();

        return $this->render(
            'finish',
            [
                'model' => $this->model
            ]
        );
    }

    /**
     * This method return all enabled professional roles translated.
     * @return array
     */
    public function getRoles()
    {
        $roles = ArrayUtility::translateArrayValues(
            ArrayHelper::map(UserProfileRoleSearch::find()->andWhere('name!="Other"')->asArray()->all(), 'id', 'name'),
            'amosadmin',
            AmosAdmin::className()
        );

        asort($roles);

        $other = ArrayUtility::translateArrayValues(
            ArrayHelper::map(UserProfileRoleSearch::find()->andWhere('name="Other"')->asArray()->all(), 'id', 'name'),
            'amosadmin',
            AmosAdmin::className()
        );

        return $roles + $other;
    }

    /**
     * This method return all enabled professional areas translated.
     * @return array
     */
    public function getAreas()
    {
        $areas = ArrayUtility::translateArrayValues(
            ArrayHelper::map(UserProfileAreaSearch::find()->andWhere('name!="Other"')->asArray()->all(), 'id', 'name'),
            'amosadmin',
            AmosAdmin::className()
        );

        asort($areas);

        $other = ArrayUtility::translateArrayValues(
            ArrayHelper::map(UserProfileAreaSearch::find()->andWhere('name="Other"')->asArray()->all(), 'id', 'name'),
            'amosadmin',
            AmosAdmin::className()
        );

        return $areas + $other;
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
                $this->layout = '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            }
        }

        return true;
    }

    /**
     * Sets in the user_profile table an accessed step for the first time
     * @param string $step
     */
    public function setAccessFirstTime($step)
    {
        if ($this->model->first_access_wizard_steps_accessed != null && $this->model->first_access_wizard_steps_accessed != '') {
            $stepsAccessed = Json::decode($this->model->first_access_wizard_steps_accessed);
            if (!$stepsAccessed[$step]) {
                $stepsAccessed[$step] = true;
                $this->model->first_access_wizard_steps_accessed = Json::encode($stepsAccessed);
                $this->model->save(false);
            }
        }
    }

}
