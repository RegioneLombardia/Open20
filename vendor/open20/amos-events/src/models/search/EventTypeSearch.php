<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models\search
 * @category   CategoryName
 */

namespace open20\amos\events\models\search;

use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventType;
use open20\amos\events\models\EventTypeContext;
use open20\amos\events\utility\EventsUtility;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class EventTypeSearch
 * EventTypeSearch represents the model behind the search form about `open20\amos\events\models\EventType`.
 * @package open20\amos\events\models\search
 */
class EventTypeSearch extends EventType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'locationRequested', 'durationRequested', 'logoRequested', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'color', 'locationRequested', 'durationRequested', 'logoRequested', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        /** @var EventType $eventTypeModel */
        $eventTypeModel = $this->eventsModule->createModel('EventType');
        $query = $eventTypeModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'title' => [
                    'asc' => [self::tableName() . '.title' => SORT_ASC],
                    'desc' => [self::tableName() . '.title' => SORT_DESC],
                ],
                'description' => [
                    'asc' => [self::tableName() . '.description' => SORT_ASC],
                    'desc' => [self::tableName() . '.description' => SORT_DESC],
                ],
                'color' => [
                    'asc' => [self::tableName() . '.color' => SORT_ASC],
                    'desc' => [self::tableName() . '.color' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', self::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', self::tableName() . '.description', $this->description])
            ->andFilterWhere([self::tableName() . '.color' => $this->color])
            ->andFilterWhere([self::tableName() . '.locationRequested' => $this->locationRequested])
            ->andFilterWhere([self::tableName() . '.durationRequested' => $this->durationRequested])
            ->andFilterWhere([self::tableName() . '.logoRequested' => $this->logoRequested]);

        return $dataProvider;
    }

    /**
     * This method search all event types.
     * @return Query
     */
    public static function searchAllEventTypesBaseQuery()
    {
        $query = new Query();
        $query->from(EventType::tableName());
        $query->andWhere(['deleted_at' => null]);
        return $query;
    }

    /**
     * This method search only the enabled event types.
     * @return Query
     */
    public static function searchEnabledEventTypesQuery()
    {
        $query = static::searchAllEventTypesBaseQuery();
        $query->andWhere(['enabled' => EventType::ENABLED]);
        return $query;
    }

    /**
     * This method search only the enabled event types ready for select.
     * @return array
     */
    public static function searchAllEventTypesReadyForSelect()
    {
        $query = static::searchAllEventTypesBaseQuery();
        $query->select(['title']);
        $query->indexBy('id');
        $eventTypesEnabled = $query->column();
        $eventTypesEnabled = EventsUtility::translateArrayValues($eventTypesEnabled);
        return $eventTypesEnabled;
    }

    /**
     * This method search only the enabled event types ready for select.
     * @param int $eventContextId
     * @return array
     */
    public static function searchEnabledEventTypesReadyForSelect($eventContextId = 0)
    {
        $moduleEvents = \Yii::$app->getModule(AmosEvents::getModuleName());
        
        $query = static::searchEnabledEventTypesQuery();
        if ($eventContextId > 0) {
            $query->andWhere(['event_context_id' => $eventContextId]);
        }
        if($moduleEvents->dropdownEventTypeDisabled){
            $query->andWhere(['event_type' => self::TYPE_INFORMATIVE]);
        }
        $query->select(['title']);
        $query->indexBy('id');
        $eventTypesEnabled = $query->column();
        $eventTypesEnabled = EventsUtility::translateArrayValues($eventTypesEnabled);
        return $eventTypesEnabled;
    }

    /**
     * This method search only the event types of generic context.
     * @return ActiveQuery
     */
    public static function searchGenericContextEventTypes()
    {
        /** @var EventType $eventTypeModel */
        $eventTypeModel = AmosEvents::instance()->createModel('EventType');
        return $eventTypeModel::find()->andWhere(['event_context_id' => EventTypeContext::EVENT_TYPE_CONTEXT_GENERIC]);
    }

    /**
     * This method search only the enabled event types of generic context ready for select.
     * @return array
     */
    public static function searchEnabledGenericContextEventTypesReadyForSelect()
    {
        return static::searchEnabledEventTypesReadyForSelect(EventTypeContext::EVENT_TYPE_CONTEXT_GENERIC);
    }
	
	/**
     * This method search only the enabled event types of generic context and the icon.
     * @return array
     */
    public static function listEnabledGenericContextEventTypesAndIcon()
    {
		$list = static::searchEnabledGenericContextEventTypesReadyForSelect();
		$array_icons = [];
		foreach ($list as $key => $value){
			$eventType = EventType::findOne($key);
                        $array_icons[$key]['type'] = $eventType->event_type;
                        $array_icons[$key]['color'] = $eventType->color;
			$array_icons[$key]['icon'] = $eventType->type_icon;
			$array_icons[$key]['title'] = $value;
		}
		return $array_icons;
    }

    /**
     * This method search only the event types of project context.
     * @return ActiveQuery
     */
    public static function searchProjectContextEventTypes()
    {
        /** @var EventType $eventTypeModel */
        $eventTypeModel = AmosEvents::instance()->createModel('EventType');
        return $eventTypeModel::find()->andWhere(['event_context_id' => EventTypeContext::EVENT_TYPE_CONTEXT_PROJECT]);
    }

    /**
     * This method search only the enabled event types of generic context ready for select.
     * @return array
     */
    public static function searchEnabledProjectContextEventTypesReadyForSelect()
    {
        return static::searchEnabledEventTypesReadyForSelect(EventTypeContext::EVENT_TYPE_CONTEXT_PROJECT);
    }

    /**
     * This method search only the event types of matchmaking context.
     * @return ActiveQuery
     */
    public static function searchMatchmakingContextEventTypes()
    {
        /** @var EventType $eventTypeModel */
        $eventTypeModel = AmosEvents::instance()->createModel('EventType');
        return $eventTypeModel::find()->andWhere(['event_context_id' => EventTypeContext::EVENT_TYPE_CONTEXT_MATCHMAKING]);
    }

    /**
     * This method search only the enabled event types of generic context ready for select.
     * @return array
     */
    public static function searchEnabledMatchmakingContextEventTypesReadyForSelect()
    {
        return static::searchEnabledEventTypesReadyForSelect(EventTypeContext::EVENT_TYPE_CONTEXT_MATCHMAKING);
    }
}
