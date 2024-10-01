<?php

namespace open20\amos\moodle\controllers;

use open20\amos\moodle\AmosMoodle;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\moodle\models\ServiceCall;
use open20\amos\moodle\utility\MoodleUtility;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * This is the class for controller "TopicController".
 */
class BadgeController extends CrudController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
                    'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                            [
                                'allow' => true,
                                'actions' => [
                                    'index',
                                ],
                                'roles' => [AmosMoodle::MOODLE_STUDENT]
                            ],
                        ]
                    ],
        ]);

        return $behaviors;
    }

    public function init() {
        $this->setModelObj(new ServiceCall());
        $this->setModelSearch(new ServiceCall());

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => Yii::t('amoscore', '{iconaLista}' . Html::tag('p', Yii::t('amoscore', 'Card')), [
                    'iconaLista' => AmosIcons::show('view-list')
                ]),
                'url' => '?currentView=list'
            ],
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
    public function actionIndex($layout = NULL) {
        Url::remember();

        $serviceCall = $this->getModel(); //new ServiceCall();

        $course = MoodleUtility::getCommunityCourse();

        if (!empty($course)) {
            $courseId = $course->moodle_courseid;

            $badgesList = $serviceCall->getUserBadges($courseId);
            //pr($badgesList, '$badgesList'); exit;

            $arrayDataProvider = new \yii\data\ArrayDataProvider(['allModels' => $badgesList['badges']]);
//            $this->setParametro($arrayDataProvider);
            
            $this->view->params['dataProvider'] = $arrayDataProvider;


            return parent::actionIndex();
        }
    }

}
