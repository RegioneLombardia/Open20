<?php
/**
 * Aria S.p.A.
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
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class HierarchicalDocumentsController
 *
 * @property \open20\amos\documenti\models\Documenti $model
 *
 * @package open20\amos\documenti\controllers
 */
class HierarchicalDocumentsController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @var AmosDocumenti $documentsModule
     */
    public $documentsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->documentsModule = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        $this->setModelObj($this->documentsModule->createModel('Documenti'));
        $this->setModelSearch($this->documentsModule->createModel('DocumentiSearch'));

        ModuleDocumentiAsset::register(Yii::$app->view);

        $this->setAvailableViews([
            'expl' => [
                'name' => 'expl',
                'label' => AmosIcons::show('grid').Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Icone')),
                'url' => '?currentView=expl'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid').Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Icon')),
                'url' => '?currentView=icon'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p',
                    AmosDocumenti::tHtml('amosdocumenti', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
        $this->layout = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'render-hierarchical-documents-widget',
                                'render-hierarchical-documents-widget-befe',
                                'render-hierarchical-documents-widget-befe-by-date',
                                'render-hierarchical-documents-widget-befe-community',
                            ],
                            'roles' => [
                                'LETTORE_DOCUMENTI',
                                'AMMINISTRATORE_DOCUMENTI',
                                'CREATORE_DOCUMENTI',
                                'FACILITATORE_DOCUMENTI',
                                'VALIDATORE_DOCUMENTI'
                            ]
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get'],
                    ]
                ]
        ]);
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionRenderHierarchicalDocumentsWidget()
    {
        Url::remember();
        $this->layout = false;
        return WidgetGraphicsHierarchicalDocuments::widget();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionRenderHierarchicalDocumentsWidgetBefe()
    {
        Url::remember();
        $this->layout = false;
        return \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefe::widget();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionRenderHierarchicalDocumentsWidgetBefeCommunity()
    {
        Url::remember();
        $this->layout = false;
        return \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity::widget();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionRenderHierarchicalDocumentsWidgetBefeByDate()
    {
        Url::remember();
        $this->layout = false;
        return \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeByDate::widget();
    }
}