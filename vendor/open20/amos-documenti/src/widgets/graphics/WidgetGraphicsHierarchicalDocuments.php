<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\documenti\widgets\graphics;

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\AmosModule;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class WidgetGraphicsHierarchicalDocuments
 * @package open20\amos\documenti\widgets\graphics
 */
class WidgetGraphicsHierarchicalDocuments extends WidgetGraphic
{
    /**
     * @var array $availableViews
     */
    public $availableViews;

    /**
     * @var int $parentId
     */
    public $parentId = null;

    /**
     * @var Documenti $parent
     */
    public $parent = null;

    /**
     * @var AmosDocumenti $documentsModule
     */
    protected $documentsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('HIERARCHICAL_DOCUMENTS');
        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Documents'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Hierarchical Documents'));
        $this->setClassFullSize('grid-item-fullsize');

        $this->documentsModule = AmosDocumenti::instance();

        if ((\Yii::$app->request->isAjax || \Yii::$app->request->isPjax) && \Yii::$app->request->get(self::paramName())) {
            $this->parentId = \Yii::$app->request->get(self::paramName());
        }

        if (!is_null($this->parentId) && is_numeric($this->parentId) && ($this->parentId > 0)) {
            /** @var Documenti $documentiModel */
            $documentiModel = $this->documentsModule->createModel('Documenti');
            $this->parent = $documentiModel::findOne($this->parentId);
        }

        $this->initCurrentView();
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        return $this->render('hierarchical-documents/hierarchical_documents',
            [
                'widget' => $this,
                'currentView' => $this->getCurrentView(),
                'toRefreshSectionId' => self::pjaxSectionId(),
                'dataProviderFolders' => $this->getDataProviderFolders(),
                'dataProviderDocuments' => $this->getDataProviderDocuments(),
                'availableViews' => $this->getAvailableViews(),
            ]);
    }

    /**
     * Id for the PJAX section.
     * @return string
     */
    public static function pjaxSectionId()
    {
        return 'widgetGraphicHierarchicalDocuments';
    }

    /**
     * Name of the param to add at the refresh widget url.
     * @return string
     */
    public static function paramName()
    {
        return 'parent_id';
    }

    /**
     * @return ActiveQuery
     */
    private function baseQuery()
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        /** @var ActiveQuery $query */
        $query = $documentiModel::find()->distinct();
        $query->andWhere(['parent_id' => $this->parentId]);
        $query = $this->addCwhQuery($query);
        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    private function addCwhQuery($query)
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;
        $classname = $this->documentsModule->model('Documenti');
        if (isset($moduleCwh)) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery($classname, ['queryBase' => $query]);
        }
        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
        if ($isSetCwh) {
            $query = $cwhActiveQuery->getQueryCwhAll();
        }
        return $query;
    }

    /**
     * @param AmosModule $moduleCwh
     * @param string $classname
     * @return bool
     */
    private function isSetCwh($moduleCwh, $classname)
    {
        if (isset($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProvider($isFolderField)
    {
        $query = $this->baseQuery();
        $query->andWhere(['is_folder' => $isFolderField]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'titolo' => SORT_ASC
                ]
            ]
        ]);
        return $dataProvider;
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProviderFolders()
    {
        return $this->getDataProvider(Documenti::IS_FOLDER);
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProviderDocuments()
    {
        return $this->getDataProvider(Documenti::IS_DOCUMENT);
    }

    /**
     * Init current view.
     */
    protected function initCurrentView()
    {
        $currentView = $this->getDefaultCurrentView();
        $this->setCurrentView($currentView);

        if ($currentViewName = \Yii::$app->request->getQueryParam('currentView')) {
            $this->setCurrentView($this->getAvailableView($currentViewName));
        }
    }

    /**
     * @return mixed
     */
    protected function getDefaultCurrentView()
    {
        $this->initAvailableViews();
        $views = array_keys($this->getAvailableViews());
        $defaultView = (in_array('icon', $views) ? 'icon' : $views[0]);
        return $this->getAvailableView($defaultView);
    }

    /**
     * Init available views.
     */
    protected function initAvailableViews()
    {
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Tabella')),
                'url' => '?currentView=grid'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Icone')),
                'url' => '?currentView=icon'
            ]
        ]);
    }

    /**
     * @return mixed
     */
    public function getAvailableViews()
    {
        return $this->availableViews;
    }

    /**
     * @param mixed $availableViews
     */
    public function setAvailableViews($availableViews)
    {
        $this->availableViews = $availableViews;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getAvailableView($name)
    {
        return $this->getAvailableViews()[$name];
    }

    /**
     * @return string
     */
    public function getCurrentView()
    {
        return $this->currentView;
    }

    /**
     * @param string $currentView
     */
    public function setCurrentView($currentView)
    {
        $this->currentView = $currentView;
    }

    /**
     * @return array
     */
    public function getGridViewColumns()
    {
        return [
            [
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Documenti $model */
                    $content = Html::beginTag('a', WidgetGraphicsHierarchicalDocuments::getLinkOptions($model));
                    $content .= DocumentsUtility::getDocumentIcon($model);
                    $content .= Html::endTag('a');
                    return $content;
                }
            ],
            [
                'attribute' => 'titolo',
                'headerOptions' => [
                    'id' => 'titolo'
                ],
                'contentOptions' => [
                    'headers' => 'titolo'
                ]
            ]
        ];
    }

    /**
     * This method render the nav bar for the widget.
     * @return string
     */
    public function getNavBar()
    {
        // Render
        $content = $this->navBarElement();
        if (!is_null($this->parent)) {
            $parents = $this->parent->allParents;
            foreach ($parents as $parent) {
                $content .= $this->navBarElement($parent, false);
            }
            $content .=  $this->navBarElement($this->parent, true);
        }
        return '<strong>'.AmosDocumenti::t('amosdocumenti', 'Sei in:') .'</strong> '. $content;
    }

    /**
     * @param Documenti $model
     * @return string
     */
    private function navBarElement($model = null, $last = false)
    {
        $url = [
            '/documenti/hierarchical-documents/render-hierarchical-documents-widget',
        ];
        if (\Yii::$app->request->get('currentView')) {
            $url['currentView'] = \Yii::$app->request->get('currentView');
        }
        $icon = AmosIcons::show('folder-open', [], 'dash');
        $content = $icon;
        $options = ['title' => AmosDocumenti::t('amosdocumenti', 'Root')];
        if($last) {
            $element = '';
            if (!is_null($model)) {
                $element = '<span class="m-l-5"> > ' . Html::tag('strong', $model->titolo, ['title' => $model->titolo]) .'</span>';
            }
        } else {
            if (!is_null($model)) {
                $content = Html::tag('span', $model->titolo);
                $url[self::paramName()] = $model->id;
                $options = ['title' => $model->titolo];
            }
            $element = '&nbsp;&nbsp;> '  . Html::a($content, $url, $options);
        }
        return $element;
    }

    /**
     * @param Documenti $model
     * @return array
     */
    public static function getLinkOptions($model)
    {
        $linkOptions = ['href' => '#', 'title' => $model->titolo, 'alt' => $model->titolo, 'class' => 'js-pjax'];
        if ($model->is_folder) {
            $href = [
                '/documenti/hierarchical-documents/render-hierarchical-documents-widget',
                self::paramName() => $model->id
            ];
            if (\Yii::$app->request->get('currentView')) {
                $href['currentView'] = \Yii::$app->request->get('currentView');
            }
            $linkOptions['href'] = \Yii::$app->urlManager->createUrl($href);
        } else {
            $linkOptions['data-pjax'] = '0';
            if (!is_null($model->getDocumentMainFile())) {
                $linkOptions['href'] = $model->getFullViewUrl();
            }
        }

        return $linkOptions;
    }

    /**
     * @param Documenti $model
     * @return string
     */
    public static function getIconDescription($model)
    {
        $moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        $showCountDocumentRecursive = $moduleDocumenti->showCountDocumentRecursive;
        $iconDescription = $model->titolo;
        if ($model->is_folder) {
            if($showCountDocumentRecursive) {
                $countChildren = count($model->getAllDocumentChildrens());
            }
            else {
                $countChildren = count($model->getDocumentChildrens());
            }
            $childrenDocumentsStr = '';
            if ($countChildren > 0) {
                $childrenDocumentsStr = ' (' . $countChildren . ' ';
                if ($countChildren == 1) {
                    $childrenDocumentsStr .= AmosDocumenti::t('amosdocumenti', '#document');
                } else {
                    $childrenDocumentsStr .= AmosDocumenti::t('amosdocumenti', '#documents');
                }
                $childrenDocumentsStr .= ')';
            }
            $iconDescription .= $childrenDocumentsStr;
        }
        return $iconDescription;
    }

    /**
     * @param Documenti $model
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDocumentDate($model)
    {
        $documentDate = '';
        if (!$model->is_folder) {
            $documentDate = ($model->publicatedFrom ? Html::beginTag('span') . \Yii::$app->formatter->asDate($model->publicatedFrom) . Html::endTag('span')
                : '-');
        }
        return $documentDate;
    }
}