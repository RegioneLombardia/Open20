<?php

namespace open20\amos\sondaggi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\sondaggi\models\SondaggiDomandePagine;

/**
 * SondaggiDomandePagineSearch represents the model behind the search form about `open20\amos\sondaggi\models\SondaggiDomandePagine`.
 */
class SondaggiDomandePagineSearch extends SondaggiDomandePagine {

    public function rules() {
        return [
            [['id', 'sondaggi_id', 'filemanager_mediafile_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['titolo', 'descrizione', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios() {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = SondaggiDomandePagine::find();
        if (isset($params['idSondaggio'])) {
            $query->andWhere(['sondaggi_id' => $params['idSondaggio']]);
        }
        $query->orderBy('ordinamento, id ASC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sondaggi_id' => $this->sondaggi_id,
            'filemanager_mediafile_id' => $this->filemanager_mediafile_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'titolo', $this->titolo])
                ->andFilterWhere(['like', 'descrizione', $this->descrizione]);

        return $dataProvider;
    }

}
