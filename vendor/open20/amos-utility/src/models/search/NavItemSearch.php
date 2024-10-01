<?php

namespace open20\amos\utility\models\search;

use luya\cms\models\NavItem;
use yii\data\ActiveDataProvider;

class NavItemSearch extends NavItem
{

    const IMPORT_TYPE_NEW_PAGE = 'new_page';
    const IMPORT_TYPE_NEW_VERSION = 'new_version';

    public $nav_container_id;

    public function rules()
    {
        return [
            [['title', 'alias','nav_id', 'id','nav_container_id'], 'safe']
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $query = NavItem::find()
            ->innerJoin('cms_nav', 'cms_nav.id = cms_nav_item.nav_id')
            ->andWhere(['is_deleted' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'timestamp_create',
                'title',
                'alias',
                'nav_id'
            ],
            'defaultOrder' => ['nav_id' => SORT_DESC, 'timestamp_create' => SORT_DESC]
        ]);

        if ($this->load($params)) {
            $query->andFilterWhere(['LIKE', 'title', $this->title]);
            $query->andFilterWhere(['LIKE', 'alias', $this->alias]);
            $query->andFilterWhere(['nav_id' => $this->nav_id]);
            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['cms_nav.nav_container_id' => $this->nav_container_id]);
        }
        return $dataProvider;
    }

    public function __toString(){
        return '';
    }

}