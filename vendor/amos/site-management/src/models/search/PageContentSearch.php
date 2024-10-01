<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\models\search
 * @category   CategoryName
 */

namespace amos\sitemanagement\models\search;

use amos\sitemanagement\models\PageContent;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use amos\sitemanagement\utility\SiteManagementUtility;

/**
 * Class PageContentSearch
 * PageContentSearch represents the model behind the search form about `amos\sitemanagement\models\PageContent`.
 * @package amos\sitemanagement\models\search
 */
class PageContentSearch extends PageContent
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['tag', 'title', 'created_at', 'updated_at', 'deleted_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * This is the base search.
     * @param array $params
     * @return ActiveQuery
     */
    public function baseSearch($params)
    {
        $query = PageContent::find();
        $this->initOrderVars(); // Init the default search values
        $this->setOrderVars($params); // Check params to get orders value
        return $query;
    }

    /**
     * Search sort.
     * @param ActiveDataProvider $dataProvider
     */
    protected function setSearchSort($dataProvider)
    {
        // Check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort([
                'attributes' => [
                    'tag' => [
                        'asc' => [self::tableName().'.tag' => SORT_ASC],
                        'desc' => [self::tableName().'.tag' => SORT_DESC]
                    ],
                    'title' => [
                        'asc' => [self::tableName().'.title' => SORT_ASC],
                        'desc' => [self::tableName().'.title' => SORT_DESC]
                    ],
                    'content' => [
                        'asc' => [self::tableName().'.content' => SORT_ASC],
                        'desc' => [self::tableName().'.content' => SORT_DESC]
                    ]
                ]
            ]);
        }
    }

    /**
     * Base filter.
     * @param ActiveQuery $query
     * @return mixed
     */
    public function baseFilter($query)
    {
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by
        ]);

        $query->andFilterWhere(['like', self::tableName().'.title', $this->title]);
        $query->andFilterWhere(['like', self::tableName().'.tag', $this->tag]);

        return $query;
    }

    /**
     * Generic search for this model. It return all records.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query       = $this->baseSearch($params);
        $permissions = SiteManagementUtility::getEnabledPermissionsForUpdate();
        // if you don't set  in the platform the permission you can't filter for permission
        if (!empty($permissions)) {
            if (!\Yii::$app->user->can('SITE_MANAGEMENT_ADMINISTRATOR')) {
                $canModify = SiteManagementUtility::getPermissionUserCan($permissions);
                $query->andWhere(['OR',
                    ['site_management_page_content.permission' => $canModify],
                    ['site_management_page_content.permission' => null]]);
            }
        }

        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $this->setSearchSort($dataProvider);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $this->baseFilter($query);
        return $dataProvider;
    }
}