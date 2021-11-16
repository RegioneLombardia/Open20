<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\news\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\news\AmosNews;
use open20\amos\news\models\NewsCategorie;
use open20\amos\news\models\NewsCategoryCommunityMm;
use open20\amos\news\models\NewsCategoryRolesMm;
use open20\amos\news\models\search\NewsCategorieSearch;
use Yii;
use yii\helpers\Url;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class NewsCategorieController
 * NewsCategorieController implements the CRUD actions for NewsCategorie model.
 *
 * @property NewsCategorie $model
 *
 * @package open20\amos\news\controllers
 */
class NewsCategorieController extends CrudController
{
    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new NewsCategorie());
        $this->setModelSearch(new NewsCategorieSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosNews::t('amosnews', '{iconaTabella}' . Html::tag('p', AmosNews::t('amosnews', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);

        parent::init();

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->view->pluginIcon = 'ic ic-news';
        }

        $this->setUpLayout();
    }

    /**
     * Lists all NewsCategorie models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();

        $this->layout = 'list';

        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();


        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($this->layout);
    }

    /**
     * Displays a single NewsCategorie model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $this->setUpLayout('main');

        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new NewsCategorie model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $this->setUpLayout('form');

        $this->model = new NewsCategorie;

        Yii::$app->view->params['textHelp']['filename'] = 'create_news_dashboard_description';
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                if ($this->model->save()) {
                    $this->model->saveNewsCategorieCommunityMm();
                    $this->model->saveNewsCategorieRolesMm();
                    Yii::$app->getSession()->addFlash('success', AmosNews::t('amosnews', 'Categoria News salvata con successo.'));
                    return $this->redirect(['/news/news-categorie/update', 'id' => $this->model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing NewsCategorie model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        Yii::$app->view->params['textHelp']['filename'] = 'create_news_dashboard_description';
        $this->model = $this->findModel($id);
        $this->model->loadNewsCategoryCommunities();
        $this->model->loadNewsCategoryRoles();

        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                if ($this->model->save()) {
                    $this->model->saveNewsCategorieCommunityMm();
                    $this->model->saveNewsCategorieRolesMm();
                    Yii::$app->getSession()->addFlash('success', AmosNews::t('amosnews', 'Categoria News aggiornata con successo.'));
                    return $this->redirect(['/news/news-categorie/update', 'id' => $this->model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing NewsCategorie model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            NewsCategoryCommunityMm::deleteAll(['news_category_id' => $this->model->id]);
            NewsCategoryRolesMm::deleteAll(['news_category_id' => $this->model->id]);
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', AmosNews::t('amosnews', 'News category successfully deleted'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'You are not authorized to delete this news category'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'News category not found'));
        }
        return $this->redirect(['index']);
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }
}
