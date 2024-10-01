<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    app\controllers\base
 */

namespace open20\agid\dataset\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use Yii;
use yii\helpers\Url;
use open20\agid\dataset\models\search\AgidDatasetSearch;
use open20\agid\dataset\models\AgidDataset;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


/**
 * Class AgidDatasetController
 * AgidDatasetController implements the CRUD actions for AgidDataset model.
 *
 * @property \open20\agid\dataset\models\AgidDataset $model
 * @property \open20\agid\dataset\models\AgidDatasetSearch $modelSearch
 *
 * @package open20\agid\dataset\controllers\base
 */
class AgidDatasetController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';
    
    public function init()
    {
        $this->setModelObj(new AgidDataset());
        $this->setModelSearch(new AgidDatasetSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid',
            ]
        ]);

        parent::init();
        $this->setUpLayout();
    }

	/**
	 * Lists all AgidDataset models.
	 * @return mixed
	 */
    public function actionIndex($layout = null)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));

        // regeneration of the dataProvider for sorting the fields
		$this->dataProvider = new ActiveDataProvider([
			'query' => $this->dataProvider
                        ->query
                        ->joinWith('agidDatasetType', true),

			'sort' => [
				'attributes' => [

					//Normal columns
					'title',
					'status',

					//related columns
                    'agidDatasetType.name' => [
                        'asc' => ['agid_dataset_type.name' => SORT_ASC],
						'desc' => ['agid_dataset_type.name' => SORT_DESC],
						'default' => SORT_ASC
                    ]
				]
			]
		]);

        return parent::actionIndex($layout);
    }

	/**
	 * Displays a single AgidDataset model.
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
	 * Creates a new AgidDataset model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new AgidDataset();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'I dati sono stati salvati con successo.'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Errore! Non è stato possibile salvare i dati. Verificare i campi inseriti.'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null,
        ]);
    }

	/**
	 * Creates a new AgidDataset model by ajax request.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new AgidDataset();

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
            'dataField' => $dataField,
        ]);
    }

	/**
	 * Updates an existing AgidDataset model.
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
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'I dati sono stati salvati con successo.'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Errore! Non è stato possibile salvare i dati.'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null,
        ]);
    }

	/**
	 * Deletes an existing AgidDataset model.
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
