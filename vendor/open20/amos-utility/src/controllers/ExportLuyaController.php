<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 22/12/2021
 * Time: 16:01
 */

namespace open20\amos\utility\controllers;


use \luya\cms\models\NavItem;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\utility\models\ImportForm;
use open20\amos\utility\models\search\NavItemSearch;
use open20\amos\utility\Module;
use open20\amos\utility\utility\LuyaConfigsUtility;
use luya\cms\models\Nav;
use luya\cms\models\NavItemPage;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use \Yii;

class ExportLuyaController extends CrudController
{
    public function init()
    {
        $this->setModelObj(new NavItemSearch());
        $this->setModelSearch(new NavItemSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ]
        ]);

        parent::init();

    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['export-page', 'import-page', 'nav-items-ajax', 'index', 'page-versions', 'nav-item-from-container-ajax'],
                        'allow' => true,
                        'roles' => ['LUYACONFIG_ADMINISTRATOR', 'ADMIN'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }


    public function beforeAction($action)
    {

        $labelCreate = Yii::t('luyaconfigs', 'Importa');
        $titleCreate = Yii::t('luyaconfigs', 'Importa pagina');
        $labelManage = Yii::t('luyaconfigs', 'Gestisci');
        $titleManage = Yii::t('luyaconfigs', 'Gestisci');
        $urlCreate = '/utility/export-luya/import-page';
        $urlManage = null;

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
//            'modelLabel' => 'bando',
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
            'canCreate' => true
        ];
        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }


    /**
     * @param $layout
     * @return string|\yii\web\Response
     */
    public function actionIndex($layout = [])
    {
        return $this->redirect('export-page');
    }

    public function actionImportPage()
    {
//        $this->setUpLayout('list');

        $model = new ImportForm();
        if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
            if (\Yii::$app->request->post() && (isset($_FILES['import-file']['tmp_name']) && (!empty($_FILES['import-file']['tmp_name'])))) {
                $file = $_FILES['import-file']['tmp_name'];
                $nav_container_id = $model->nav_container_id;
                $luyaUtility = new LuyaConfigsUtility();
                $ok = $luyaUtility->import($file, $nav_container_id, $model->nav_item_id, $model->versionName, $model->nav_item_sub_container);
                if (!empty($luyaUtility->errorMessages)) {
                    foreach ($luyaUtility->errorMessages as $error) {
                        $text .= $error . '<br>';
                    }
                    \Yii::$app->session->addFlash('warning', $text);

                }
                if ($ok) {
                    \Yii::$app->session->addFlash('success', "Pagina importata correttamente");
                    return $this->redirect('import-page');
                } else if (!empty($luyaUtility->errorDangerMessages)) {
                    foreach ($luyaUtility->errorDangerMessages as $error) {
                        $textErr .= $error . '<br>';
                    }
                    \Yii::$app->session->addFlash('danger', $textErr);
                    return $this->redirect('import-page');
                }

            }
        }
        return $this->render('import', ['model' => $model]);
    }


    /**
     * @param $nav_id
     * @param $version
     * @return string|\yii\console\Response|\yii\web\Response|null
     */
    public function actionExportPage($nav_id = null, $nav_item_id = null, $version = null)
    {
        $this->setUpLayout('list');
        $utility = new LuyaConfigsUtility();
//        $utility->loginLuya(\Yii::$app->user->id);

        if (empty($nav_id)) {
            $modelSearch = new NavItemSearch();
            $dataProvider = $modelSearch->search(\Yii::$app->request->get());
            return $this->render('export', ['dataProvider' => $dataProvider, 'modelSearch' => $modelSearch]);
        } else {
            $luyaUtility = new LuyaConfigsUtility();
            return $luyaUtility->export($nav_id, $nav_item_id, $version);
        }

    }


    /**
     * @param $nav_id
     * @param bool $first
     * @return array|Nav|null|\yii\db\ActiveRecord[]
     */
    public function findChildren($nav_id, $first = false)
    {
        $navItems = Nav::find()->andWhere(['parent_nav_id' => $nav_id])->all();
        if (empty($navItems)) {
            if (!$first) {
                return Nav::findOne($nav_id);
            }
        }
        foreach ($navItems as $navItem) {
            $navItems[] = $this->findChildren($navItem->id);
        }
        return $navItems;
    }


    /**
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionNavItemsAjax($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
//            $query = new Query();
//            $query->select(new Expression("cms_nav_item.id as id, CONCAT(title, ' | ', alias) AS text"))
//                ->from('cms_nav_item')
//                ->leftJoin('cms_nav','cms_nav.id=cms_nav_item.nav_id')
////                ->leftJoin('cms_nav_container','cms_nav_container.id=cms_nav_item.nav_container_id')
//                ->andWhere(['or',
//                    ['like', 'title', $q],
//                    ['like', 'alias', $q],
//                ])
//                ->andWhere([ 'is_deleted' => 0])
//                ->limit(20);
//            $command = $query->createCommand();
//            $data = $command->queryAll();
//            $out['results'] = array_values($data);

            $query = new Query();
            $query->select(new Expression("cms_nav_item.id as id, CONCAT(admin_lang.short_code, ' - ', cms_nav_item.title, ' | ', cms_nav_item.alias) AS text, cms_nav_container.name as container"))
                ->from('cms_nav_item')
                ->leftJoin('cms_nav', 'cms_nav.id=cms_nav_item.nav_id')
                ->leftJoin('admin_lang', 'admin_lang.id=cms_nav_item.lang_id')
                ->leftJoin('cms_nav_container', 'cms_nav_container.id = cms_nav.nav_container_id')
                ->andWhere(['or',
                    ['like', 'cms_nav_item.title', $q],
                    ['like', 'cms_nav_item.alias', $q],
                ])
                ->andWhere(['cms_nav.is_deleted' => 0])
                ->orderBy('cms_nav.nav_container_id ASC, cms_nav_item.id ASC')
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();

            $groupedData = ArrayHelper::map($data, 'id', 'text', 'container');
            $results = [];
            foreach ($groupedData as $container => $docArray) {
                $pages = [];
                foreach ($docArray as $id => $title) {
                    $pages[] = ['id' => $id, 'text' => $title];
                }
                $results[] = ['text' => $container, 'children' => $pages];
            }

            $out['results'] = $results;
        }

        return $out;
    }

    /**
     * @return string
     */
    public function actionPageVersions()
    {
        $expandRowKey = \Yii::$app->request->post('expandRowKey');
        if ($expandRowKey && !empty($expandRowKey)) {
            $navItem = NavItem::findOne($expandRowKey);
            if ($navItem) {
                $dataProvider = new ActiveDataProvider([
                    'query' => NavItemPage::find()->andWhere(['nav_item_id' => $expandRowKey])
                ]);
            }
            return $this->renderAjax('_versions', ['dataProvider' => $dataProvider, 'navItem' => $navItem]);
        }
        return '';
    }

    public function actionNavItemFromContainerAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $container_id = $parents[0];
                $query = new Query();
                $navItems = $query->select(new Expression("cms_nav_item.id as id, CONCAT(admin_lang.short_code, ' - ', cms_nav_item.title, ' | ', cms_nav_item.alias) AS text"))
                    ->from('cms_nav_item')
                    ->leftJoin('cms_nav', 'cms_nav.id=cms_nav_item.nav_id')
                    ->leftJoin('admin_lang', 'admin_lang.id=cms_nav_item.lang_id')
                    ->andWhere(['nav_container_id' => $container_id])->all();

                foreach ($navItems as $navItem){
                    $out[] = ['id' => $navItem['id'], 'name' => $navItem['text']];
                }
//                $out = self::getSubCatList($cat_id);
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    /**
     * @return array
     */
    public static function getManageLinks()
    {
        return [];

//        $links[] = [
//            'title' => Module::t('amosnews', 'Esporta pagina'),
//            'label' => Module::t('amosnews', 'Esporta'),
//            'url' => '/luyaconfigs/export-luya/export-page'
//        ];
//        $links[] = [
//            'title' => Module::t('amosnews', 'Importa pagina'),
//            'label' => Module::t('amosnews', 'Importa'),
//            'url' => '/luyaconfigs/export-luya/import-page'
//        ];
//        return $links;
    }

}