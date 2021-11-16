<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\landings\controllers\base
 */

namespace backend\modules\landings\controllers\base;

use backend\modules\landings\models\PreferenceLanding;
use backend\modules\landings\models\PreferenceLandingLink;
use backend\modules\landings\Module;
use Yii;
use backend\modules\landings\models\search\PreferenceLandingLinkSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;


/**
 * Class PreferenceLandingLinksController
 * PreferenceLandingLinksController implements the CRUD actions for PreferenceLandingLink model.
 *
 * @property \backend\modules\landings\models\PreferenceLandingLink $model
 * @property \backend\modules\landings\models\search\PreferenceLandingLinkSearch $modelSearch
 *
 * @package backend\modules\landings\controllers\base
 */
class PreferenceLandingLinksController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new PreferenceLandingLink());
        $this->setModelSearch(new PreferenceLandingLinkSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
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
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);
    }

    /**
     * Lists all PreferenceLandingLink models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        throw new ForbiddenHttpException();
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single PreferenceLandingLink model.
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
     * Creates a new PreferenceLandingLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $landing = PreferenceLanding::findOne(['id' => $id]);
        if (empty($landing)) {
            throw new yii\web\BadRequestHttpException('landing non impostata');
        }

        $this->model = new PreferenceLandingLink();
        $this->model->preference_landing_id = $id;
        $this->model->sort_value = $this->model->getLandingMaxSortValue() + 1;

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Module::t('landings', 'Link creato'));
                return $this->redirect(Url::to([
                    '/landings/preference-landing/update',
                    'id' => $this->model->preference_landing_id,
                    '#' => 'pos-links',
                ]));
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('landings', 'Link non creato controlla i dati inseriti'));
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
     * Creates a new PreferenceLandingLink model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreateAjax($fid, $dataField)
//    {
//        
//        $this->model = new PreferenceLandingLink();
//
//        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
//            if ($this->model->save()) {
////Yii::$app->getSession()->addFlash('success', Module::t('landings', 'Item created'));
//                return json_encode($this->model->toArray());
//            } else {
////Yii::$app->getSession()->addFlash('danger', Module::t('landings', 'Item not created, check data'));
//            }
//        }
//
//        return $this->renderAjax('_formAjax', [
//            'model' => $this->model,
//            'fid' => $fid,
//            'dataField' => $dataField
//        ]);
//    }

    /**
     * Updates an existing PreferenceLandingLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Module::t('landings', 'Link aggiornato'));
                return $this->redirect(Url::to([
                    '/landings/preference-landing/update',
                    'id' => $this->model->preference_landing_id,
                    '#' => 'pos-links',
                    ]));
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('landings', 'Link non aggiornato, controlla i dati inseriti'));
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
     * Deletes an existing PreferenceLandingLink model.
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
                Yii::$app->getSession()->addFlash('success', Module::t('landings', 'Link eliminato correttamente.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('landings', 'Non sei autorizzato ad eliminare questo link.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Link non trovato.'));
        }
        return $this->redirect(Url::to([
            '/landings/preference-landing/update',
            'id' => $this->model->preference_landing_id,
            '#' => 'pos-links',
        ]));;
    }
}
