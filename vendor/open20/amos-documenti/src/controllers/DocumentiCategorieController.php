<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\controllers
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiCategoryCommunityMm;
use open20\amos\documenti\models\DocumentiCategoryRolesMm;
use Yii;
use yii\helpers\Url;

/**
 * Class DocumentiCategorieController
 * DocumentiCategorieController implements the CRUD actions for DocumentiCategorie model.
 *
 * @property \open20\amos\documenti\models\DocumentiCategorie $model
 * @property \open20\amos\documenti\models\search\DocumentiCategorieSearch $modelSearch
 *
 * @package open20\amos\documenti\controllers
 */
class DocumentiCategorieController extends CrudController
{
    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @var AmosDocumenti $documentsModule
     */
    public $documentsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->documentsModule = Yii::$app->getModule(AmosDocumenti::getModuleName());

        $this->setModelObj($this->documentsModule->createModel('DocumentiCategorie'));
        $this->setModelSearch($this->documentsModule->createModel('DocumentiCategorieSearch'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Tabella')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        //Yii::$app->view->title = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }

    /**
     * Lists all DocumentiCategorie models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = NULL)
    {
        //cta:tutti i documenti
        $this->view->title =[
            //GESTIONE TITOLO
            'titleSection' => 'Categorie di documenti',
            //GESTIONE CTA
            'labelCta' => 'Tutti i documenti', //tutte o di mio interesse
            'titleCta' => 'Visualizza la lista dei documenti', //tutte o di mio interesse
            'linkCta' =>'/documenti/documenti/all-documents', //tutte o di mio interesse
            //GESTIONE +
            'labelCreate' => 'Nuovo',
            'titleCreate' => 'Crea un nuovo documento',
            'linkCreate' =>'/documenti/documenti/create',
            //GESTIONE MANAGE
            'labelManage' => 'Gestisci',
            'titleManage' =>'Gestisci i documenti',
            'linkManage' => '#',
        ];

        Url::remember();
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        if( isset(\Yii::$app->getModule('documenti')->params['containerFullWidth']) ){
            $this->view->params['containerFullWidth'] = \Yii::$app->getModule('documenti')->params['containerFullWidth'];
        }
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#page_title_documents_categories'));
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single DocumentiCategorie model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        return $this->render('view', ['model' => $this->model]);
    }

    /**
     * Creates a new DocumentiCategorie model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $this->model = $this->documentsModule->createModel('DocumentiCategorie');

        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                if ($this->model->save()) {
                    $this->model->saveDocumentiCategorieCommunityMm();
                    $this->model->saveDocumentiCategorieRolesMm();
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Categoria documenti salvata con successo.'));
                    return $this->redirect(['/documenti/documenti-categorie/update', 'id' => $this->model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing DocumentiCategorie model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);
        $this->model->loadDocumentiCategoryCommunities();
        $this->model->loadDocumentiCategoryRoles();

        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                if ($this->model->save()) {
                    $this->model->saveDocumentiCategorieCommunityMm();
                    $this->model->saveDocumentiCategorieRolesMm();
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Categoria documenti aggiornata con successo.'));
                    return $this->redirect(['/documenti/documenti-categorie/update', 'id' => $this->model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing DocumentiCategorie model.
     * If deletion is successful, the browser will be redirected to the previous list page.
     * @param int $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            if ($this->model->getDocumenti()->count() == 0) {
                /** @var DocumentiCategoryCommunityMm $documentiCategoryCommunityMmModel */
                $documentiCategoryCommunityMmModel = $this->documentsModule->createModel('DocumentiCategoryCommunityMm');
                $documentiCategoryCommunityMmModel::deleteAll(['documenti_categorie_id' => $this->id]);
                /** @var DocumentiCategoryRolesMm $documentiCategoryRolesMmModel */
                $documentiCategoryRolesMmModel = $this->documentsModule->createModel('DocumentiCategoryRolesMm');
                $documentiCategoryRolesMmModel::deleteAll(['documenti_categorie_id' => $this->id]);
                $this->model->delete();

                if (!$this->model->hasErrors()) {
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::t('amosdocumenti', 'Elemento cancellato correttamente.'));
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::t('amosdocumenti', 'Non sei autorizzato a cancellare questo elemento.'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::t('amosdocumenti', 'Non è possibile cancellare la categoria perché associata ad almeno un documento.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Elemento non trovato.'));
        }
        return $this->redirect(['/documenti/documenti-categorie/index']);
    }
}
