<?php

namespace backend\modules\rbacviewer\controllers;

use backend\modules\rbacviewer\helper\StructureTable;
use backend\modules\rbacviewer\models\AuthItemChild;
use backend\modules\rbacviewer\models\AuthItemsTreeViewTemp;
use backend\modules\rbacviewer\models\AuthItemTreeView;
use backend\modules\rbacviewer\models\AuthItemViewer;
use backend\modules\rbacviewer\models\ZTest;
use mdm\admin\models\searchs\AuthItem;
use Yii;
use yii\base\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\db\IntegrityException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Role;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\layout\Module;
use yii\base\Model;

/**
 * Class RbacViewerController
 *
 *
 *
 * @package backend\modules\rbacviewer\controllers
 */
class RbacViewerController extends CrudController
{

    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                        ],
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    /**
     * used to assign an progressive ID to the structure of the tree table (AuthItemsTreeViewTemp)
     * @var int
     */
    public $myid = 0;

    public function init()
    {
        $this->myid = 0;
        $this->setModelObj(new AuthItemsTreeViewTemp());
        $this->setModelSearch(new AuthItemsTreeViewTemp());

        $this->setAvailableViews([

                'list' => [
                    'name' => 'list',
                    'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                    'url' => '?currentView=list'
                    ],
            
            /*
            'list' => [
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
                ],
            */
        ]);

        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
       // $this->setUpLayout('bootstrap-italia-layout-no-sidebar');   
       // $this->layout = '@vendor/open20/amos-core/views/layouts/bootstrap-italia-layout-no-sidebar';  
       $this->setUpLayout('@vendor/open20/amos-layout/src/views/layouts/bootstrap-italia-layout-no-sidebar');

        parent::init();
    }

    // public function setUpLayout($layout = null)
    // {

    //     if ($layout === false) {
    //         $this->layout = false;
    //         return true;
    //     }
    //     $this->layout = (!empty($layout)) ? $layout : $this->layout;
    //     $module = \Yii::$app->getModule('layout');

    //     if (empty($module)) {
    //         // if (strpos($this->layout, '@') === false) {
    //             $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
    //         // }
    //         return true;
    //     }

    //     return true;
    // }

    /**
     *
     * @return mixed
     */
    public function actionIndex()
    {
       

        $itemChoose = null;
        if (isset(Yii::$app->request->getQueryParams()['item'])) {
            $itemChooseStr = Yii::$app->request->getQueryParams()['item'];
            if (Yii::$app->authManager->getRole($itemChooseStr)) {
                $itemChoose = Yii::$app->authManager->getRole($itemChooseStr);
            } elseif (Yii::$app->authManager->getPermission($itemChooseStr)) {
                $itemChoose = Yii::$app->authManager->getPermission($itemChooseStr);
            } elseif (Yii::$app->authManager->getRule($itemChooseStr)){
                $itemChoose = Yii::$app->authManager->getRule($itemChooseStr);
            }
        }
        $childrens =[];
        if ($itemChoose) {
            $childrens = Yii::$app->authManager->getChildren($itemChoose->name);
        }

        $helpStructureTable = new StructureTable;
        $helpStructureTable->initializeStructure($childrens);

        $query = AuthItemsTreeViewTemp::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);


//        $query = Auth
        $query = \mdm\admin\models\AuthItem::find(['type' => 1]);
        $dataProviderRoles = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

//        pr(count($dataProviderRoles->models));
//        die();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'itemChoose' => $itemChoose,
            'dataProviderRoles' => $dataProviderRoles
        ]);
    }

    public function actionModal($item){


        $this->render('modal',[
            'item' => $item,
        ]);
    }
}
