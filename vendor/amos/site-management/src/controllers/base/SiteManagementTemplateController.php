<?php

namespace amos\sitemanagement\controllers\base;

use amos\sitemanagement\models\search\SiteManagementTemplateSearch;
use amos\sitemanagement\models\SiteManagementTemplate;
use amos\sitemanagement\models\SiteManagementTemplateFields;
use amos\sitemanagement\Module;
use amos\sitemanagement\widgets\icons\WidgetIconSiteManagementAdministration;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use Yii;
use yii\helpers\Url;

/**
 * SiteManagementTemplateController implements the CRUD actions for SiteManagementTemplate model.
 */
class SiteManagementTemplateController extends CrudController
{
    use TabDashboardControllerTrait;

    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new SiteManagementTemplate());
        $this->setModelSearch(new SiteManagementTemplateSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}' . Html::tag('p', Yii::t('amoscore', 'Table')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
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
            ],*/
        ]);

        parent::init();
    }

    /**
     * Lists all SiteManagementTemplate models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams();
        return parent::actionIndex();
    }

    /**
     * Displays a single SiteManagementTemplate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new SiteManagementTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout("form");
        $model = new SiteManagementTemplate;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                SiteManagementTemplateFields::deleteAll(['template_id' => $model->id]);
                if(!empty($model->fieldTypes)){
                    foreach($model->fieldTypes as $fieldType){
                        $templateFields = new SiteManagementTemplateFields();
                        $templateFields->template_id = $model->id;
                        $templateFields->field_id = $fieldType;
                        $templateFields->save();
                    }
                }
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
    }

    /**
     * Updates an existing SiteManagementTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout("form");
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                SiteManagementTemplateFields::deleteAll(['template_id' => $model->id]);
                if(!empty($model->fieldTypes)){
                    foreach($model->fieldTypes as $fieldType){
                        $templateFields = new SiteManagementTemplateFields();
                        $templateFields->template_id = $model->id;
                        $templateFields->field_id = $fieldType;
                        $templateFields->save();
                    }
                }
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
    }

    /**
     * Deletes an existing SiteManagementTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
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
    }
    /**
     * This method is useful to set all idea params for all list views.
     */
    protected function setListViewsParams($setCurrentDashboard = true)
    {
        $this->setUpLayout('list');
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
            $this->child_of = WidgetIconSiteManagementAdministration::className();

        }
        Yii::$app->session->set(Module::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(Module::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

}
