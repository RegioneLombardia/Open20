<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\controllers
 * @category   CategoryName
 */

namespace open20\amos\discussioni\controllers;

use open20\amos\core\behaviors\TaggableBehavior;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\components\PartsWizardDiscussioniTopicCreation;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class DiscussioniTopicWizardController
 *
 * @property \open20\amos\discussioni\models\DiscussioniTopic $model
 *
 * @package open20\amos\discussioni\controllers
 */
class DiscussioniTopicWizardController extends CrudController
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
        $this->setModelObj(new DiscussioniTopic());
        $this->setModelSearch(new DiscussioniTopicSearch());
        $this->setAvailableViews([]);

        parent::init();
        $this->setUpLayout();
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
                            'publication',
                            'summary',
                            'finish'
                        ],
                        'roles' => ['CREATORE_DISCUSSIONI']
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
        $parts = new PartsWizardDiscussioniTopicCreation(['model' => $this->model]);
        $parts->model = $this->model;
        Yii::$app->view->title = $parts->active['index'] . '. ' . $parts->active['label'];
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => Yii::$app->view->title]
        ];
        Yii::$app->view->params['model'] = $this->model;
        Yii::$app->view->params['partsQuestionario'] = $parts;
        Yii::$app->view->params['hideBreadcrumb'] = true; // This param hide the wizard breadcrumb.
        Yii::$app->view->params['hidePartsUrl'] = true; // This param disable the progress wizard menu links.
        Yii::$app->view->params['textHelp'] = [
            'filename' => 'discussions-description'
        ];
    }
    
    /**
     * Get the next action to go to.
     * @return \yii\web\Response
     */
    public function goToNextPart()
    {
        $parts = new PartsWizardDiscussioniTopicCreation(['model' => $this->model]);
        return $this->redirect([$parts->getNext(), 'id' => $this->model->id]);
    }
    
    /**
     * Action for introduction step of the wizard.
     * @param int|null $id The discussion id.
     * @return string|\yii\web\Response
     */
    public function actionIntroduction($id = null)
    {
        Url::remember();
        
        if (isset($id)) {
            $this->model = $this->findModel($id);
        } else {
            $this->model = new DiscussioniTopic();
        }
        
        $cwhBehavior = $this->model->getBehavior('cwhBehavior');
        if (!empty($cwhBehavior)) {
            $this->model->detachBehavior('cwhBehavior');
        }
        
        if (Yii::$app->getRequest()->post()) {
            return $this->goToNextPart();
        }
        
        $this->setParamsForView();
        return $this->render('introduction', [
            'model' => $this->model
        ]);
    }
    
    /**
     * Action for details step of the wizard.
     * @param int|null $id The discussion id.
     * @return string|\yii\web\Response
     */
    public function actionDetails($id = null)
    {
        Url::remember();
        
        if (isset($id)) {
            $this->model = $this->findModel($id);
            if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('DiscussionValidate', ['model' => $this->model]))) {
                Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
                return $this->redirect('introduction');
            }
        } else {
            $this->model = new DiscussioniTopic();
        }
        $this->model->setScenario(DiscussioniTopic::SCENARIO_DETAILS);
        
        $cwhBehavior = $this->model->getBehavior('cwhBehavior');
        if (!empty($cwhBehavior)) {
            $this->model->detachBehavior('cwhBehavior');
        }
        
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }
        
        $this->setParamsForView();
        $this->model->setScenario(DiscussioniTopic::SCENARIO_DETAILS);
        return $this->render('details', [
            'model' => $this->model
        ]);
    }
    
    /**
     * Action for publication step of the wizard.
     * @param int $id The discussion id.
     * @return string|\yii\web\Response
     */
    public function actionPublication($id)
    {
        Url::remember();
        
        $this->model = $this->findModel($id);
        
        if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('DiscussionValidate',
                ['model' => $this->model]))
        ) {
            Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
            return $this->redirect('introduction');
        }
        $this->model->setScenario(DiscussioniTopic::SCENARIO_PUBLICATION);
        
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }
        
        $this->setParamsForView();
        return $this->render('publication', [
            'model' => $this->model
        ]);
    }
    
    /**
     * Action for summary step of the wizard.
     * @param int $id The discussion id.
     * @return string|\yii\web\Response
     */
    public function actionSummary($id)
    {
        Url::remember();
        
        $this->model = $this->findModel($id);
        if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('DiscussionValidate', ['model' => $this->model]))) {
            Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
            return $this->redirect('introduction');
        }
        $this->model->setScenario(DiscussioniTopic::SCENARIO_SUMMARY);
        
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post())) {
            $cwhBehavior = $this->model->getBehavior('cwhBehavior');
            if (!empty($cwhBehavior)) {
                $this->model->detachBehavior('cwhBehavior');
            }
            $this->model->detachBehaviorByClassName(TaggableBehavior::className());
            if ($this->model->status == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA) {
                $this->model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE;
                $this->model->save();
                $this->model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA;
            }
            if ($this->model->save()) {
                return $this->goToNextPart();
            }
            if (!empty($cwhBehavior)) {
                $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
            }
        }
        
        $viewPublishId = 'request-publish-btn';
        $viewPublishLabel = 'Request publish';
        
        $loggedUser = Yii::$app->getUser();
        
        if ($loggedUser->can('DiscussionValidate', ['model' => $this->model])) {
            $viewPublishId = 'publish-btn';
            $viewPublishLabel = 'Publish';
        }
        
        $this->setParamsForView();
        return $this->render('summary', [
            'model' => $this->model,
            'viewPublishId' => $viewPublishId,
            'viewPublishLabel' => $viewPublishLabel
        ]);
    }
    
    /**
     * Action for finish step of the wizard.
     * @param int $id The discussion id.
     * @return string
     */
    public function actionFinish($id)
    {
        Url::remember();
        $this->model = $this->findModel($id);
        $finishMessage = AmosDiscussioni::tHtml('amosdiscussioni', 'The discussion has been') . ' ';
        $loggedUser = Yii::$app->getUser();
        if ($loggedUser->can('DiscussionValidate', ['model' => $this->model]) &&
            ($this->model->status == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA)
        ) {
            $finishMessage .= AmosDiscussioni::tHtml('amosdiscussioni', 'published');
        } else {
            if (!($this->model->created_by == Yii::$app->user->id)) {
                Yii::$app->session->addFlash('danger',
                    BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
                return $this->redirect('introduction');
            }
            $finishMessage .= AmosDiscussioni::tHtml('amosdiscussioni',
                'created and is now waiting to be published');
        }
        $this->setParamsForView();
        return $this->render('finish', [
            'model' => $this->model,
            'finishMessage' => $finishMessage
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
