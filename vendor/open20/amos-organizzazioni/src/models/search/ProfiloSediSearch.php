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

use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\organizzazioni\Module;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ProfiloSediSearch
 * ProfiloSediSearch represents the model behind the search form about `open20\amos\organizzazioni\models\ProfiloSedi`.
 * @package open20\amos\organizzazioni\models\search
 */
class ProfiloSediSearch extends ProfiloSedi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'is_main',
                'active',
                'profilo_id',
                'profilo_sedi_type_id',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [[
                'name',
                'description',
                'phone',
                'fax',
                'email',
                'address',
                'created_at',
                'updated_at',
                'deleted_at'
            ], 'safe'],
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

    public function getScope($params)
    {
        $scope = $this->formName();
        if (!isset($params[$scope])) {
            $scope = '';
        }
        return $scope;
    }

    public function search($params)
    {
        /** @var ProfiloSedi $model */
        $model = Module::instance()->createModel('ProfiloSedi');
        $query = $model::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);

        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'is_main' => $this->is_main,
            'active' => $this->active,
            'profilo_id' => $this->profilo_id,
            'profilo_sedi_type_id' => $this->profilo_sedi_type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'pec', $this->pec])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
