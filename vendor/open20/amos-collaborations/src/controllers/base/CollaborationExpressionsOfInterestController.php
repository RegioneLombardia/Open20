<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\controllers\base
 */

namespace open20\amos\collaborations\controllers\base;

use open20\amos\collaborations\events\CollaborationExpressionsOfInterestEvent;
use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\collaborations\models\search\CollaborationExpressionsOfInterestSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


/**
 * Class CollaborationExpressionsOfInterestController
 * CollaborationExpressionsOfInterestController implements the CRUD actions for CollaborationExpressionsOfInterest model.
 *
 * @property \open20\amos\collaborations\models\CollaborationExpressionsOfInterest $model
 * @property \open20\amos\collaborations\models\search\CollaborationExpressionsOfInterestSearch $modelSearch
 *
 * @package open20\amos\collaborations\controllers\base
 */
class CollaborationExpressionsOfInterestController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * Init
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->setModelObj(new CollaborationExpressionsOfInterest());
        $this->setModelSearch(new CollaborationExpressionsOfInterestSearch());

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                'url' => '?currentView=list'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /*'icon' => [
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
            ],*/
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all CollaborationExpressionsOfInterest models.
     * @return string
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->searchCreatedByMe(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single CollaborationExpressionsOfInterest model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
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
     * Creates a new CollaborationExpressionsOfInterest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new CollaborationExpressionsOfInterest();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                //Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                (new CollaborationExpressionsOfInterestEvent)->sendNotifyToCollaborationProposalInterested($this->model);
                return $this->render('parts/thank_you');
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new CollaborationExpressionsOfInterest model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return  false|string
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new CollaborationExpressionsOfInterest();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing CollaborationExpressionsOfInterest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing CollaborationExpressionsOfInterest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param $model
     * @return bool
     */
    public function viewReadAllBtn($model)
    {
        return (Yii::$app->user->can('COLLABORATIONEXPRESSIONSOFINTEREST_READ', [
            'model' => $model
        ]));
    }
}
