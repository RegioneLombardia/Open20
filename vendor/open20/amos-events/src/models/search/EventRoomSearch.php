<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

namespace open20\amos\events\models\search;

use open20\amos\events\models\EventRoom;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class EventRoomSearch
 * @package open20\amos\events\models\search
 */
class EventRoomSearch extends EventRoom
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_name', 'available_seats'], 'safe']
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
        /** @var EventRoom $eventRoomModel */
        $eventRoomModel = $this->eventsModule->createModel('EventRoom');
        $query = $eventRoomModel::find();
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
                    'room_name' => [
                        'asc' => [self::tableName() . '.room_name' => SORT_ASC],
                        'desc' => [self::tableName() . '.room_name' => SORT_DESC]
                    ],
                    'available_seats' => [
                        'asc' => [self::tableName() . '.available_seats' => SORT_ASC],
                        'desc' => [self::tableName() . '.available_seats' => SORT_DESC]
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
        $query->andFilterWhere(['available_seats' => $this->available_seats]);
        $query->andFilterWhere(['like', self::tableName() . '.room_name', $this->room_name]);
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
