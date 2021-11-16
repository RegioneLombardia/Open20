<?php

namespace open20\amos\moodle\controllers;

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\MoodleCourse;
use open20\amos\moodle\models\search\MoodleCourseSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\moodle\assets\MoodleAsset;
use open20\amos\core\user\User;
use open20\amos\core\user\AmosUser;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * This is the class for controller "CourseEnrolmentController".
 */
class CourseEnrolmentController extends CrudController {

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
                            'search-courses',
                        ],
                        'roles' => [
                            AmosMoodle::MOODLE_RESPONSABILE,
                            AmosMoodle::MOODLE_ADMIN,
                        ]
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
        MoodleAsset::register(Yii::$app->view);

        $this->setModelObj(new MoodleCourse());
        $this->setModelSearch(new MoodleCourseSearch());

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
     * @param int $userId : l'utente da iscivere 
     * Mostra tutti i corsi ad iscrizione autonoma (aperti) con il bottone per iscrivere un certo utente passato come parametro
     * @return mixed
     */
    public function actionSearchCourses($layout = null, $userId) {
        Url::remember();

        //$provider = Yii::$app->session->get('social-match');
        // Yii::$app->session->set('match', 'en-US');
        $userNotValid = true;
        $userToEnrol = User::findOne([
            'id' => $userId,
        ]);

        if ($userToEnrol) {
            $amosUser = new AmosUser(['identityClass' => User::className()]);
            $amosUser->setIdentity($userToEnrol);
           
            if ($amosUser->can(AmosMoodle::MOODLE_STUDENT)) {
                $userNotValid = false;
            }
        }

        if (!$userNotValid) {     
           $arrayDataProvider = $this->getModelSearch()->searchOpenCoursesForUser(Yii::$app->request->getQueryParams(),$userId);
           
//           $this->setParametro($arrayDataProvider);
            $this->view->params['dataProvider'] = $arrayDataProvider;

        }

        $this->setUpLayout('list');

        if ($layout) {
            $this->setUpLayout($layout);
        }
        

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getAvailableView('grid'),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null,
            'userToEnrol' => $userToEnrol,
            'userNotValid' => $userNotValid,
        ]);
    }

}
