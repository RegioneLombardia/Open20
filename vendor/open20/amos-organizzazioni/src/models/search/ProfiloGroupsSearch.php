<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models\search
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models\search;

use open20\amos\organizzazioni\models\ProfiloGroups;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class ProfiloGroupsSearch
 * @package open20\amos\organizzazioni\models\search
 */
class ProfiloGroupsSearch extends ProfiloGroups
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'safe']
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
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function baseSearch($params)
    {
        /** @var ProfiloGroups $model */
        $model = $this->organizzazioniModule->createModel('ProfiloGroups');
        $query = $model::find();
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
                    'name' => [
                        'asc' => [self::tableName() . '.name' => SORT_ASC],
                        'desc' => [self::tableName() . '.name' => SORT_DESC]
                    ],
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
        $query->andFilterWhere(['like', self::tableName() . '.name', $this->name]);
        $query->andFilterWhere(['like', self::tableName() . '.description', $this->description]);
        return $query;
    }

    /**
     * Generic search for this model. It return all records.
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        $query = $this->baseSearch($params);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $this->setSearchSort($dataProvider);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $this->baseFilter($query);
        return $dataProvider;
    }
}
