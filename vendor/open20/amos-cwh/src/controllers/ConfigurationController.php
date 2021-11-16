<?php

namespace open20\amos\cwh\controllers;


use open20\amos\core\controllers\BaseController;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\base\ModelConfig;
use open20\amos\cwh\helpers\ContentHelper;
use open20\amos\cwh\helpers\NetworkHelper;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\utility\CwhUtil;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class ConfigurationController
 * @package open20\amos\cwh\controllers
 *
 */
class ConfigurationController extends BaseController
{
    const CONTENTS_DATA_CACHE_KEY = 'ContentsData';
    const NETWORKS_DATA_CACHE_KEY = 'NetworksData';
    const LAST_PROCESS_DATETIME_CACHE_KEY = 'CwhTime';

    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function init() {

        $this->setModelObj(new ModelConfig());

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
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
                        'actions' => ['content', 'delete-content', 'wizard', 'network'],
                        'roles' => ['AMMINISTRATORE_CWH']
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

    public function actionContent($id = null)
    {
        set_time_limit(0);
        $Content = CwhConfigContents::findOne($id);

        if (!($Content)) {
            $Content = new CwhConfigContents();
            $Content->load(\Yii::$app->getRequest()->getQueryParams(), '');
            if($Content->classname){
                if(class_exists($Content->classname)){
                    $modelObject = \Yii::createObject($Content->classname);
                    if(!$Content->status_attribute) {
                        if ($modelObject->hasProperty('status')) {
                            $Content->status_attribute = 'status';
                            if ($modelObject->status) {
                                $Content->status_value = $modelObject->status;
                            }
                        }
                    }
                }
            }
        }

        if (\Yii::$app->getRequest()->getIsPost()) {
            $Content->load(\Yii::$app->getRequest()->post());
            if ($Content->save(false)) {
                Yii::$app->session->addFlash('success', AmosCwh::t('amoscwh', '#configuration_saved'));
                return $this->redirect(['content',
                    'id' => $Content->id
                ]);
            }
        }

        return $this->render('contents', [
            'Content' => $Content,
            'Statuses' => $Content->statuses,
        ]);
    }

    public function actionDeleteContent($tablename = null){

        set_time_limit(0);
        try {
            $content = CwhConfigContents::findOne(['tablename' => $tablename]);
            if (!is_null($content)) {
                $content->delete();
                Yii::$app->session->addFlash('success', AmosCwh::t('amoscwh', '#configuration_deleted'));
            }
        }catch (\yii\base\Exception $ex ){
            Yii::$app->session->addFlash('danger',  AmosCwh::t('amoscwh', '#delete_error'));
        }
       return $this->redirect('wizard');

    }

    public function actionNetwork($id = null)
    {
        $Network = CwhConfig::findOne($id);

        if (!($Network)) {
            $Network = new CwhConfig();
            $Network->load(\Yii::$app->getRequest()->getQueryParams(), '');
        }

        if (\Yii::$app->getRequest()->getIsPost()) {
            $Network->load(\Yii::$app->getRequest()->post());
            if ($Network->save(false)) {
                return $this->redirect(['network',
                    'id' => $Network->id
                ]);
            }
        }

        return $this->render('network', [
            'Network' => $Network,
        ]);
    }

    /**
     * Regenerates Cwh nodi view
     *
     */
    public function regenerateView()
    {
        try {
            CwhUtil::createCwhView();
        } catch (\Exception $e) {
            Yii::$app->getSession()->addFlash('warning', AmosCwh::t('amoscwh', 'Vista non creata correttamente. COD. ERROR: ' . $e->getMessage()));
        }
        Yii::$app->getSession()->addFlash('success', AmosCwh::t('amoscwh', 'Vista creata correttamente.'));

    }

    /**
     * @param bool $regenerateView
     * @return string
     */
    public function actionWizard($regenerateView = false)
    {
        Url::remember();

        $this->setUpLayout('main');

        if($regenerateView) {
            $this->regenerateView();
        }

        if (($post = \Yii::$app->getRequest()->post())) {
            if (isset($post['delete_cache'])) {
                set_time_limit(60);
                \Yii::$app->getCache()->delete(self::CONTENTS_DATA_CACHE_KEY);
                \Yii::$app->getCache()->delete(self::NETWORKS_DATA_CACHE_KEY);
                \Yii::$app->getCache()->delete(self::LAST_PROCESS_DATETIME_CACHE_KEY);
            }
        }

        $ContentsData = \Yii::$app->getCache()->getOrSet(self::CONTENTS_DATA_CACHE_KEY,
            function () {
                return ContentHelper::getEntities();
            });

        $NetworksData = \Yii::$app->getCache()->getOrSet(self::NETWORKS_DATA_CACHE_KEY,
            function () {
                return NetworkHelper::getEntities();
            });

        $time = time();
        $lastProcessDateTime = \Yii::$app->getCache()->getOrSet(self::LAST_PROCESS_DATETIME_CACHE_KEY,
            function () use ($time) {
                return $time;
            });

        $ContentsDataProvider = new ArrayDataProvider([
            'allModels' => $ContentsData,
        ]);

        $NetworksDataProvider = new ArrayDataProvider([
            'allModels' => $NetworksData,
        ]);

        return $this->render('wizard', [
            'networksDataProvider' => $NetworksDataProvider,
            'contentsDataProvider' => $ContentsDataProvider,
            'lastProcessDateTime' => $lastProcessDateTime,
        ]);
    }

}