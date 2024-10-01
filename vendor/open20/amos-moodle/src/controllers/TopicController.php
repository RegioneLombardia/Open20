<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\controllers
 */

namespace open20\amos\moodle\controllers;

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\Topic;
use open20\amos\moodle\models\ServiceCall;
use open20\amos\moodle\utility\MoodleUtility;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\layout\Module;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;

/**
 * This is the class for controller "TopicController".
 */
class TopicController extends CrudController
{
    /**
     *
     * @var type 
     */
    protected $serviceCall;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(
            parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index'
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
    public function init()
    {
        $this->setModelObj(new Topic());
        $this->setModelSearch(new Topic());
        $this->serviceCall = new ServiceCall();

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}' . Html::tag('p', Yii::t('amoscore', 'Table')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /* 'list' => [
              'name' => 'list',
              'label' => Yii::t('amoscore', '{iconaLista}'.Html::tag('p',Yii::t('amoscore', 'List')), [
              'iconaLista' => AmosIcons::show('view-list')
              ]),
              'url' => '?currentView=list'
              ],
              'icon' => [
              'name' => 'icon',
              'label' => Yii::t('amoscore', '{iconaElenco}'.Html::tag('p',Yii::t('amoscore', 'Icons')), [
              'iconaElenco' => AmosIcons::show('grid')
              ]),
              'url' => '?currentView=icon'
              ],
              'map' => [
              'name' => 'map',
              'label' => Yii::t('amoscore', '{iconaMappa}'.Html::tag('p',Yii::t('amoscore', 'Map')), [
              'iconaMappa' => AmosIcons::show('map')
              ]),
              'url' => '?currentView=map'
              ],
              'calendar' => [
              'name' => 'calendar',
              'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
              //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
              'label' => Yii::t('amoscore', '{iconaCalendario}'.Html::tag('p',Yii::t('amoscore', 'Calendar')), [
              'iconaMappa' => AmosIcons::show('calendar')
              ]),
              'url' => '?currentView=calendar'
              ], */
        ]);

        parent::init();
    }

    /**
     * 
     * @param type $action
     * @return boolean
     */
    public function beforeAction($action)
    {
        $titleSection = AmosMoodle::_t('Argomenti');
      
        $this->view->params = [
            'hideCreate'=>true,
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'moodle',
            'titleSection' => $titleSection,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here
        return true;
    }

    /**
     * Lists all Topic models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();

        //$courseId = Yii::$app->request->get('courseId');
        $course = MoodleUtility::getCommunityCourse();

        if (!empty($course)) {
            $courseId = $course->moodle_courseid;
            $this->getModelObj()->courseId = $courseId;

            if (!empty($courseId)) {

                //se l'utente può iscriversi al corso da solo
                //$selfEnrollment = false;
                
                //se l'utente è iscritto al corso
                $courseEnrolled = $this->serviceCall->isUserEnrolledInCourse($courseId);

                //L'utente è iscritto al corso: mostro i contenuti
                if ($courseEnrolled) {
                    $contentsList = $this->serviceCall->getCourseContents($courseId);
                    //pr($contentsList);exit;

                    $topicList = $this->getModelObj()->getTopicList($contentsList);
                    $arrayDataProvider = new \yii\data\ArrayDataProvider(['allModels' => $topicList]);
                    $this->view->params['dataProvider'] = $arrayDataProvider;

                } else {
                    $arrayDataProviderNull = new \yii\data\ArrayDataProvider(['allModels' => []]);
                    $this->view->params['dataProvider'] = $arrayDataProviderNull;
                }

                $this->view->params['courseId'] = $courseId;

                if (!$courseEnrolled) {
                    $this->layout = '@vendor/open20/amos-core/views/layouts/main';
                    return parent::actionIndex($this->layout);
                }
                
                return parent::actionIndex();
            }
        }
    }

    /**
     * Displays a single Topic model.
     * @param integer $id
     * @return mixed
     */
    /* public function actionView($id) {

      $model = $this->findModel($id);

      if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
      } else {
      return $this->render('view', ['model' => $model]);
      }
      } */

    /**
     * Creates a new Topic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /* public function actionCreate() {
      $this->layout = "@vendor/open20/amos-core/views/layouts/form";
      $model = new Topic;

      if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($model->save()) {
      Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
      return $this->redirect(['index']);
      } else {
      Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
      return $this->render('create', [
      'model' => $model,
      ]);
      }
      } else {
      return $this->render('create', [
      'model' => $model,
      ]);
      }
      } */

    /**
     * Updates an existing Topic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*  public function actionUpdate($id) {
      $this->layout = "@vendor/open20/amos-core/views/layouts/form";
      $model = $this->findModel($id);

      if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($model->save()) {
      Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
      return $this->redirect(['index']);
      } else {
      Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
      return $this->render('update', [
      'model' => $model,
      ]);
      }
      } else {
      return $this->render('update', [
      'model' => $model,
      ]);
      }
      } */

    /**
     * Deletes an existing Topic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*  public function actionDelete($id) {
      $model = $this->findModel($id);
      if ($model) {
      //si può sostituire il  delete() con forceDelete() in caso di SOFT DELETE attiva
      //In caso di soft delete attiva e usando la funzione delete() non sarà bloccata
      //la cancellazione del record in presenza di foreign key quindi
      //il record sarà cancelleto comunque anche in presenza di tabelle collegate a questo record
      //e non saranno cancellate le dipendenze e non avremo nemmeno evidenza della loro presenza
      //In caso di soft delete attiva è consigliato modificare la funzione oppure utilizzare il forceDelete() che non andrà
      //mai a buon fine in caso di dipendenze presenti sul record da cancellare
      if ($model->delete()) {
      Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item deleted'));
      } else {
      Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not deleted because of dependency'));
      }
      } else {
      Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not found'));
      }
      return $this->redirect(['index']);
      } */
}
