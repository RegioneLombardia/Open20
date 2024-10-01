<?php

namespace open20\gamification\models\search;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\gamification\models\Gamification;

/**
 * GamificationSearch represents the model behind the search form of `open20\gamification\models\Gamification`.
 */
class GamificationSearch extends Gamification
{
    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['id', 'utente_iscrizione', 'utente_foto', 'utente_colleg', 'community_creazione', 'community_iscrizione',
                 'notizie_creazione', 'notizie_commento', 'notizie_like', 'documenti_creazione', 'discussioni_creazione',
                 'discussioni_commento', 'discussioni_like', 'sondaggi_partec'], 'integer'],
            [['titolo', 'descrizione', 'status', 'starting_date', 'finish_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        //$this->status = null;
        $query = Gamification::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'deleted_by' => $this->deleted_by,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'titolo', $this->titolo])
            ->andFilterWhere(['like', 'descrizione', $this->descrizione])
            //->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['>=', 'starting_date', $this->starting_date])
            ->andFilterWhere(['<=', 'finish_date', $this->finish_date]);

        return $dataProvider;
    }
}
