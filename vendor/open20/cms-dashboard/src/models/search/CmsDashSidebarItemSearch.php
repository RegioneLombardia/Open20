<?php

namespace open20\cms\dashboard\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\cms\dashboard\models\CmsDashSidebarItem;

/**
 * CmsDashSidebarItemSearch represents the model behind the search form about `open20\cms\dashboard\models\CmsDashSidebarItem`.
 */
class CmsDashSidebarItemSearch extends CmsDashSidebarItem
{

//private $container;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'isVisible', 'isTargetBlank', 'position', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['link', 'link_shortcut', 'label', 'description', 'shortcut_description', 'icon_name', 'id_container', 'class_container', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CmsDashSidebarItem::find();
        $query->orderBy(['position' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['cms_dash_sidebar_item.template' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['cms_dash_sidebar_item.vendorPath' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['cms_dash_sidebar_item.providerList' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['cms_dash_sidebar_item.actionButtonClass' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['cms_dash_sidebar_item.viewPath' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['cms_dash_sidebar_item.pathPrefix' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['cms_dash_sidebar_item.savedForm' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['cms_dash_sidebar_item.formLayout' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['cms_dash_sidebar_item.accessFilter' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['cms_dash_sidebar_item.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['cms_dash_sidebar_item.singularEntities' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['cms_dash_sidebar_item.modelMessageCategory' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['cms_dash_sidebar_item.controllerClass' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['cms_dash_sidebar_item.modelClass' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['cms_dash_sidebar_item.searchModelClass' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['cms_dash_sidebar_item.baseControllerClass' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['cms_dash_sidebar_item.indexWidgetType' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['cms_dash_sidebar_item.enableI18N' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['cms_dash_sidebar_item.enablePjax' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['cms_dash_sidebar_item.messageCategory' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['cms_dash_sidebar_item.formTabs' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['cms_dash_sidebar_item.tabsFieldList' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['cms_dash_sidebar_item.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['cms_dash_sidebar_item.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'isVisible' => $this->isVisible,
            'isTargetBlank' => $this->isTargetBlank,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'link_shortcut', $this->link_shortcut])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'description', $this->shortcut_description])
            ->andFilterWhere(['like', 'icon_name', $this->icon_name])
            ->andFilterWhere(['like', 'id_container', $this->id_container])
            ->andFilterWhere(['like', 'class_container', $this->class_container]);

        return $dataProvider;
    }
}
