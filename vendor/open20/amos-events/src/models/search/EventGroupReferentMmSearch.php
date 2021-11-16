<?php

namespace open20\amos\events\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\events\models\EventGroupReferentMm;

/**
 * EventGroupReferentMmSearch represents the model behind the search form about `open20\amos\events\models\EventGroupReferentMm`.
 */
class EventGroupReferentMmSearch extends EventGroupReferentMm
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
            [['exclude_from_query', 'id', 'user_id', 'event_group_referent_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['EventGroupReferent', 'safe'],
            [['nomeCognome', 'denominazione'], 'string'],
            [['email',], 'email'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query        = EventGroupReferentMm::find();
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('eventGroupReferent');
        $query->joinWith('user');

        $dataProvider->setSort([
            'attributes' => [
                'user_id' => [
                    'asc' => ['event_group_referent_mm.user_id' => SORT_ASC],
                    'desc' => ['event_group_referent_mm.user_id' => SORT_DESC],
                ],
                'event_group_referent_id' => [
                    'asc' => ['event_group_referent_mm.event_group_referent_id' => SORT_ASC],
                    'desc' => ['event_group_referent_mm.event_group_referent_id' => SORT_DESC],
                ],
                'eventGroupReferent' => [
                    'asc' => ['event_group_referent.denominazione' => SORT_ASC],
                    'desc' => ['event_group_referent.denominazione' => SORT_DESC],
                ], 'user' => [
                    'asc' => ['user.email' => SORT_ASC],
                    'desc' => ['user.email' => SORT_DESC],
                ],]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }



        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'event_group_referent_id' => $this->event_group_referent_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);
//        $query->andFilterWhere( ['exclude_from_query' => $this->exclude_from_query]);
        $query->andFilterWhere(['like', new \yii\db\Expression('event_group_referent.denominazione'), $this->denominazione]);
        $query->andFilterWhere(['like', new \yii\db\Expression('user.email'), $this->email]);

        return $dataProvider;
    }
}