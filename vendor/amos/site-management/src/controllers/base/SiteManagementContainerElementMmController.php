<?php

namespace amos\sitemanagement\controllers\base;

use amos\sitemanagement\models\search\SiteManagementContainerElementMmSearch;
use amos\sitemanagement\models\search\SiteManagementElementSearch;
use amos\sitemanagement\models\SiteManageContElemPubblication;
use amos\sitemanagement\models\SiteManageContElemPubblicationClass;
use amos\sitemanagement\models\SiteManageContElemPubblicationUserMm;
use amos\sitemanagement\models\SiteManagementContainer;
use amos\sitemanagement\models\SiteManagementContainerElementMm;
use amos\sitemanagement\Module;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use Yii;
use yii\helpers\Url;

/**
 * SiteManagementContainerElementMmController implements the CRUD actions for SiteManagementContainerElementMm model.
 */
class SiteManagementContainerElementMmController extends CrudController
{

    public function init()
    {
        $this->setModelObj(new SiteManagementContainerElementMm());
        $this->setModelSearch(new SiteManagementContainerElementMmSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}'.Html::tag('p', Yii::t('amoscore', 'Table')),
                    [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
    }

    /**
     * Lists all SiteManagementContainerElementMm models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single SiteManagementContainerElementMm model.
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
     * Creates a new SiteManagementContainerElementMm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idContainer, $created_element_id = null)
    {
        $this->setUpLayout("form");

        $model             = new SiteManagementContainerElementMm;
        $modelContainer    = SiteManagementContainer::findOne($idContainer);
        $elementSearch     = new SiteManagementElementSearch();
        $orderByLastInsert = false;
        $module            = \Yii::$app->getModule('sitemanagement');
        if ($module) {
            $orderByLastInsert     = $module->orderContentByLastInsert;
            $enableFixedContainers = $module->enableFixedContainers;
            if ($enableFixedContainers) {
                $elementSearch->template_id = $modelContainer->fixed_template_id;
            }
        }

        $lastOrder = 0;
        if ($orderByLastInsert == false) {
            $lastOrder = $modelContainer->getLastContainerOrder();
        } else {

        }

        $model->elem_order               = $lastOrder + 1;
        $model->container_id             = $idContainer;
        $modelPubblication               = new SiteManageContElemPubblication();
        $modelPubblication->container_id = $idContainer;

        if (!empty($created_element_id)) {
            $model->element_id = $created_element_id;
        }



        $dataProviderElements = $elementSearch->search(\Yii::$app->request->get());
        // if you create and assign an element on the fly
        if ($created_element_id) {
            $dataProviderElements->query->andWhere(['id' => $model->element_id]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $modelPubblication->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->shiftElement($idContainer, $model->elem_order, $model->id);
                $modelPubblication->container_elem_mm_id = $model->id;
                if ($modelPubblication->save()) {
                    $this->savePubblicationMms($modelPubblication);
                }
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['/sitemanagement/site-management-container/update', 'id' => $idContainer]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
                return $this->render('create',
                        [
                        'model' => $model,
                        'modelContainer' => $modelContainer,
                        'modelPubblication' => $modelPubblication,
                        'elementSearch' => $elementSearch,
                        'dataProviderElements' => $dataProviderElements
                ]);
            }
        } else {
            return $this->render('create',
                    [
                    'model' => $model,
                    'modelContainer' => $modelContainer,
                    'modelPubblication' => $modelPubblication,
                    'elementSearch' => $elementSearch,
                    'dataProviderElements' => $dataProviderElements
            ]);
        }
    }

    /**
     * 
     * @param integer $container
     * @param integer $order
     * @param integer $my_id
     * @param integer $value
     */
    protected function shiftElement($container, $order, $my_id = null, $value = 1)
    {
        $query = SiteManagementContainerElementMm::find()->andWhere(['container_id' => $container]);
        if ($my_id !== null) {
            $query->andWhere(['!=', 'id', $my_id]);
        }
        $allContents = $query->orderBy('elem_order')->all();
        foreach ($allContents as $v) {
            if ($v->elem_order >= $order) {
                $v->elem_order = $v->elem_order + $value;
                $v->save(false);
            }
        }
    }

    /**
     * Updates an existing SiteManagementContainerElementMm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout("form");
        /** @var  $model SiteManagementContainerElementMm */
        $model             = $this->findModel($id);
        $modelContainer    = $model->container;
        $modelPubblication = $model->siteManageContElemPubblication;
        $classes           = $modelPubblication->siteManageContElemPubblicationClasses;
        $users             = $modelPubblication->users;

        /** prepare data for the selects */
        foreach ($classes as $class) {
            $modelPubblication->pubblicationClasses [] = $class->class;
        }
        foreach ($users as $user) {
            $modelPubblication->pubblicationUsers [] = $user->id;
        }

        $elementSearch = new SiteManagementElementSearch();
        $module        = \Yii::$app->getModule('sitemanagement');
        if ($module) {
            $enableFixedContainers = $module->enableFixedContainers;
            if ($enableFixedContainers) {
                $elementSearch->template_id = $modelContainer->fixed_template_id;
            }
        }
        $dataProviderElements = $elementSearch->search(\Yii::$app->request->get());
        $dataProviderElements->query->andWhere(['id' => $model->element_id]);


        if ($model->load(Yii::$app->request->post()) && $model->validate() && $modelPubblication->load(Yii::$app->request->post())) {
//            pr($modelPubblication->attributes); die;
            if ($model->save()) {
                if ($modelPubblication->save()) {
                    $this->savePubblicationMms($modelPubblication);
                    Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                    return $this->redirect(['/sitemanagement/site-management-container/update', 'id' => $modelContainer->id]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
                return $this->render('update',
                        [
                        'model' => $model,
                        'modelContainer' => $modelContainer,
                        'modelPubblication' => $modelPubblication,
                        'elementSearch' => $elementSearch,
                        'dataProviderElements' => $dataProviderElements
                ]);
            }
        } else {
            return $this->render('update',
                    [
                    'model' => $model,
                    'modelContainer' => $modelContainer,
                    'modelPubblication' => $modelPubblication,
                    'elementSearch' => $elementSearch,
                    'dataProviderElements' => $dataProviderElements
            ]);
        }
    }

    /**
     * Deletes an existing SiteManagementContainerElementMm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /** @var  $model SiteManagementContainerElementMm */
        $model        = $this->findModel($id);
        $container_id = $model->container_id;
        if ($model) {
//si può sostituire il  delete() con forceDelete() in caso di SOFT DELETE attiva 
//In caso di soft delete attiva e usando la funzione delete() non sarà bloccata
//la cancellazione del record in presenza di foreign key quindi 
//il record sarà cancelleto comunque anche in presenza di tabelle collegate a questo record
//e non saranno cancellate le dipendenze e non avremo nemmeno evidenza della loro presenza
//In caso di soft delete attiva è consigliato modificare la funzione oppure utilizzare il forceDelete() che non andrà 
//mai a buon fine in caso di dipendenze presenti sul record da cancellare
            $model->deleteWithRelations();

            Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item deleted'));
        } else {
            Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not found'));
        }
        return $this->redirect(['/sitemanagement/site-management-container/update', 'id' => $container_id]);
    }

    /**
     * @param $modelPubblication SiteManageContElemPubblication
     */
    public function savePubblicationMms($modelPubblication)
    {
        SiteManageContElemPubblicationClass::deleteAll(['cont_elem_pubblication_id' => $modelPubblication->id]);
        if ($modelPubblication->pubblication_type_id == 3) {
            foreach ($modelPubblication->pubblicationClasses as $class) {
                $pubblicationClass                            = new SiteManageContElemPubblicationClass();
                $pubblicationClass->cont_elem_pubblication_id = $modelPubblication->id;
                $pubblicationClass->class                     = $class;
                $pubblicationClass->save();
            }
        }

        SiteManageContElemPubblicationUserMm::deleteAll(['cont_elem_pubblication_id' => $modelPubblication->id]);
        if ($modelPubblication->pubblication_type_id == 2) {
            foreach ($modelPubblication->pubblicationUsers as $user_id) {
                $pubblicationUser                            = new SiteManageContElemPubblicationUserMm();
                $pubblicationUser->cont_elem_pubblication_id = $modelPubblication->id;
                $pubblicationUser->user_id                   = $user_id;
                $pubblicationUser->save();
            }
        }
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('amossitemanagement', 'Create new')
        ];
    }
}