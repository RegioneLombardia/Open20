<?php

namespace open20\amos\sondaggi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;

/**
* SondaggiRisposteSessioniSearch represents the model behind the search form about `open20\amos\sondaggi\models\SondaggiRisposteSessioni`.
*/
class SondaggiRisposteSessioniSearch extends SondaggiRisposteSessioni
{
public function rules()
{
return [
[['id', 'user_profile_id', 'sondaggi_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['session_id', 'unique_id', 'begin_date', 'end_date', 'session_tmp', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
];
}

public function scenarios()
{
// bypass scenarios() implementation in the parent class
return Model::scenarios();
}

public function search($params)
{
$query = SondaggiRisposteSessioni::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

if (!($this->load($params) && $this->validate())) {
return $dataProvider;
}

$query->andFilterWhere([
            'id' => $this->id,
            'begin_date' => $this->begin_date,
            'end_date' => $this->end_date,
            'user_profile_id' => $this->user_profile_id,
            'sondaggi_id' => $this->sondaggi_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'session_id', $this->session_id])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'session_tmp', $this->session_tmp]);

return $dataProvider;
}
}
