<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\operators\models\base
 */

namespace open20\agid\person\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\agid\person\models\AgidPerson;
use open20\agid\person\models\AgidPersonOrganizationalUnitMm;
use open20\agid\person\models\search\AgidPersonSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use open20\agid\person\Module;
use yii\data\ActiveDataProvider;

/**
 * Class AgidPersonController
 * AgidPersonController implements the CRUD actions for AgidPerson model.
 *
 * @property \backend\modules\operators\models\AgidPerson $model
 * @property \backend\modules\operators\models\AgidPersonSearch $modelSearch
 *
 * @package backend\modules\operators\models\base
 */
class AgidPersonController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new AgidPerson());
        $this->setModelSearch(new AgidPersonSearch());

        // default status of search model 
        $this->modelSearch->status = null;

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],

        ]);
        $this->setUpLayout();
        parent::init();

    }


    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        $createBtnTitle = Module::t('amosperson', '#add_person');
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => $createBtnTitle,
            'urlCreateNew' => [(array_key_exists("noWizardNewLayout", Yii::$app->params) ? '/person/agid-person/create' : '/person/agid-person')],
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
     * Lists all AgidPerson models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(Module::t('amosservice', 'Agid Persone'));
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));

        // rigenerazione del dataProvider per il sort dei campi
		$this->dataProvider = new ActiveDataProvider([
			'query' => $this->dataProvider
                        ->query
                        // ->joinWith('agidPersonContentType', true)
                        ->joinWith('agidPersonType', true),
			'sort' => [
				'attributes' => [

					// Normal columns
					'id',
					'name',
					'surname',
					'role',
					'telephone',
					'email',
					'updated_by',
					'updated_at',
					'status',

					// Related columns
                    'agidPersonType.name' => [
                        'asc' => ['agid_person_type.name' => SORT_ASC],
						'desc' => ['agid_person_type.name' => SORT_DESC],
						'default' => SORT_ASC
					],
				]
			]
		]);

        // set sort order by created_at / id
        $sort = $this->dataProvider->getSort();
        $sort->defaultOrder = ['id' => SORT_DESC];
        $this->dataProvider->setSort($sort);

        return parent::actionIndex('list');
    }

    /**
     * Displays a single AgidPerson model.
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
     * Creates a new AgidPerson model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
         $this->setUpLayout('form');
        $this->model = new AgidPerson();
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            if ($this->model->save()) {

                $this->model->createRelations();

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Il contenuto è stato creato con successo.'));
                return $this->redirect(['update', 'id' => $this->model->id]);

            } else {

                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Errore! Il contenuto non è stato creato, verificare i dati inseriti nel form.'));
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
     * Creates a new AgidPerson model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new AgidPerson();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
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
            'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing AgidPerson model.
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

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Il contenuto è stato aggiornato con successo.'));
                return $this->redirect(['update', 'id' => $this->model->id]);

            } else {

                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Errore! Il contenuto non è stato aggiornato, verificare i dati inseriti nel form.'));
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
     * Deletes an existing AgidPerson model.
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
