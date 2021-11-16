<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\controllers\base;

use Yii;
use open20\amos\comuni\models\IstatProvince;
use open20\amos\comuni\models\search\IstatProvinceSearch;
use open20\amos\core\controllers\CrudController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;
use open20\amos\comuni\AmosComuni;

/**
 * IstatProvinceController implements the CRUD actions for IstatProvince model.
 */
class IstatProvinceController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(new IstatProvince());
        $this->setModelSearch(new IstatProvinceSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosComuni::t('amoscomuni', '{iconaTabella}' . Html::tag('p', AmosComuni::t('amoscomuni', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /* 'list' => [
              'name' => 'list',
              'label' => AmosComuni::t('amoscomuni', '{iconaLista}'.Html::tag('p',AmosComuni::t('amoscomuni', 'Lista')), [
              'iconaLista' => AmosIcons::show('view-list')
              ]),
              'url' => '?currentView=list'
              ],
              'icon' => [
              'name' => 'icon',
              'label' => AmosComuni::t('amoscomuni', '{iconaElenco}'.Html::tag('p',AmosComuni::t('amoscomuni', 'Icone')), [
              'iconaElenco' => AmosIcons::show('view-grid')
              ]),
              'url' => '?currentView=icon'
              ],
              'map' => [
              'name' => 'map',
              'label' => AmosComuni::t('amoscomuni', '{iconaMappa}'.Html::tag('p',AmosComuni::t('amoscomuni', 'Mappa')), [
              'iconaMappa' => AmosIcons::show('map-alt')
              ]),
              'url' => '?currentView=map'
              ],
              'calendar' => [
              'name' => 'calendar',
              'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                    //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
              'label' => AmosComuni::t('amoscomuni', '{iconaCalendario}'.Html::tag('p',AmosComuni::t('amoscomuni', 'Calendario')), [
              'iconaCalendario' => AmosIcons::show('calendar')
              ]),
              'url' => '?currentView=calendar'
              ], */
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all IstatProvince models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single IstatProvince model.
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
     * Finds the IstatProvince model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IstatProvince the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IstatProvince::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new IstatProvince model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $model = new IstatProvince;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->addFlash('success', AmosComuni::t('amoscomuni', 'Elemento creato correttamente.'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosComuni::t('amoscomuni', 'Elemento non creato, verificare i dati inseriti.'));
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
     * Updates an existing IstatProvince model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->addFlash('success', AmosComuni::t('amoscomuni', 'Elemento aggiornato correttamente.'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosComuni::t('amoscomuni', 'Elemento non aggiornato, verificare i dati inseriti.'));
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
     * Deletes an existing IstatProvince model.
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
                Yii::$app->getSession()->addFlash('success', AmosComuni::t('amoscomuni', 'Elemento cancellato correttamente.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosComuni::t('amoscomuni', 'Elemento non cancellato per la presenza di dipendenze.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosComuni::t('amoscomuni', 'Elemento non trovato.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }
}
