<?php

namespace open20\amos\utility\controllers;

use open20\amos\utility\helper\StructureTable;
use open20\amos\utility\models\AuthItemsTreeViewTemp;
//use open20\amos\utility\models\AuthItemChild;
//
//use open20\amos\utility\models\AuthItemTreeView;
//use open20\amos\utility\models\AuthItemViewer;
//use open20\amos\utility\models\ZTest;
use open20\amos\utility\models\search\AuthItemSearch;
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
 * @package open20\amos\utility\controllers
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
                            'detail'
                        ],
                        'roles' => ['ADMIN']
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


       $this->setUpLayout();

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
     * @param null $layout
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex($layout = null)
    {

        $modelSearch = new AuthItemSearch();
        $dataProviderRoles = $modelSearch->searchRoles(\Yii::$app->request->get());

        return $this->render('index', [
            'modelSearch' => $modelSearch,
            'dataProviderRoles' => $dataProviderRoles
        ]);
    }

    /**
     * @param $item
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDetail($item){
        $itemChooseStr = $item;
        if (Yii::$app->authManager->getRole($itemChooseStr)) {
            $itemChoose = Yii::$app->authManager->getRole($itemChooseStr);
        } elseif (Yii::$app->authManager->getPermission($itemChooseStr)) {
            $itemChoose = Yii::$app->authManager->getPermission($itemChooseStr);
        } elseif (Yii::$app->authManager->getRule($itemChooseStr)){
            $itemChoose = Yii::$app->authManager->getRule($itemChooseStr);
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
        $query = \open20\amos\utility\models\AuthItem::find()->andWhere(['type' => 1]);
        $dataProviderRoles = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

//        pr(count($dataProviderRoles->models));
//        die();

        return $this->render('detail', [
            'dataProvider' => $dataProvider,
            'itemChoose' => $itemChoose,
            'dataProviderRoles' => $dataProviderRoles
        ]);
    }


    /**
     * @param $item
     */
    public function actionModal($item){
        $this->render('modal',[
            'item' => $item,
        ]);
    }
}
