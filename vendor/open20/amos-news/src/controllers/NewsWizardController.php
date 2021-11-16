<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\controllers
 * @category   CategoryName
 */

namespace open20\amos\news\controllers;

use open20\amos\core\behaviors\TaggableBehavior;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\news\AmosNews;
use open20\amos\news\components\PartsWizardNewsCreation;
use open20\amos\news\models\News;
use open20\amos\news\models\search\NewsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class NewsWizardController
 *
 * @property \open20\amos\news\models\News $model
 *
 * @package open20\amos\news\controllers
 */
class NewsWizardController extends CrudController
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
        $this->setModelObj(new News());
        $this->setModelSearch(new NewsSearch());
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
                        'roles' => ['CREATORE_NEWS']
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
        $parts = new PartsWizardNewsCreation(['model' => $this->model]);
        Yii::$app->view->title = $parts->active['index'] . '. ' . $parts->active['label'];
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => Yii::$app->view->title]
        ];
        Yii::$app->view->params['model'] = $this->model;
        Yii::$app->view->params['partsQuestionario'] = $parts;
        Yii::$app->view->params['hideBreadcrumb'] = true; // This param hide the wizard Breadcrumb.
        Yii::$app->view->params['hidePartsUrl'] = true; // This param disable the progress wizard menu links.
        Yii::$app->view->params['textHelp'] = [
            'filename' => 'news-description'
        ];

    }
    
    /**
     * Get the next action to go to.
     * @return \yii\web\Response
     */
    public function goToNextPart()
    {

        $parts = new PartsWizardNewsCreation(['model' => $this->model]);
        return $this->redirect([$parts->getNext(), 'id' => $this->model->id]);
    }
    
    /**
     * Action for introduction step of the wizard.
     * @param int|null $id The news id.
     * @return string|\yii\web\Response
     */
    public function actionIntroduction($id = null)
    {
        Url::remember();
        
        if (isset($id)) {
            $this->model = $this->findModel($id);
        } else {
            $this->model = new News();
        }
        $cwhBehavior = $this->model->getBehavior('cwhBehavior');
        if (!empty($cwhBehavior)) {
            $this->model->detachBehavior('cwhBehavior');
        }
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }
        $this->model->setScenario(News::SCENARIO_INTRODUCTION);
        
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
     * @param int|null $id The news id.
     * @return string|\yii\web\Response
     */
    public function actionDetails($id = null)
    {
        Url::remember();

        if (isset($id)) {
            $this->model = $this->findModel($id);
            if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('NewsValidate', ['model' => $this->model]))) {
                Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
                return $this->redirect('/news/news-wizard/introduction');
            }
            $this->model->setDetailScenario();
        } else {
            $this->model = new News();
            $this->model->setDetailScenario();
        }
        $cwhBehavior = $this->model->getBehavior('cwhBehavior');
        if (!empty($cwhBehavior)) {
            $this->model->detachBehavior('cwhBehavior');
        }

        if (Yii::$app->request->post()&& $this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }

        $this->setParamsForView();
        $this->model->setDetailScenario();

        return $this->render('details', [
            'model' => $this->model
        ]);
    }
    
    /**
     * Action for publication step of the wizard.
     * @param int $id The news id.
     * @return string|\yii\web\Response
     */
    public function actionPublication($id)
    {
        Url::remember();
        
        $this->model = $this->findModel($id);
        if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('NewsValidate',
                ['model' => $this->model]))
        ) {
            Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
            return $this->redirect('/news/news-wizard/introduction');
        }
        $this->model->setScenario(News::SCENARIO_PUBLICATION);
        
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
     * @param int $id The news id.
     * @return string|\yii\web\Response
     */
    public function actionSummary($id)
    {
        Url::remember();
        
        $this->model = $this->findModel($id);
        
        if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('NewsValidate', ['model' => $this->model]))) {
            Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
            return $this->redirect('/news/news-wizard/introduction');
        }
        
        $this->model->setScenario(News::SCENARIO_SUMMARY);
        
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post())) {
            $cwhBehavior = $this->model->getBehavior('cwhBehavior');
            if (!empty($cwhBehavior)) {
                $this->model->detachBehavior('cwhBehavior');
            }
            $this->model->detachBehaviorByClassName(TaggableBehavior::className());
            if ($this->model->status == News::NEWS_WORKFLOW_STATUS_VALIDATO) {
                $this->model->status = News::NEWS_WORKFLOW_STATUS_DAVALIDARE;
                $this->model->save();
                $this->model->status = News::NEWS_WORKFLOW_STATUS_VALIDATO;
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
        
        if ($loggedUser->can('NewsValidate', ['model' => $this->model])) {
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
     * @param int $id The news id.
     * @return string
     */
    public function actionFinish($id)
    {
        Url::remember();
        $this->model = $this->findModel($id);
        $finishMessage = AmosNews::tHtml('amosnews', 'The news has been') . ' ';
        $loggedUser = Yii::$app->getUser();
        if ($loggedUser->can('NewsValidate',
                ['model' => $this->model]) && $this->model->status == News::NEWS_WORKFLOW_STATUS_VALIDATO
        ) {
            $finishMessage .= AmosNews::tHtml('amosnews', 'published');
        } else {
            if (!($this->model->created_by == Yii::$app->user->id)) {
                Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
                return $this->redirect('/news/news-wizard/introduction');
            }
            $finishMessage .= AmosNews::tHtml('amosnews', 'created and is now waiting to be published');
        }
        $this->setParamsForView();
        Yii::$app->view->params['hidePartsUrl'] = true; // This param disable the progress wizard menu links.
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
