<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow\controllers\base
 * @category   CategoryName
 */

namespace open20\amos\slideshow\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\slideshow\AmosSlideshow;
use open20\amos\slideshow\models\search\SlideshowRouteSearch;
use open20\amos\slideshow\models\SlideshowRoute;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class SlideshowRouteController
 *
 * SlideshowRouteController implements the CRUD actions for SlideshowRoute model.
 *
 * @property \open20\amos\slideshow\models\SlideshowRoute $model
 *
 * @package open20\amos\slideshow\controllers\base
 */
class SlideshowRouteController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(new SlideshowRoute());
        $this->setModelSearch(new SlideshowRouteSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosSlideshow::t('amosslideshow', '{iconaTabella}' . Html::tag('p', AmosSlideshow::t('amosslideshow', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
                'name' => 'list',
                'label' => AmosSlideshow::t('amosslideshow', '{iconaLista}' . Html::tag('p', AmosSlideshow::t('amosslideshow', 'Lista')), [
                    'iconaLista' => AmosIcons::show('view-list')
                ]),
                'url' => '?currentView=list'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosSlideshow::t('amosslideshow', '{iconaElenco}' . Html::tag('p', AmosSlideshow::t('amosslideshow', 'Icone')), [
                    'iconaElenco' => AmosIcons::show('grid')
                ]),
                'url' => '?currentView=icon'
            ],*/
        ]);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * Lists all SlideshowRoute models.
     * @param null|string $layout
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single SlideshowRoute model.
     * @param int $id
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
     * Creates a new SlideshowRoute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $this->model = new SlideshowRoute();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', AmosSlideshow::tHtml('amosslideshow', 'Elemento creato correttamente.'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosSlideshow::tHtml('amosslideshow', 'Elemento non creato, verificare i dati inseriti.'));
                return $this->render('create', [
                    'model' => $this->model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $this->model,
            ]);
        }
    }

    /**
     * Updates an existing SlideshowRoute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', AmosSlideshow::tHtml('amosslideshow', 'Elemento aggiornato correttamente.'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosSlideshow::tHtml('amosslideshow', 'Elemento non aggiornato, verificare i dati inseriti.'));
                return $this->render('update', [
                    'model' => $this->model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $this->model,
            ]);
        }
    }

    /**
     * Deletes an existing SlideshowRoute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', AmosSlideshow::tHtml('amosslideshow', 'Elemento cancellato correttamente.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosSlideshow::tHtml('amosslideshow', 'Elemento non cancellato per la presenza di dipendenze.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosSlideshow::tHtml('amosslideshow', 'Elemento non trovato.'));
        }
        return $this->redirect(['index']);
    }
}
