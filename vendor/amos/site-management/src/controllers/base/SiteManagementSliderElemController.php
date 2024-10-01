<?php

namespace amos\sitemanagement\controllers\base;

use amos\sitemanagement\models\search\SiteManagementSliderElemSearch;
use amos\sitemanagement\models\SiteManagementSlider;
use amos\sitemanagement\models\SiteManagementSliderElem;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * SiteManagementSliderElemController implements the CRUD actions for SiteManagementSliderElem model.
 */
class SiteManagementSliderElemController extends CrudController
{

    public function init()
    {
        $this->setModelObj(new SiteManagementSliderElem());
        $this->setModelSearch(new SiteManagementSliderElemSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}'.Html::tag('p', Yii::t('amoscore', 'Table')),
                    [
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
     * Lists all SiteManagementSliderElem models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single SiteManagementSliderElem model.
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
     * Creates a new SiteManagementSliderElem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $urlRedirect = null, $useCrop = null, $ratioCrop = null, $slider_type = null, $onlyImages = false, $onlyVideos = false, $onlyInstagramVideos = false)
    {
        $module = \Yii::$app->getModule('sitemanagement');
        $this->setUpLayout("form");

        $files  = [];
        $slider = SiteManagementSlider::findOne($id);
        if (empty($slider)) {
            throw new NotFoundHttpException('Forbidden');
        }


        $orderByLastInsert = false;

        if ($module) {
            $orderByLastInsert = $module->orderContentByLastInsert;
            $files             = $module->getFileNamesDirectoryForUploadVideo();
        }

        $model = new SiteManagementSliderElem;

        $model->slider_id = $slider->id;
        if($slider_type){
            $model->type = $slider_type;
        }
        $lastOrder = 0;

        if ($orderByLastInsert == false) {
            $lastOrder = $slider->getLastElementOrder();
        }

        $model->order = $lastOrder + 1;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {            
            if ($model->save()) {
                $this->shiftElement($model->slider_id, $model->order, $model->id);
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                //redirect
                if (empty($urlRedirect)) {
                    return $this->redirect(['/sitemanagement/site-management-slider/update', 'id' => $slider->id]);
                } else {
                    return $this->redirect(urldecode($urlRedirect));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
                return $this->render('create',
                        [
                        'model' => $model,
                        'slider' => $slider,
                        'files' => $files,
                        'useCrop' => $useCrop,
                        'ratioCrop' => $ratioCrop,
                        'onlyImages' => $onlyImages,
                        'onlyVideos' => $onlyVideos,
                        'onlyInstagramVideos' => $onlyInstagramVideos
                ]);
            }
        } else {
            return $this->render('create',
                    [
                    'model' => $model,
                    'slider' => $slider,
                    'files' => $files,
                    'useCrop' => $useCrop,
                    'ratioCrop' => $ratioCrop,
                    'onlyImages' => $onlyImages,
                    'onlyVideos' => $onlyVideos,
                    'onlyInstagramVideos' => $onlyInstagramVideos
            ]);
        }
    }

    /**
     *
     * @param integer $slider
     * @param integer $order
     * @param integer $my_id
     * @param integer $old_order
     * @param integer $new_order
     * @param integer $value
     */
    protected function shiftElement($slider, $order, $my_id = null, $old_order = null, $value = 1)
    {
        $query = SiteManagementSliderElem::find()->andWhere(['slider_id' => $slider]);
        if ($my_id !== null) {
            $query->andWhere(['!=', 'id', $my_id]);
        }
        //pr($order, 'order');pr($value, 'value');
        $allContents = $query->orderBy('order')->all();
        foreach ($allContents as $v) {
            if ($old_order !== null) {
                if ($old_order < $order) {
                    if ($v->order <= $order && $v->order > $old_order) {
                        $v->order = $v->order - $value;
                        $v->save(false);
                    }
                } else if ($old_order > $order) {
                    if ($v->order >= $order && $v->order < $old_order) {
                        $v->order = $v->order + $value;
                        $v->save(false);
                    }
                }
            } else {
                if ($v->order >= $order) {                  
                    $v->order = $v->order + $value;                   
                    $v->save(false);
                }
            }
        }
    }

    /**
     * Updates an existing SiteManagementSliderElem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $urlRedirect = null, $useCrop = null, $ratioCrop = null, $onlyImages = false, $onlyVideos = false)
    {
        $files  = [];
        $module = \Yii::$app->getModule('sitemanagement');
        if ($module) {
            $files = $module->getFileNamesDirectoryForUploadVideo();
        }

        $this->setUpLayout("form");
        $model    = $this->findModel($id);
        $oldOrder = $model->order;
        $slider   = SiteManagementSlider::findOne($model->slider_id);
        if (empty($slider)) {
            throw new NotFoundHttpException('Forbidden');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
            $this->shiftElement($model->slider_id, $model->order, $model->id, $oldOrder);
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                // redirect
                if (empty($urlRedirect)) {
                    return $this->redirect(['/sitemanagement/site-management-slider/update', 'id' => $slider->id]);
                } else {
                    return $this->redirect(urldecode($urlRedirect));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
                return $this->render('update',
                        [
                        'model' => $model,
                        'slider' => $slider,
                        'files' => $files,
                        'useCrop' => $useCrop,
                        'ratioCrop' => $ratioCrop,
                        'onlyImages' => $onlyImages,
                        'onlyVideos' => $onlyVideos,
                ]);
            }
        } else {
            return $this->render('update',
                    [
                    'model' => $model,
                    'slider' => $slider,
                    'files' => $files,
                    'useCrop' => $useCrop,
                    'ratioCrop' => $ratioCrop,
                    'onlyImages' => $onlyImages,
                        'onlyVideos' => $onlyVideos,
            ]);
        }
    }

    /**
     * Deletes an existing SiteManagementSliderElem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $urlRedirect = null)
    {
        $model     = $this->findModel($id);
        $slider_id = $model->slider_id;
        if ($model) {
//si può sostituire il  delete() con forceDelete() in caso di SOFT DELETE attiva 
//In caso di soft delete attiva e usando la funzione delete() non sarà bloccata
//la cancellazione del record in presenza di foreign key quindi 
//il record sarà cancelleto comunque anche in presenza di tabelle collegate a questo record
//e non saranno cancellate le dipendenze e non avremo nemmeno evidenza della loro presenza
//In caso di soft delete attiva è consigliato modificare la funzione oppure utilizzare il forceDelete() che non andrà 
//mai a buon fine in caso di dipendenze presenti sul record da cancellare
            $model->delete();
            Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item deleted'));
        } else {
            Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not found'));
        }
        if ($urlRedirect) {
            return $this->redirect(urldecode($urlRedirect));
        } else {
            return $this->redirect(['/sitemanagement/site-management-slider/update', 'id' => $slider_id]);
        }
    }
}