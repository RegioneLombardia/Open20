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

use open20\amos\events\models\EventCalendars;
use open20\amos\events\models\EventCalendars as EventCalendarsModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class EventCalendarsSearch
 * EventCalendars represents the model behind the search form about `open20\amos\events\models\EventCalendars`.
 * @package open20\amos\events\models\search
 */
class EventCalendarsSearch extends EventCalendarsModel
{
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
            [['id', 'event_id', 'slot_duration', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'date_start', 'date_end', 'hour_start', 'hour_end', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [[
                'date_start_from',
                'date_start_to',
                'date_end_from',
                'date_end_to',
            ], 'safe'],
            ['Event', 'safe'],
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
        /** @var EventCalendars $eventCalendarsModel */
        $eventCalendarsModel = $this->eventsModule->createModel('EventCalendars');

        /** @var ActiveQuery $query */
        $query = $eventCalendarsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('event');

        $dataProvider->setSort([
            'attributes' => [
                'event_id' => [
                    'asc' => ['event_calendars.event_id' => SORT_ASC],
                    'desc' => ['event_calendars.event_id' => SORT_DESC],
                ],
                'title' => [
                    'asc' => ['event_calendars.title' => SORT_ASC],
                    'desc' => ['event_calendars.title' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['event_calendars.description' => SORT_ASC],
                    'desc' => ['event_calendars.description' => SORT_DESC],
                ],
                'date_start' => [
                    'asc' => ['event_calendars.date_start' => SORT_ASC],
                    'desc' => ['event_calendars.date_start' => SORT_DESC],
                ],
                'date_end' => [
                    'asc' => ['event_calendars.date_end' => SORT_ASC],
                    'desc' => ['event_calendars.date_end' => SORT_DESC],
                ],
                'hour_start' => [
                    'asc' => ['event_calendars.hour_start' => SORT_ASC],
                    'desc' => ['event_calendars.hour_start' => SORT_DESC],
                ],
                'hour_end' => [
                    'asc' => ['event_calendars.hour_end' => SORT_ASC],
                    'desc' => ['event_calendars.hour_end' => SORT_DESC],
                ],
                'slot_duration' => [
                    'asc' => ['event_calendars.slot_duration' => SORT_ASC],
                    'desc' => ['event_calendars.slot_duration' => SORT_DESC],
                ],
                'event' => [
                    'asc' => ['event.title' => SORT_ASC],
                    'desc' => ['event.title' => SORT_DESC],
                ],]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'event_id' => $this->event_id,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'hour_start' => $this->hour_start,
            'hour_end' => $this->hour_end,
            'slot_duration' => $this->slot_duration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['>=', 'date_start', $this->date_start_from]);
        $query->andFilterWhere(['<=', 'date_start', $this->date_start_to]);
        $query->andFilterWhere(['>=', 'date_end', $this->date_end_from]);
        $query->andFilterWhere(['<=', 'date_end', $this->date_end_to]);
        $query->andFilterWhere(['like', new \yii\db\Expression('event.title'), $this->Event]);

        return $dataProvider;
    }
}
