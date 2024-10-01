<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\controllers\base
 */

namespace open20\prodottiservizi\controllers\base;

use open20\prodottiservizi\assets\ModuleProdottiServiziAsset;
use open20\prodottiservizi\models\ProdottiDestinatari;
use open20\prodottiservizi\models\ProdottiFinalita;
use open20\prodottiservizi\models\ProdottiServiziAccordion;
use open20\prodottiservizi\models\ProdottiServiziDestinatariMm;
use open20\prodottiservizi\models\ProdottiServiziFinalitaMm;
use open20\prodottiservizi\models\ProdottiSoluzioni;
use open20\prodottiservizi\models\search\ProdottiServiziAccordionSearch;
use open20\prodottiservizi\Module;
use open20\prodottiservizi\utility\ProdottiServiziUtility;
use Yii;
use open20\prodottiservizi\models\ProdottiServizi;
use open20\prodottiservizi\models\search\ProdottiServiziSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;

/**
 * Class ProdottiServiziController
 * ProdottiServiziController implements the CRUD actions for ProdottiServizi model.
 *
 * @property \open20\prodottiservizi\models\ProdottiServizi $model
 * @property open20\prodottiservizi\models\search\ProdottiServiziSearch $modelSearch
 *
 * @package open20\prodottiservizi\controllers\base
 */
class ProdottiServiziController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new ProdottiServizi());
        $this->setModelSearch(new ProdottiServiziSearch());

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
        ModuleProdottiServiziAsset::register($this->view);
    }

    /**
     * Lists all ProdottiServizi models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $params = Yii::$app->request->getQueryParams();
        $this->setDataProvider($this->modelSearch->search($params));

        // Settaggio del titolo aggiornato
        $scope = $params['ProdottiServiziSearch']['scope'];
        if(isset($scope) && $scope > 0 && $scope <= 3) {
            $articolo = ($scope == 3) ? 'i' : 'le';
            $this->view->params['titleSection'] .= " per $articolo " . ProdottiServiziUtility::getAmbitoLabel($scope);
        }

        // Setto solo la view che interessa
        $this->setAvailableViews([
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),
                'url' => '?currentView=icon'
            ]
        ]);
        
        // Disabilito i filtri
        $this->module->params['searchParams'][$this->id]['enable'] = false;

        // Settaggio parametri
        $this->parametro = [];
        $this->parametro['soluzioni'] = ProdottiSoluzioni::find()->orderBy('name ASC')->all();
        $this->parametro['finalita'] = ProdottiFinalita::find()->orderBy('name ASC')->all();
        $this->parametro['scope'] = isset(Yii::$app->request->getQueryParam('ProdottiServiziSearch')['scope']) ?
            Yii::$app->request->getQueryParam('ProdottiServiziSearch')['scope'] : 0;
        if($this->parametro['scope'] != 0){
            $this->parametro['destinatari'] = ProdottiDestinatari::find()
                ->andWhere(['parent' => $this->parametro['scope']])
                ->orderBy('name ASC')
                ->all();
        }
        else{
            $this->parametro['destinatari'] = ProdottiDestinatari::find()
                ->orderBy('name ASC')
                ->all();
        }

        return parent::actionIndex($layout);
    }

    /**
     * Displays a single ProdottiServizi model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            if(is_null($this->model->id_product_root)) {
                $sportelli = $this->model->sportelli;
            } else {
                $model = ProdottiServizi::findOne($this->model->id_product_root);
                $sportelli = $model->sportelli;
            }

            // Accordion dinamici
            $modelAccordionSearch = new ProdottiServiziAccordionSearch();
            $params['ProdottiServiziAccordionSearch']['prodotto_id'] = $id;
            $params['ProdottiServiziAccordionSearch']['is_visible'] = 1;
            $dataProviderAccordion = $modelAccordionSearch->search($params);

            return $this->render('view', [
                'model' => $this->model,
                'sportelli' => $sportelli,
                'dataProviderAccordion' => $dataProviderAccordion
            ]);
        }
    }

    /**
     * Creates a new ProdottiServizi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new ProdottiServizi();

        if ($this->model->load(Yii::$app->request->post())) {
            if($this->model->validate()) {
                if ($this->model->save()) {
                    ProdottiServiziUtility::addAccordionToNewProdotto($this->model->id);
                    Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('prodottiservizi', 'Item created'));
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('prodottiservizi', 'Si &egrave; verificato un errore durante il salvataggio.'));
                }
            }
            else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('prodottiservizi', 'Item not created, check data'));
            }
        }
        $solutions = ArrayHelper::map(ProdottiSoluzioni::find()->orderBy('name ASC')->all(), 'id', 'name');
        $finalita = ArrayHelper::map(ProdottiFinalita::find()->all(), 'id', 'name');
        $destinatari = ArrayHelper::map(ProdottiDestinatari::find()->orderBy('name ASC')->andWhere(['parent' => $this->model->scope])->all(), 'id', 'name');

        $modelAccordionSearch = new ProdottiServiziAccordionSearch();
        $dataProviderAccordion = $modelAccordionSearch->search();

        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
            'solutions' => $solutions,
            'finalita' => $finalita,
            'destinatari' => $destinatari,
            'dataProviderAccordion' => $dataProviderAccordion,
            'currentViewAccordion' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ]
        ]);
    }

    /**
     * Creates a new ProdottiServizi model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new ProdottiServizi();

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
     * Updates an existing ProdottiServizi model.
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
        $solutions = ArrayHelper::map(ProdottiSoluzioni::find()->all(), 'id', 'name');

        $destinatari = ArrayHelper::map(ProdottiDestinatari::find()->andWhere(['parent' => $this->model->scope])->all(), 'id', function($model) {return $model->name;});
        $finalita = ArrayHelper::map(ProdottiFinalita::find()->all(), 'id', function($model) {return $model->name;});

        $modelAccordionSearch = new ProdottiServiziAccordionSearch();
        $params['ProdottiServiziAccordionSearch']['prodotto_id'] = $id;
        $dataProviderAccordion = $modelAccordionSearch->search($params);

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
            'solutions' => $solutions,
            'destinatari' => $destinatari,
            'finalita' => $finalita,
            'dataProviderAccordion' => $dataProviderAccordion,
            'currentViewAccordion' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ]
        ]);
    }

    /**
     * Deletes an existing ProdottiServizi model.
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
