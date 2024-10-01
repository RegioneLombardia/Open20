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
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\log\Logger;

/**
 * Class WidgetGraphicsHierarchicalDocuments
 * @package open20\amos\documenti\widgets\graphics
 */
class WidgetGraphicsHierarchicalDocumentsBefe extends WidgetGraphic
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
     * @var string $search
     */
    public $search = null;

    /**
     * @var Documenti $parent
     */
    public $parent = null;

    /**
     *
     * @var array $categories
     */
    public $categories;

    /**
     * @var AmosDocumenti $documentsModule
     */
    protected $documentsModule = null;

    /**
     *
     * @var \yii\db\ActiveQuery $query
     */
    public $query = null;

    /**
     * @var bool $enableSideBar
     */
    public $enableSideBar = true;

    /**
     * @var int|null $folder_id
     */
    public $folder_id = null;

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

        if (\Yii::$app->request->isAjax || \Yii::$app->request->isPjax) {
            $paramNameValue = \Yii::$app->request->get(self::paramName());
            if ($paramNameValue) {
                $this->parentId = $paramNameValue;
            }
            $categories = \Yii::$app->request->get('categories');
            if ($categories) {
                $this->categories = $categories;
            }
            $paramNameSearchValue = \Yii::$app->request->get(self::paramNameSearch());
            if ($paramNameSearchValue) {
                $this->search = $paramNameSearchValue;
            }
            $folderId = \Yii::$app->request->get('folder_id');
            if ($folderId) {
                $this->folder_id = $folderId;
            }
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
        return $this->render('hierarchical-befe/hierarchical_documents', [
            'widget' => $this,
            'currentView' => $this->getCurrentView(),
            'toRefreshSectionId' => self::pjaxSectionId(),
            'dataProviderFolders' => $this->getDataProviderFolders(),
            'dataProviderDocuments' => $this->getDataProviderDocuments(),
            'availableViews' => $this->getAvailableViews(),
            'filter' => new \open20\amos\documenti\models\search\DocumentiSearch(),
        ]);
    }

    /**
     * Id for the PJAX section.
     * @return string
     */
    public static function pjaxSectionId()
    {
        return 'widgetGraphicHierarchicalDocumentsBefe';
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
     * Name of the param to search.
     * @return string
     */
    public static function paramNameSearch()
    {
        return 'search';
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

        if (empty($this->search)) {
            $paramNameValue = \Yii::$app->request->get(self::paramName());
            $parentId = (empty($paramNameValue) ? $this->folder_id : $paramNameValue);
            $query->andWhere(['documenti.parent_id' => $parentId]);
        }

        $query->joinWith('documentiCategorie');

        $query = $this->addCwhQuery($query);

        return $query;
    }

    private function baseFolderQuery($parent_id = null)
    {
        if (!empty($this->query)) {
            $query = $this->query;
        } else {
            /** @var Documenti $documentiModel */
            $documentiModel = $this->documentsModule->createModel('Documenti');
            /** @var ActiveQuery $query */
            $query = $documentiModel::find()->distinct();
        }

        $query->andWhere(['documenti.parent_id' => $parent_id]);

        $query->andWhere(['documenti.is_folder' => 1]);

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
        try {
            if (!empty($this->query)) {
                $query = $this->query;
            } else {
                $query = $this->baseQuery();
            }

            if (!empty($this->search)) {
                if (!empty($this->folder_id)) {
                    $parentIds = $this->getRecursiveFolderIds($this->folder_id);
                \Yii::debug($parentIds, 'aria');
                    $query->andWhere(['or', ['documenti.parent_id' => $parentIds]]);
                    //\Yii::debug($this->folder_id, 'aria');
                }
                $query->andWhere(['or',
                    ['like', 'documenti.titolo', $this->search],
                    ['like', 'documenti.sottotitolo', $this->search],
                    ['like', 'documenti.descrizione_breve', $this->search],
                    ['like', 'documenti.descrizione', $this->search],
                    ['like', 'documenti_categorie.titolo', $this->search],
                    ['like', 'documenti_categorie.sottotitolo', $this->search],
                    ['like', 'documenti_categorie.descrizione', $this->search],
                    ['like', 'documenti_categorie.descrizione_breve', $this->search],
                ]);
            }

            if (!empty($this->categories)) {
                $query->andWhere(['or',
                    ['documenti.documenti_categorie_id' => $this->categories],
                    ['documenti.is_folder' => 1],
                ]);
            }

            $query->andWhere(['is_folder' => $isFolderField]);

            if ($isFolderField) {
                $query->orderBy('documenti.titolo ASC');
            } else {
                $query->orderBy('documenti.created_at DESC');
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'titolo' => SORT_ASC
                    ]
                ]
            ]);
            $dataProvider->setPagination(false);
            return $dataProvider;
        } catch (\Exception $ex) {
            \Yii::getLogger()->log('getDataProvider di WidgetGraphicsHierarchicalDocumentsBefe', Logger::LEVEL_ERROR);
            return null;
        }
    }

    /**
     * @return Array
     */
    private function getRecursiveFolderIds($folder) {
        $folders = [$folder];

        $documentiModel = $this->documentsModule->createModel('Documenti');
        $children = $documentiModel::find()->andWhere(['parent_id' => $folder]);
        foreach($children as $child) {
            if ($child->is_folder)
                $folders = \yii\helpers\ArrayHelper::merge($folders, $this->getRecursiveFolderIds($child->id));
        }
        return $folders;
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
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p',
                        AmosDocumenti::tHtml('amosdocumenti', 'Tabella')),
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
                'attribute' => 'titolo',
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Documenti $model */
                    $content = Html::beginTag('a', WidgetGraphicsHierarchicalDocumentsBefe::getLinkOptions($model));
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
            ],
        ];
    }

    public function getChildrenContent($children, $lvl)
    {
        $content = '';
        foreach ($children as $fold) {
            $children2 = $fold->childrenFolder;
            if (empty($children2)) {
                $content .= $this->navSideBarElement($fold, ($lvl + 1));
            } else {
                $content .= $this->navSideBarElement($fold, ($lvl + 1));
                $content .= $this->getChildrenContent($children2, ($lvl + 1));
            }
        }
        return $content;
    }

    /**
     * This method render the nav bar for the widget.
     * @return string
     */
    public function getNavBar()
    {
        if ($this->enableSideBar) {
            $lvl = 1;
            // Render
            $content = $this->navSideBarElement();

            $allFolders = $this->baseFolderQuery($this->folder_id)->orderBy('parent_id, titolo')->all();
            foreach ($allFolders as $folder) {
                $children = $folder->childrenFolder;

                if (empty($children)) {
                    $content .= $this->navSideBarElement($folder, ($lvl + 1));
                } else {
                    $content .= $this->navSideBarElement($folder, ($lvl + 1));
                    $content .= $this->getChildrenContent($children, ($lvl + 1));
                }
            }
            return $content;
        } else {
            // Render
            $content = $this->navBarElement();
            if (!is_null($this->parent)) {
                $parents = $this->parent->allParents;
                foreach ($parents as $parent) {
                    $content .= $this->navBarElement($parent, false);
                }
                $content .= $this->navBarElement($this->parent, true);
            }
            return '<strong>' . AmosDocumenti::t('amosdocumenti', 'Sei in:') . '</strong> ' . $content;
        }
    }

    private function navSideBarElement($model = null, $lvl = 0)
    {
        $url = [
            '/documenti/hierarchical-documents/render-hierarchical-documents-widget-befe',
        ];
        if (\Yii::$app->request->get('currentView')) {
            $url['currentView'] = \Yii::$app->request->get('currentView');
        }
        if (!empty($this->categories)) {
            $url['categories'] = $this->categories;
        }
        if (!empty($this->folder_id)) {
            $url['folder_id'] = $this->folder_id;
        }
        $active = '';
        if (!empty($model)) {
            if ($this->parentId == $model->id) {
                $active = 'active';
            }
        }
        $icon = '<span class="icon icon-folder icon-sm mdi mdi-folder mr-1"></span>';
        $arrow = '<span class="icon icon-arrow icon-sm mdi mdi-chevron-right ml-1"></span>';

        if (!is_null($model)) {
            $content = $icon . Html::tag('span', $model->titolo) . $arrow;
            $url[self::paramName()] = $model->id;
            $options = ['data-url' => Url::to($url), 'title' => $model->titolo, 'class' => 'hierarchical-link text-black text-decoration-none font-weight-normal'];
        } else {
            if (!empty($this->folder_id)) {
                /** @var Documenti $documentModel */
                $documentModel = $this->documentsModule->createModel('Documenti');
                $folder = $documentModel::findOne($this->folder_id);
                if (!is_null($folder)) {
                    $mainFolderName = $folder->getTitle();
                } else {
                    throw new InvalidConfigException('The provided folder_id is not valid');
                }
            } else {
                $mainFolderName = AmosDocumenti::t('amosdocumenti', 'Cartella principale');
            }
            $content = $icon . $mainFolderName . $arrow;
            $options = ['data-url' => Url::to($url), 'title' => AmosDocumenti::t('amosdocumenti', 'Root'), 'class' => 'hierarchical-link text-black text-decoration-none font-weight-normal'];
        }

        if ($lvl > 0) {
            $element = '<div class="hierarchical-element ml-' . $lvl . ' px-2 py-2 bg-white mb-3 font-weight-normal ' . $active . '">' . Html::a($content, null, $options) . '</div>';
        } else {
            if ($this->parentId == null) {
                $active = 'active';
            }
            $element = '<div class="hierarchical-element px-2 py-2 bg-white mb-3 ' . $active . '">' . Html::a($content, null, $options) . '</div>';
        }

        return $element;
    }

    /**
     * @param Documenti $model
     * @return string
     */
    private function navBarElement($model = null, $last = false)
    {
        $url = [
            '/documenti/hierarchical-documents/render-hierarchical-documents-widget-befe',
        ];
        if (\Yii::$app->request->get('currentView')) {
            $url['currentView'] = \Yii::$app->request->get('currentView');
        }
        $icon = '<span class="icon icon-folder icon-sm mdi mdi-folder mr-1"></span>';
        if ($last) {
            $element = '';
            if (!is_null($model)) {
                $element = '<span class="m-l-5"> > ' . Html::tag('strong', $model->titolo, ['title' => $model->titolo]) . '</span>';
            }
        } else {
            if (!is_null($model)) {
                $content = Html::tag('span', $model->titolo);
                $url[self::paramName()] = $model->id;
                $options = ['title' => $model->titolo, 'class' => 'hierarchical-link text-black text-decoration-none font-weight-bold'];
            } else {
                if (!empty($this->folder_id)) {
                    /** @var Documenti $documentModel */
                    $documentModel = $this->documentsModule->createModel('Documenti');
                    $folder = $documentModel::findOne($this->folder_id);
                    if (!is_null($folder)) {
                        $mainFolderName = $folder->getTitle();
                    } else {
                        throw new InvalidConfigException('The provided folder_id is not valid');
                    }
                } else {
                    $mainFolderName = AmosDocumenti::t('amosdocumenti', 'Cartella principale');
                }
                $content = $icon . $mainFolderName;
                $options = ['title' => AmosDocumenti::t('amosdocumenti', 'Root'), 'class' => 'hierarchical-link text-black text-decoration-none font-weight-bold'];
            }
            $element = '&nbsp;&nbsp;> ' . Html::a($content, $url, $options);
        }
        return $element;
    }

    /**
     * @param Documenti $model
     * @param int|array|null $categories
     * @param int|null $folderId
     * @return array
     */
    public static function getLinkOptions($model, $categories = null, $folderId = null)
    {
        $linkOptions = ['href' => '#', 'title' => $model->titolo, 'alt' => $model->titolo, 'class' => 'js-pjax'];
        if ($model->is_folder) {
            $href = [
                '/documenti/hierarchical-documents/render-hierarchical-documents-widget-befe',
                self::paramName() => $model->id
            ];
            if (\Yii::$app->request->get('currentView')) {
                $href['currentView'] = \Yii::$app->request->get('currentView');
            }
            if (!empty($categories)) {
                $href['categories'] = $categories;
            }
            if (!empty($folderId)) {
                $href['folder_id'] = $folderId;
            }
            $linkOptions['href'] = \Yii::$app->urlManager->createUrl($href);
        } else {
            $linkOptions['data-pjax'] = '0';
            if (!is_null($model->getDocumentMainFile())) {

                $linkOptions['href'] = $model->getDocumentMainFile()->getUrl('original',true);
            }
            if (!empty($model->link_document)) {
                $linkOptions['href'] = $model->link_document;
                $linkOptions['target'] = '_blank';
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
            if ($showCountDocumentRecursive) {
                $countChildren = count($model->getAllDocumentChildrens());
            } else {
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

    /**
     * @return bool
     */
    public function isVisible()
    {
        return (!\Yii::$app->user->isGuest);
    }
}
