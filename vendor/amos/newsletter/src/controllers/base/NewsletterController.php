<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\newsletter\controllers\base
 */

namespace amos\newsletter\controllers\base;

use amos\newsletter\models\NewsletterServiceMailFieldsMm;
use amos\newsletter\utility\NewsletterUtility;
use Yii;
use amos\newsletter\models\Newsletter;
use amos\newsletter\models\search\NewsletterSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;
use yii\web\Response;


/**
 * Class NewsletterController
 * NewsletterController implements the CRUD actions for Newsletter model.
 *
 * @property \amos\newsletter\models\Newsletter $model
 * @property \amos\newsletter\models\search\NewsletterSearch $modelSearch
 *
 * @package amos\newsletter\controllers\base
 */
class NewsletterController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new Newsletter());
        $this->setModelSearch(new NewsletterSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
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
    }

    /**
     * Lists all Newsletter models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single Newsletter model.
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
     * Creates a new Newsletter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new Newsletter();
        $mailService = NewsletterUtility::getCurrentMailService();
        $decoded = $mailService->getLists();

        $lists = [];
        if(!empty($decoded->Items)){
            $lists = $decoded->Items;
        }
        $decodedFields = $mailService->getDynamicFields();
        $fields = [];
        if(!empty($decodedFields->Items)){
            $fields = $decodedFields->Items;
        }



        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                if(!empty(\Yii::$app->request->post('serviceMailFields'))){
                    $this->model->serviceMailFields = \Yii::$app->request->post('serviceMailFields');
                }
                NewsletterServiceMailFieldsMm::deleteAll(['newsletter_id' => $this->model->id]);
                foreach ($this->model->serviceMailFields as $field){
                    $fieldsmm = new NewsletterServiceMailFieldsMm();
                    $fieldsmm->newsletter_id = $this->model->id;
                    $fieldsmm->service_mail_field_id =  $field;
                    $fieldsmm->save();
                }
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'fields' => $fields,
            'lists' => $lists,
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new Newsletter model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new Newsletter();

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
     * Updates an existing Newsletter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        /** @var  $model Newsletter*/
        $this->model = $this->findModel($id);
        if(\Yii::$app->request->get('idList')){
            $this->model->service_email_list_id = \Yii::$app->request->get('idList');
        }

        $mailService = NewsletterUtility::getCurrentMailService();
        $decoded = $mailService->getLists();
        $lists = [];
        if(!empty($decoded->Items)){
            $lists = $decoded->Items;
        }

        $decodedFields = $mailService->getDynamicFields();
        $fields = [];
        if(!empty($decodedFields->Items)){
            $fields = $decodedFields->Items;
        }

        $this->model->loadDynamicFields();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                if(!empty(\Yii::$app->request->post('serviceMailFields'))){
                    $this->model->serviceMailFields = \Yii::$app->request->post('serviceMailFields');
                }
                NewsletterServiceMailFieldsMm::deleteAll(['newsletter_id' => $this->model->id]);
                foreach ($this->model->serviceMailFields as $field){
                    $fieldsmm = new NewsletterServiceMailFieldsMm();
                    $fieldsmm->newsletter_id = $this->model->id;
                    $fieldsmm->service_mail_field_id =  $field;
                    $fieldsmm->save();
                }
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'fields' => $fields,
            'lists' => $lists,
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing Newsletter model.
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


    /**
     * @param $idList
     * @return array
     */
    public function actionGetGroupsByListAjax()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $mailService = NewsletterUtility::getCurrentMailService();
        $result = [];
        if ($mailService) {
            if (isset($_POST['depdrop_parents'])) {
                $parents = $_POST['depdrop_parents'];
                if ($parents != null) {
                    $idList = $parents[0];
                    $decoded = $mailService->getGroupsByList($idList);
                    if (!empty($decoded->Items)) {
                        $groups = $decoded->Items;
                        foreach ($groups as $group) {
                            $result[] = ['id' => $group->idGroup, 'name' => $group->Name];
                        }
                    }
                }

            }
        }
        return ['output' => $result, 'selected'=> ''];
    }



}
