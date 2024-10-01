<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione\controllers\base
 */

namespace open20\video\controllers\base;

use open20\video\Module;
use open20\video\utils\VideoUtil;
use Yii;
use open20\video\models\Video;
use open20\video\models\search\VideoSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use yii\helpers\Url;

/**
 * Class FormazioneController
 * FormazioneController implements the CRUD actions for Formazione model.
 *
 * @property \frontend\modules\formazione\models\Formazione $model
 * @property \frontend\modules\formazione\models\search\FormazioneSearch $modelSearch
 *
 * @package frontend\modules\formazione\controllers\base
 */
class VideoController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new Video());
        $this->setModelSearch(new VideoSearch());

        $this->viewIcon = [
            'name' => 'icon',
            'label' => AmosIcons::show('view-list') . Html::tag('p', Module::t('amosvideo', 'Card')),
            'url' => '?currentView=icon'
        ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::t('amosvideo', 'Table')),
            'url' => '?currentView=grid'
        ];

        $this->setAvailableViews([
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),
                'url' => '?currentView=icon'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],/*
            'list' => [
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
            ],*/
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all Formazione models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();

        $this->setAvailableViews([
            'grid' => [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::t('amosvideo', 'Table')),
            'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->viewGrid);

        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single Formazione model.
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
     * Creates a new Formazione model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new Video();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->community_id = VideoUtil::getScopeCommunity();
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
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
     * Creates a new Formazione model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new Video();

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
     * Updates an existing Formazione model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
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
     * Deletes an existing Formazione model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
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
        return $this->redirect(['index']);
    }


}
