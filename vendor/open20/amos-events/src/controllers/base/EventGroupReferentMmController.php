<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers\base 
 */

namespace open20\amos\events\controllers\base;

use open20\amos\events\AmosEvents;
use Yii;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\events\models\search\EventGroupReferentMmSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;

/**
 * Class EventGroupReferentMmController 
 * EventGroupReferentMmController implements the CRUD actions for EventGroupReferentMm model.
 *
 * @property \open20\amos\events\models\EventGroupReferentMm $model
 * @property \open20\amos\events\models\search\EventGroupReferentMmSearch $modelSearch 
 *
 * @package open20\amos\events\controllers\base 
 */
class EventGroupReferentMmController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new EventGroupReferentMm());
        $this->setModelSearch(new EventGroupReferentMmSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /* 'list' => [
              'name' => 'list',
              'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
              'url' => '?currentView=list'
              ],
              'icon' => [
              'name' => 'icon',
              'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),
              'url' => '?currentView=icon'
              ],
              'map' => [
              'name' => 'map',
              'label' => AmosIcons::show('map') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Map')),
              'url' => '?currentView=map'
              ],
              'calendar' => [
              'name' => 'calendar',
              'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
              //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
              'label' => AmosIcons::show('calendar') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Calendari')),
              'url' => '?currentView=calendar'
              ], */
        ]);

        parent::init();
//        $this->setUpLayout();
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);
    }

    /**
     * Lists all EventGroupReferentMm models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
//        $eventRoleUse = new \open20\amos\moodle\bootstrap\EventRoleUser();
//        $eventRoleUse->enableMoodleUser(62, 'MOODLE_STUDENT');

//        $this->setUpLayout('list');
        \Yii::$app->view->params['enableLayoutList'] = true;
        \Yii::$app->view->params['enableChangeView'] = true;


        $moduleMoodle = \Yii::$app->getModule('moodle');
        $moduleEvents = \Yii::$app->getModule('events');

        $group_id = filter_input(INPUT_GET, 'group_id');
        if (empty($group_id)) {
            \Yii::$app->getSession()->addFlash('danger',
                \open20\amos\events\AmosEvents::t('amosevents', 'Pagina non accessibile direttamente'));
            $this->goBack();
        }
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => \open20\amos\events\AmosEvents::t('amosevents', 'Aggiungi utente al gruppo'),
            'urlCreateNew' => ['/events/event-group-referent-mm/create', 'group_id' => $group_id],
        ];
        Yii::$app->view->params['additionalButtons']['htmlButtons'] = [
            Html::a(AmosEvents::t('amosevents','Crea operatore'), ['/admin/user-profile/create','group_id' => $group_id], [
            'class' => 'btn btn-xs btn-primary mr-1'
        ])];

        $queryParams                                  = \yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),
                ['EventGroupReferentMmSearch' => [
                    'event_group_referent_id' => $group_id,
        ]]);


        $this->setDataProvider($this->modelSearch->search($queryParams));
        $this->getDataProvider()->query->andWhere(['exclude_from_query' => 1]);
        Url::remember();
        $courseCompletedUserIds = [];

        $enabledOperatorCandidate = false;
        if($moduleMoodle && $moduleEvents) {
            if(!empty($moduleEvents->operatorCandidate['enabled']) && !empty($moduleEvents->operatorCandidate['courseid'])) {
                $enabledOperatorCandidate = true;
                foreach ($this->getDataProvider()->models as $member) {
                    if(in_array($member->status,[EventGroupReferentMm::STATUS_ACTIVE, EventGroupReferentMm::STATUS_REQUEST_ACTIVATION])){
                        $courseCompletedUserIds [] = $member->user_id;
                    }
//                    if (\open20\amos\events\utility\EventsUtility::checkIfCourseIsCompleted($moduleEvents->operatorCandidate['courseid'], $member->user_id)) {
//                        $courseCompletedUserIds [] = $member->user_id;
//                    }
                }
            }
        }


        return $this->render(
            'index',
            [
                'enabledOperatorCandidate' => $enabledOperatorCandidate,
                'courseCompletedUserIds' => $courseCompletedUserIds,
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null,
                'moduleName' => ($this->moduleName) ? $this->moduleName : null,
                'contextModelId' => ($this->contextModelId) ? $this->contextModelId : null,
            ]
        );
    }

    /**
     * Displays a single EventGroupReferentMm model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new EventGroupReferentMm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($group_id)
    {
        $this->setUpLayout('form');
        $this->model = new EventGroupReferentMm();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['index', 'group_id' => $this->model->event_group_referent_id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create',
                [
                'model' => $this->model,
                'group_id' => $group_id,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new EventGroupReferentMm model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new EventGroupReferentMm();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax',
                [
                'model' => $this->model,
                'fid' => $fid,
                'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing EventGroupReferentMm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $group_id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update',
                [
                'model' => $this->model,
                'group_id' => $group_id,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing EventGroupReferentMm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            if(\Yii::$app->authManager->checkAccess($this->model->user_id, 'EVENT_DG')){
                $role = \Yii::$app->authManager->getRole('EVENT_DG');
                \Yii::$app->authManager->revoke($role, $this->model->user_id);
            }
            if(\Yii::$app->authManager->checkAccess($this->model->user_id, 'EVENT_DG_OPERATOR')){
                $role = \Yii::$app->authManager->getRole('EVENT_DG_OPERATOR');
                \Yii::$app->authManager->revoke($role, $this->model->user_id);
            }
            $this->model->delete();

            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success',
                    BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }


}