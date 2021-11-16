<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @category   CategoryName
 */

namespace backend\modules\statistics\controllers;

use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\statistics\models\search\CampainsViewsSearch;
use backend\modules\statistics\models\stats\CampainsViewsStats;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\modules\statistics\utility\UIUtility;
use open20\amos\core\controllers\BackendController;
use InvalidArgumentException;
use yii\base\Controller;
use yii\filters\AccessControl;
use preference\userprofile\models\PreferenceChannel;
use Yii;
use yii\helpers\VarDumper;
use preference\userprofile\utility\TargetTagUtility;

/**
 * Undocumented class
 */
class PreferenceCampaignsController extends BackendController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'personal-data',
                            'inscriptions',
                            'errors',
                            'items',
                        ],
                        'roles' => ['PC_ADMIN', 'PC_STRUCTURE_ADMIN', 'PC_APPROVER', 'PC_STRUCTURE_APPROVER']
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
    }

    public function init()
    {
        parent::init();

        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarDataAnalysis();
    }

    /**
     * SONO LE SEGMENTAZIONI  APERTURE per campagna EMAIL
     *
     * @return void
     */
    public function actionIndex()
    {

        $modelStats = new CampainsViewsStats();
        $genderData = $modelStats->statsGender(Yii::$app->request->getQueryParams());
        $ageGroupData = $modelStats->statsAgeGroups(Yii::$app->request->getQueryParams());
        $provincesData = $modelStats->statsProvinces(Yii::$app->request->getQueryParams());
        // $dataProvider = $modelSearch->load(Yii::$app->request->getQueryParams());        

        // VarDumper::dump( $dataProvider->query, $depth = 10, $highlight = true); die;
        $channels = PreferenceCampainChannelMm::find()->andWhere([
            'status' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT,
            'preference_channel_id' => PreferenceChannel::NEWSLETTER_ID,
            ])->orderBy('created_at DESC')->all();
            
        $choices = ArrayHelper::map($channels,'id', 'nameConcatCampainChannel');
        return $this->render('index', [
            'model' => $modelStats,
            'choices' => $choices,
            'genderData' => $genderData,
            'ageGroupData' => $ageGroupData,
            'provincesData' => $provincesData,
        ]);
    }

    public function actionPersonalData()
    {
        $modelStats = new CampainsViewsStats();
        $dataGender = $modelStats->statPersonalDataGender(Yii::$app->request->getQueryParams());
        $dataAgeGroups = $modelStats->statPersonalDataAgeGroup(Yii::$app->request->getQueryParams());
        $dataProvinces = $modelStats->statPersonalDataProvinces(Yii::$app->request->getQueryParams());
        $targets = TargetTagUtility::getAllTargetTag();
        
        $choices = ArrayHelper::map($targets,'id', 'nome');
        $choices[0] = 'Tutti i target';
        $choices[-1] = 'Totali';
        ksort($choices);
        
        return $this->render('personal_data', [
            'model' => $modelStats,
            'choices' => $choices,
            'dataGender' => $dataGender,
            'dataAgeGroups' => $dataAgeGroups,
            'dataProvinces' => $dataProvinces,
        ]);
    }

    public function actionInscriptions()
    {
        $modelStats = new CampainsViewsStats();
        $inscriptionsData = $modelStats->statInscriptions(Yii::$app->request->getQueryParams());

        // VarDumper::dump( $dataProvider->query, $depth = 10, $highlight = true); die;
        
        return $this->render('inscriptions', [
            'model' => $modelStats,
            'choices' => $modelStats->getUserStatusChioces(),
            'inscriptionsData' => $inscriptionsData,
        ]);
    }

    public function actionItems()
    {
        $modelStats = new CampainsViewsStats();
        $data = $modelStats->statTargetsItems(Yii::$app->request->getQueryParams());
        // $ageGroupData = $modelStats->statsAgeGroups(Yii::$app->request->getQueryParams());
        // $provincesData = $modelStats->statsProvinces(Yii::$app->request->getQueryParams());
        // $dataProvider = $modelSearch->load(Yii::$app->request->getQueryParams());        

        // VarDumper::dump( $dataProvider->query, $depth = 10, $highlight = true); die;
        $targets = TargetTagUtility::getAllTargetTag();
        
        $choices = ArrayHelper::map($targets,'id', 'nome');
        $choices[0] = 'Tutti i target';
        ksort($choices);
        
        return $this->render('items', [
            'model' => $modelStats,
            'choices' => $choices,
            'data' => $data,
            // 'ageGroupData' => $ageGroupData,
            // 'provincesData' => $provincesData,
        ]);
    }

    public function actionErrors()
    {

        // die('stop');
        $modelStats = new CampainsViewsStats();
        $errorsData = $modelStats->statErrors();

        // VarDumper::dump( $dataProvider->query, $depth = 10, $highlight = true); die;
        
        return $this->render('errors', [
            'model' => $modelStats,
            'errorsData' => $errorsData,
        ]);
    }

}
