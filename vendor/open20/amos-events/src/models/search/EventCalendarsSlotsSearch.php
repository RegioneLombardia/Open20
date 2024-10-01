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

use open20\amos\events\models\EventCalendarsSlots;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;

/**
 * Class EventCalendarsSlotsSearch
 * EventCalendarsSlotsSearch represents the model behind the search form about `open20\amos\events\models\EventCalendarsSlots`.
 * @package open20\amos\events\models\search
 */
class EventCalendarsSlotsSearch extends EventCalendarsSlots
{
    public $event;
    public $isSearch;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'event_calendars_id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['date', 'event', 'hour_start', 'hour_end', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [[
                'date_from',
                'date_to',
            ], 'safe'],
            ['User', 'safe'],
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

    public function search($params)
    {
        /** @var EventCalendarsSlots $eventCalendarsSlotsModel */
        $eventCalendarsSlotsModel = $this->eventsModule->createModel('EventCalendarsSlots');

        /** @var ActiveQuery $query */
        $query = $eventCalendarsSlotsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('user');

        $dataProvider->setSort([
            'attributes' => [
                'event_calendars_id' => [
                    'asc' => ['event_calendars_slots.event_calendars_id' => SORT_ASC],
                    'desc' => ['event_calendars_slots.event_calendars_id' => SORT_DESC],
                ],
                'date' => [
                    'asc' => ['event_calendars_slots.date' => SORT_ASC],
                    'desc' => ['event_calendars_slots.date' => SORT_DESC],
                ],
                'hour_start' => [
                    'asc' => ['event_calendars_slots.hour_start' => SORT_ASC],
                    'desc' => ['event_calendars_slots.hour_start' => SORT_DESC],
                ],
                'hour_end' => [
                    'asc' => ['event_calendars_slots.hour_end' => SORT_ASC],
                    'desc' => ['event_calendars_slots.hour_end' => SORT_DESC],
                ],
                'user_id' => [
                    'asc' => ['event_calendars_slots.user_id' => SORT_ASC],
                    'desc' => ['event_calendars_slots.user_id' => SORT_DESC],
                ],
                'user' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                ],]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'event_calendars_id' => $this->event_calendars_id,
            'date' => $this->date,
            'hour_start' => $this->hour_start,
            'hour_end' => $this->hour_end,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);
        $query->andFilterWhere(['>=', 'date', $this->date_from]);
        $query->andFilterWhere(['<=', 'date', $this->date_to]);
        $query->andFilterWhere(['like', new \yii\db\Expression('user.username'), $this->User]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function mySlotsAllSearch($params, $limit = null){
        $query = EventCalendarsSlots::find()
            ->innerJoinWith('eventCalendars')
            ->innerJoin('event_calendars_slots_booked','event_calendars_slots.id = event_calendars_slots_booked.event_calendars_slots_id')
            ->andWhere(['event_calendars_slots_booked.deleted_at' => null])
            ->andWhere(['event_calendars_slots_booked.user_id' => \Yii::$app->user->id])
            ->andFilterWhere(['event_id' => $this->event]);

        $this->load($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        return $dataProvider;
    }


}
