<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\controllers\base
 * @category   CategoryName
 */

namespace amos\sitemanagement\controllers\base;

use amos\sitemanagement\models\search\SiteManagementElementSearch;
use amos\sitemanagement\models\SiteManageContElemPubblication;
use amos\sitemanagement\models\SiteManagementContainerElem;
use amos\sitemanagement\models\SiteManagementContainerElementMm;
use amos\sitemanagement\models\SiteManagementContainerElemFieldsVal;
use amos\sitemanagement\models\SiteManagementElement;
use amos\sitemanagement\models\SiteManagementFieldsType;
use amos\sitemanagement\models\SiteManagementTemplateFields;
use amos\sitemanagement\Module;
use amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainerFather;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\Url;

/**
 * Class SiteManagementElementController
 * SiteManagementElementController implements the CRUD actions for SiteManagementElement model.
 *
 * @property \amos\sitemanagement\models\SiteManagementElement $model
 * @property \amos\sitemanagement\models\search\SiteManagementElementSearch $modelSearch
 *
 * @package amos\sitemanagement\controllers\base
 */
class SiteManagementElementController extends CrudController
{
    /**
     * Trait used for initialize the tab dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new SiteManagementElement());
        $this->setModelSearch(new SiteManagementElementSearch());

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::tHtml('amoscore', 'Table')),
            'url' => '?currentView=grid'
        ];

        $this->setAvailableViews([
            'grid' => $this->viewGrid
        ]);

        parent::init();

        $this->layout = 'main';
        $this->setUpLayout();
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->view->title = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('amossitemanagement', 'Add element')
        ];
    }

    /**
     * This method is useful to set all idea params for all list views.
     */
    protected function setListViewsParams($setCurrentDashboard = true)
    {
        $this->setCreateNewBtnLabel();
        $this->setUpLayout('list');
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
            $this->child_of = WidgetIconSiteManagementContainerFather::className();
        }
        Yii::$app->session->set(Module::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(Module::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * This method returns the close url for close button in action view.
     * @return string
     */
    public function getViewCloseUrl()
    {
        return Yii::$app->session->get(Module::beginCreateNewSessionKey());
    }

    /**
     * Lists all models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(Module::t('amossitemanagement', 'Elements'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }
        return parent::actionIndex();
    }

    /**
     * Displays a single model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($idContainer = null)
    {
        $this->setUpLayout('form');

        $this->model = new SiteManagementElement();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully created.'));
                return $this->redirect(['update', 'id' => $this->model->id, 'idContainer' =>$idContainer]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'Element not created, check the data entered.'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing SiteManagementElement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $idContainer = null)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);
        $template = $this->model->siteManagementTemplate;
        $attributes = [];
        $attributesTypes = [];
        $modelFieldImages = null;
        if ($template) {
            $fields = $template->siteManagementFieldsTypes;
            foreach ($fields as $field) {
                $attributes [] = $field->name;
                $attributesTypes [$field->name] = $field->type;
                if($field->type == 'file'){
                    $templateFieldImage = SiteManagementContainerElemFieldsVal::find()->andWhere([ 'container_elem_id' => $this->model->id, 'field_id' => $field->id])->one();
                    if(empty($templateFieldImage)){
                        $templateFieldImage = new SiteManagementContainerElemFieldsVal();
                        $templateFieldImage->field_id = $field->id;
                        $templateFieldImage->container_elem_id = $this->model->id;
                    }
                    $modelFieldImages = $templateFieldImage;

                }
            }
        }

        $dynamicModel = new DynamicModel($attributes);
        $dynamicModel->addRule($attributes, 'safe');
        $dynamicModel = $this->model->loadFieldsToDynamicModel($dynamicModel);
        if ($this->model->load(Yii::$app->request->post()) && $dynamicModel->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                //you need to save the file before the saveFieldDynamicForm
                if(!empty($modelFieldImages)) {
                    $modelFieldImages->value = 'image';
                    $modelFieldImages->save();
                }
                $this->model->saveFieldsFormDynamicModel($dynamicModel, $attributesTypes);
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully updated.'));
                if(!empty($idContainer)){
                    return $this->redirect(['/sitemanagement/site-management-container-element-mm/create', 'idContainer' => $idContainer, 'created_element_id' => $this->model->id]);
                }
                return $this->redirect(\Yii::$app->request->referrer);
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'Element not updated, check the data entered.'));
            }
            //save element with data from a content
        }else if($this->model->load(Yii::$app->request->post()) && $this->model->validate()){
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully updated.'));
                if(!empty($idContainer)){
                    return $this->redirect(['/sitemanagement/site-management-container-element-mm/create', 'idContainer' => $idContainer, 'created_element_id' => $this->model->id]);
                }
                return $this->redirect(\Yii::$app->request->referrer);
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'Element not updated, check the data entered.'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'dynamicModel' => $dynamicModel,
            'attributesTypes' => $attributesTypes,
            'modelFieldImages' => $modelFieldImages
        ]);
    }

    /**
     * Deletes an existing model.
     * If deletion is successful, the browser will be redirected to the previous list page.
     * @param int $id
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $elementsMm = SiteManagementContainerElementMm::find()->andWhere(['element_id' => $id])->all();
            /** @var  $element */
            foreach ($elementsMm as $elementMm){
                $elementMm->deleteWithRelations();
            }
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(Yii::$app->session->get(Module::beginCreateNewSessionKey()));
    }
}
