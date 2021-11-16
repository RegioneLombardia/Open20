<?php

namespace open20\amos\moodle\controllers;

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\Lesson;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\moodle\models\ServiceCall;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * This is the class for controller "LessonController".
 */
class LessonController extends CrudController {

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
                            'index','lesson-detail','certificate-detail'
                        ],
                        'roles' => [AmosMoodle::MOODLE_STUDENT]
                    ],
                ]
            ],
           
        ]);
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function init() {
        $this->setModelObj(new Lesson());
        $this->setModelSearch(new Lesson());

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
     * Lists all Topic models.
     * @return mixed
     */
    public function actionIndex($layout = null) {
        Url::remember();

        $serviceCall = new ServiceCall();
        $topicId = Yii::$app->request->get('topicId');
        $courseId = Yii::$app->request->get('courseId');

        if (!empty($topicId) && !empty($courseId)) {
            $contentsList = $serviceCall->getCourseContents($courseId, $topicId);
            
            $lessonList = $this->getModelObj()->getLessonList($contentsList);

            //pr($lessonList);
            $arrayDataProvider = new \yii\data\ArrayDataProvider(['allModels' => $lessonList]);
            //$this->setDataProvider($arrayDataProvider);
            //$this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));

        $this->view->params['dataProvider'] = $arrayDataProvider;

//            $this->setParametro($arrayDataProvider);
            
            return parent::actionIndex();
        }
    }

    /**
     * 
     * @param type $lessonId
     * @return type
     */
    public function actionLessonDetail($lessonId){
        //pr($lessonId);
        $serviceCall = new ServiceCall();
        $scormDetails = $serviceCall->getScormDetails($lessonId);
        //pr($scormDetails);
        $close = (Yii::$app->request->get('close') == 1);
           $params = [
            'scormDetails' => $scormDetails,
            'close' => $close,
        ];

        return $this->renderPartial('lessonDetail', $params);
       
    }

    /**
     * 
     * @param type $certificateId
     * @return type
     */
    public function actionCertificateDetail($certificateId){
        //pr($lessonId);
        $serviceCall = new ServiceCall();
        $certificateDetails = $serviceCall->getCertificateDetails($certificateId);
        //pr($scormDetails);
        $params = [
            'certificateDetails' => $certificateDetails,
        ];

        return $this->renderPartial('certificateDetail', $params);
    }
    
}
