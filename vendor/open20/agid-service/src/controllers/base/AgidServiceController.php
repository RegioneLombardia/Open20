<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\controllers\base
 * @category   CategoryName
 */

namespace open20\agid\service\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\agid\service\models\AgidService;
use open20\agid\service\models\search\AgidServiceSearch;
use open20\agid\service\Module;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

/**
 * Class AgidServiceController
 * AgidServiceController implements the CRUD actions for AgidService model.
 *
 * @property AgidService $model
 * @property AgidServiceSearch $modelSearch
 *
 */
abstract class AgidServiceController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';
    
    /**
     * @var \open20\amos\cwh\AmosCwh $moduleCwh
     */
    public $moduleCwh;
    
    /**
     * @var array $scope
     */
    public $scope;
    
    /**
     * @inheridoc
     */
    public function init()
    {
        $this->setModelObj(new AgidService());
        $this->setModelSearch(new AgidServiceSearch());

        // default status of search model 
        $this->modelSearch->status = null;
        
        $this->scope = null;
        $this->moduleCwh = Yii::$app->getModule('cwh');
        
        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }
        
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        
        ]);
        
        parent::init();
        
        $this->setUpLayout();
    }
    
    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        $createBtnTitle = Module::t('amosservice', '#add_new_service');
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => $createBtnTitle,
            'urlCreateNew' => ['/service/agid-service/create'],
            'otherOptions' => ['title' => $createBtnTitle, 'class' => 'btn btn-primary']
        ];
    }
    
    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams()
    {
        $this->setCreateNewBtnParams();
        $this->setUpLayout('list');
        Yii::$app->session->set(Module::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(Module::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }
    
    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle News page title (ie. Created by news, ...)
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->session->set('previousTitle', $pageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }
    
    /**
     * Lists all AgidService models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(Module::t('amosservice', 'Services'));
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));

        // rigenerazione del dataProvider per il sort dei campi
		$this->dataProvider = new ActiveDataProvider([
			'query' => $this->dataProvider
                        ->query
                        ->joinWith('agidServiceType', true)
                        ->joinWith('agidServiceStatus', true)
                        ->joinWith('agidUoManager', true),
			'sort' => [
				'attributes' => [

					//Normal columns
					'id',
					'name',
					'updated_by',
					'updated_at',
					'status',

					//related columns
                    'agidServiceType.name' => [
                        'asc' => ['agid_service_type.name' => SORT_ASC],
						'desc' => ['agid_service_type.name' => SORT_DESC],
						'default' => SORT_ASC
                    ],
                    
                    'agidServiceStatus.name' => [
                        'asc' => ['agid_service_status.name' => SORT_ASC],
						'desc' => ['agid_service_status.name' => SORT_DESC],
						'default' => SORT_ASC
                    ],

                    'agidUoManager.name' => [
                        'asc' => ['agid_organizational_unit.name' => SORT_ASC],
						'desc' => ['agid_organizational_unit.name' => SORT_DESC],
						'default' => SORT_ASC
                    ],

                    'agidUoArea.name' => [
                        'asc' => ['agid_organizational_unit.name' => SORT_ASC],
						'desc' => ['agid_organizational_unit.name' => SORT_DESC],
						'default' => SORT_ASC
                    ],
				]
			]
		]);
        
        // set sort order by created_at / id
        $sort = $this->dataProvider->getSort();
        $sort->defaultOrder = ['id' => SORT_DESC];
        $this->dataProvider->setSort($sort);

        return parent::actionIndex($layout);
    }
    
    /**
     * Displays a single AgidService model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $this->setUpLayout('form');
        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }
    
    /**
     * Creates a new AgidService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new AgidService();
        
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                $this->model->createRelations();
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Il servizio è stato creato con successo.'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Il servizio non è stato creato, controllare i dati inseriti nel form.'));
            }
        }
        
        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
            'moduleCwh' => $this->moduleCwh,
            'scope' => $this->scope
        ]);
    }
    
    /**
     * Creates a new AgidService model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new AgidService();
        
        if (Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                //Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
                //Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }
        
        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField,
            'moduleCwh' => $this->moduleCwh,
            'scope' => $this->scope
        ]);
    }
    
    /**
     * Updates an existing AgidService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        $this->model->loadRelations();
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                $this->model->updateRelations();
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Il servizio è stato aggiornato con successo.'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Il servizio non è stato aggiornato, controllare i dati inseriti nel form.'));
            }
        }
        
        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
            'moduleCwh' => $this->moduleCwh,
            'scope' => $this->scope
        ]);
    }
    
    /**
     * Deletes an existing AgidService model.
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
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Elemento eliminato con successo.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Non sei autorizzato a eliminare questo elemento.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Elemento non trovato.'));
        }
        return $this->redirect(['index']);
    }
}
