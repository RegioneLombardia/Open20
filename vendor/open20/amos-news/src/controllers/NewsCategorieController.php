<?php

/**
 * Aria S.p.A.
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
use open20\amos\admin\AmosAdmin;
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


    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosNews::t('amosnews', 'Categorie news');
            $urlLinkAll   = '';

            $labelSigninOrSignup = AmosNews::t('amosnews', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosNews::t(
                'amosnews',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosNews::t('amosnews', '#beforeActionCtaLogin');
            $titleSignin = AmosNews::t(
                'amosnews',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );

            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                $labelLink,
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]
            );
            $subTitleSection  = Html::tag(
                'p',
                AmosNews::t(
                    'amosnews',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosNews::t('amosnews', 'Categorie news');
            $labelLinkAll = AmosNews::t('amosnews', 'Tutte le notizie');
            $urlLinkAll   = '/news/news/all-news';
            $titleLinkAll = AmosNews::t('amosnews', 'Visualizza la lista delle notizie');

            $subTitleSection = Html::tag('p', AmosNews::t('amosnews', ''));
        }

        $labelCreate = AmosNews::t('amosnews', 'Nuova');
        $titleCreate = AmosNews::t('amosnews', 'Crea una nuova notizia');
        $labelManage = AmosNews::t('amosnews', 'Gestisci');
        $titleManage = AmosNews::t('amosnews', 'Gestisci le notizie');
        $urlCreate   = '/news/news-categorie/create';
        $urlManage   = AmosNews::t('amosnews', '#');

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'news',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }


    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {
        $links[] = [
            'title' => AmosNews::t('amosnews', 'Visualizza le news create da me'),
            'label' => AmosNews::t('amosnews', 'Create da me'),
            'url' => '/news/news/own-news'
        ];

        if (\Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconNewsCategorie::class)) {
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Visualizza le categorie delle news'),
                'label' => AmosNews::t('amosnews', 'Categorie'),
                'url' => '/news/news-categorie/index',
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconNewsDaValidare::class)) {
            $links[] = [
            'title' => AmosNews::t('amosnews', 'Visualizza le news da validare'),
            'label' => AmosNews::t('amosnews', 'Da validare'),
            'url' => '/news/news/to-validate-news'
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconAdminAllNews::class)) {
            $links[] = [
            'title' => AmosNews::t('amosnews', 'Amministra tutte le news'),
            'label' => AmosNews::t('amosnews', 'Amministra'),
            'url' => '/news/news/admin-all-news'
            ];
        }
        return $links;
    }

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

        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Categorie news');
        }


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
                $this->model->color_text = $this->model->colorText();
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
                $this->model->color_text = $this->model->colorText();
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
