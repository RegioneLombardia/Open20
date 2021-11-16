<?php

namespace open20\amos\events\models\search;

use open20\amos\events\utility\EventsUtility;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\events\models\EventContainer;

/**
 * EventContainerSearch represents the model behind the search form about `open20\amos\events\models\EventContainer`.
 */
class EventContainerSearch extends EventContainer
{

    public $isSearch;
//private $container; 

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'luya_template_id', 'luya_page_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = EventContainer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if(!\Yii::$app->user->can('SUPER_USER_EVENT')){
            $group = EventsUtility::getCurrentDg();
            $query->innerJoin('event_group_referent', 'event_group_referent.id = event_container.event_group_referent_id')
                ->andWhere(['event_group_referent.id' => $group->id]);
        }
        $dataProvider->setSort([
            'attributes' => [
                'title' => [
                    'asc' => ['event_container.title' => SORT_ASC],
                    'desc' => ['event_container.title' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['event_container.description' => SORT_ASC],
                    'desc' => ['event_container.description' => SORT_DESC],
                ],
                'luya_template_id' => [
                    'asc' => ['event_container.luya_template_id' => SORT_ASC],
                    'desc' => ['event_container.luya_template_id' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'luya_template_id' => $this->luya_template_id,
            'luya_page_id' => $this->luya_page_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
