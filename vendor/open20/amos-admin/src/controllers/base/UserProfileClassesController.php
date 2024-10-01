<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers\base 
 */

namespace open20\amos\admin\controllers\base;

use Yii;
use open20\amos\admin\models\UserProfileClasses;
use open20\amos\admin\models\search\UserProfileClassesSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;
use open20\amos\admin\models\UserProfileClassesAuthMm;
use yii\helpers\ArrayHelper;

/**
 * Class UserProfileClassesController 
 * UserProfileClassesController implements the CRUD actions for UserProfileClasses model.
 *
 * @property \open20\amos\admin\models\UserProfileClasses $model
 * @property \open20\amos\admin\models\search\UserProfileClassesSearch $modelSearch 
 *
 * @package open20\amos\admin\controllers\base 
 */
class UserProfileClassesController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new UserProfileClasses());
        $this->setModelSearch(new UserProfileClassesSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /* 'list' => [
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
              ], */
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all UserProfileClasses models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single UserProfileClasses model.
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
     * Creates a new UserProfileClasses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new UserProfileClasses();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                $this->model->assignPermissions(\Yii::$app->request->post());

                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create',
                [
                'model' => $this->model,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new UserProfileClasses model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new UserProfileClasses();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax',
                [
                'model' => $this->model,
                'fid' => $fid,
                'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing UserProfileClasses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model                = $this->findModel($id);
        $oldAuth = ArrayHelper::map(UserProfileClassesAuthMm::findAll(['user_profile_classes_id' => $id]),
                'item_id', 'item_id');
        $this->model->configuration = $oldAuth;
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            if ($this->model->save()) {
                $this->model->assignPermissions(\Yii::$app->request->post());
                $this->model->updatePermissionToUsers();

                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update',
                [
                'model' => $this->model,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing UserProfileClasses model.
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
                Yii::$app->getSession()->addFlash('success',
                    BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }
	
	public function actionClone($id){
        $this->model = $this->findModel($id);
        
        $allPermissions = ArrayHelper::map(UserProfileClassesAuthMm::findAll(['user_profile_classes_id' => $id]),
                'item_id', 'item_id');
		
         if ($this->model) {
             $newModel = new UserProfileClasses();
			 $newModel->name =  $this->model->name."_copy".($this->model->id+1);
			 $newModel->description =  $this->model->description;
			 $newModel->code =  $this->model->code;
			 // $newModel->type =  $this->model->type;
			 $newModel->enabled =  $this->model->enabled;
			 $newModel->configuration = $this->model->configuration;
			 if($newModel->save()){
				  foreach ($allPermissions as $v) {
					$newAuth                          = new UserProfileClassesAuthMm();
					$newAuth->user_profile_classes_id = $newModel->id;
					$newAuth->item_id                 = $v;
					$newAuth->save(false);
				}
				Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
			 }else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore', 'Item not copied, check data'));
            }
         } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }
}