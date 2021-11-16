<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniCommenti;
use open20\amos\discussioni\models\search\DiscussioniCommentiSearch;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class DiscussioniCommentiController
 * DiscussioniCommentiController implements the CRUD actions for DiscussioniCommenti model.
 * @package open20\amos\discussioni\controllers
 * @deprecated from version 1.5.
 */
class DiscussioniCommentiController extends CrudController
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
        $this->setModelObj(new DiscussioniCommenti());
        $this->setModelSearch(new DiscussioniCommentiSearch());
        
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosDiscussioni::t('amosdiscussioni', '{iconaTabella}' . Html::tag('p', AmosDiscussioni::t('amosdiscussioni', 'Table')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /* 'map' => [
              'name' => 'map',
              'label' => AmosDiscussioni::t('amosdiscussioni', '{iconaMappa}'.Html::tag('p',AmosDiscussioni::t('amosdiscussioni', 'Map')), [
              'iconaMappa' => AmosIcons::show('map-alt')
              ]),
              'url' => '?currentView=map'
              ], */
        ]);
        
        parent::init();
        $this->setUpLayout();
    }
    
    /**
     * Lists all DiscussioniCommenti models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }
    
    /**
     * Displays a single DiscussioniCommenti model.
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
     * Finds the DiscussioniCommenti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DiscussioniCommenti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DiscussioniCommenti::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(AmosDiscussioni::t('amosdiscussioni', 'La pagina richiesta non esiste'));
        }
    }
    
    /**
     * Creates a new DiscussioniCommenti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $model = new DiscussioniCommenti;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $model;
            }
            return $this->redirect(Url::previous());
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing DiscussioniCommenti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Url::previous());
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing DiscussioniCommenti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
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
