<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    app\controllers\base
 */

namespace open20\agid\organizationalunit\controllers\base;

use Yii;
use Exception;
use Throwable;
use yii\helpers\Url;
use yii\db\Transaction;
use open20\amos\core\helpers\Html;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\controllers\CrudController;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitSearch;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitDocumentiMm;
use open20\agid\organizationalunit\models\AgidOrganizationalOrganizationalUnitMm;
use open20\amos\core\icons\AmosIcons;
use yii\data\ActiveDataProvider;

/**
 * Class AgidOrganizationalUnitController
 * AgidOrganizationalUnitController implements the CRUD actions for AgidOrganizationalUnit model.
 *
 * @property \open20\agid\organizationalunit\models\AgidOrganizationalUnit $model
 * @property \open20\agid\organizationalunit\models\AgidOrganizationalUnitSearch $modelSearch
 *
 * @package open20\agid\organizationalunit\controllers\base
 */
class AgidOrganizationalUnitController extends CrudController
{
       

    public function init()
    {
        $this->layout = 'main';
        // Yii::$app->layoutmanager->setLayout($this);
        $this->setModelObj(new AgidOrganizationalUnit());
        $this->setModelSearch(new AgidOrganizationalUnitSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid',
            ],
        ]);

        parent::init();
        
    }

	/**
	 * Lists all AgidOrganizationalUnit models.
	 * @return mixed
	 */
    public function actionIndex($layout = null)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->view->params['enablePLuginToolbar'] = true;
        // return parent::actionIndex($this->layout);

        // rigenerazione del dataProvider per il sort dei campi
		$this->dataProvider = new ActiveDataProvider([
			'query' => $this->dataProvider
                        ->query
                        ->joinWith('agidOrganizationalUnitContentType', true)
                        ->joinWith('agidOrganizationalUnitType', true),
			'sort' => [
				'attributes' => [

					//Normal columns
					'id',
					'name',
					'updated_by',
					'updated_at',
					'status',

					//related columns
					'agidOrganizationalUnitContentType.name' => [
						'asc' => ['agid_organizational_unit_content_type.name' => SORT_ASC],
						'desc' => ['agid_organizational_unit_content_type.name' => SORT_DESC],
						'default' => SORT_ASC
                    ],

                    'agidOrganizationalUnitType.name' => [
                        'asc' => ['agid_organizational_unit_type.name' => SORT_ASC],
						'desc' => ['agid_organizational_unit_type.name' => SORT_DESC],
						'default' => SORT_ASC
                    ]
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
	 * Displays a single AgidOrganizationalUnit model.
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
	 * Creates a new AgidOrganizationalUnit model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
    public function actionCreate()
    {

        $this->model = new AgidOrganizationalUnit();
        
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            // start transaction 
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                
                // create AgidOrganizationalUnit
                if( $this->model->save() ){

                    $post_request = Yii::$app->request->post();

                    // create AgidOrganizationaOrganizationalUnitMM
                    $agid_organizational_unit = Yii::$app->request->post();
                    
                    foreach ($agid_organizational_unit['AgidOrganizationalUnit']['agid_organizational_organizational_unit_mm'] as $key => $value) {
                        
                        // create AgidOrganizationalOrganizationalUnitMm
                        $agid_organizational_organizational_unit_mm = new AgidOrganizationalOrganizationalUnitMm;

                        $agid_organizational_organizational_unit_mm->agid_organizational_unit_id = $this->model->id;
                        $agid_organizational_organizational_unit_mm->agid_organizational_unit_father_id = $value;

                        // $agid_organizational_organizational_unit_mm->save();

                        if( !$agid_organizational_organizational_unit_mm->save() ){
                            throw new Exception("Errore! Non è stato possibile creare il Legame con le altre strutture.", 1);
                        }
                    }


                    // create AgidOrganizationalUnitDocumentiMm
                    foreach ($agid_organizational_unit['AgidOrganizationalUnit']['agid_organizational_unit_documenti_mm'] as $key => $value) {

                        // create AgidOrganizationalUnitDocumentiMm
                        $agid_organizational_unit_documenti_mm = new AgidOrganizationalUnitDocumentiMm;

                        $agid_organizational_unit_documenti_mm->agid_organizational_unit_id = $this->model->id;
                        $agid_organizational_unit_documenti_mm->documenti_id = $value;

                        // $agid_organizational_unit_documenti_mm->save();

                        if( !$agid_organizational_unit_documenti_mm->save() ){
                            throw new Exception("Errore! Non è stato possibile creare il Legame con le altre strutture.", 1);
                        }
                    }
                
                    // create AgidOrganizationalUnitServiceMm
                    $this->model->createAgidOrganizationalUnitServicesMm();

                    // commit transaction
                    $transaction->commit();

                }else{

                    throw new Exception("Errore! La creazione dell'Unità Organizzativa non è andata a buon fine.", 1);
                }

            } catch (\Throwable $th) {
                
                $transaction->rollBack();
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', $th->getMessage()));

                return $this->redirect(['create', 'id' => $this->model->id]);
            }
          
            Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', "L'Unità Organizzativa è stata creata con successo."));
            return $this->redirect(['update', 'id' => $this->model->id]);

        }
        

        return $this->render('create', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null,
        ]);
    }

	/**
	 * Creates a new AgidOrganizationalUnit model by ajax request.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->model = new AgidOrganizationalUnit();

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
	 * Updates an existing AgidOrganizationalUnit model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
    public function actionUpdate($id)
    {

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            // start transaction 
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                    
                if ($this->model->save()) {

                    // delete all AgidOrganizationalOrganizationalUnitMm and create new
                    foreach ($this->model->agidOrganizationalOrganizationalUnitMm as $agid_organizational_unit_mm) {
                        $agid_organizational_unit_mm->delete();
                    }
    
                    // create AgidOrganizationaOrganizationalUnitMM
                    $agid_organizational_unit = Yii::$app->request->post();

                    // create new AgidOrganizationalOrganizationalUnitMm
                    foreach ($agid_organizational_unit['AgidOrganizationalUnit']['agid_organizational_organizational_unit_mm'] as $key => $value) {
                        
                        $agid_organizational_organizational_unit_mm = new AgidOrganizationalOrganizationalUnitMm;
                        $agid_organizational_organizational_unit_mm->agid_organizational_unit_id = $this->model->id;
                        $agid_organizational_organizational_unit_mm->agid_organizational_unit_father_id = $value;

                        if( !$agid_organizational_organizational_unit_mm->save() ){
                            throw new Exception("Errore! Non è stato possibile creare il Legame con le altre strutture.", 1);
                        }
                    }


                    // delete all AgidOrganizationalUnitDocumentiMm and create new
                    foreach ($this->model->agidOrganizationalUnitDocumentiMm as $agid_organizational_unit_documenti_mm) {
                        $agid_organizational_unit_documenti_mm->delete();
                    }

                    // create new AgidOrganizationalUnitDocumentiMm
                    foreach ($agid_organizational_unit['AgidOrganizationalUnit']['agid_organizational_unit_documenti_mm'] as $key => $value) {
                        
                        $agid_organizational_unit_documenti_mm = new AgidOrganizationalUnitDocumentiMm;
                        $agid_organizational_unit_documenti_mm->agid_organizational_unit_id = $this->model->id;
                        $agid_organizational_unit_documenti_mm->documenti_id = $value;

                        if( !$agid_organizational_unit_documenti_mm->save() ){
                            throw new Exception("Errore! Non è stato possibile creare il Legame con le altre strutture.", 1);
                        }
                    }

                    
                    // update all AgidOrganizationalUnitServiceMm
                    $this->model->updateAgidOrganizationalUnitServiceMm();


                    // commit transaction
                    $transaction->commit();

                }else{

                    throw new Exception("Errore! L'Unità Organizzativa non è stata aggiornata con successo.", 1);
                }

            } catch (\Throwable $th) {
                
                $transaction->rollBack();
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', $th->getMessage()));

                return $this->redirect(['update', 'id' => $this->model->id]);
            }

            // success message
            Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', "L'Unità Organizzativa è stata aggiornata con successo."));
            return $this->redirect(['update', 'id' => $this->model->id]);
        }


        return $this->render('update', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null,
        ]);
    }

	/**
	 * Deletes an existing AgidOrganizationalUnit model.
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
