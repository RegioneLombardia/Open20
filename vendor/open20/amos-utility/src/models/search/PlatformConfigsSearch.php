<?php

namespace open20\amos\utility\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\utility\models\PlatformConfigs;

/**
 * PlatformConfigsSearch represents the model behind the search form about `open20\amos\utility\models\PlatformConfigs`.
 */
class PlatformConfigsSearch extends PlatformConfigs
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
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['module', 'key', 'value', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PlatformConfigs::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'module' => [
                    'asc' => ['platform_configs.module' => SORT_ASC],
                    'desc' => ['platform_configs.module' => SORT_DESC],
                ],
                'key' => [
                    'asc' => ['platform_configs.key' => SORT_ASC],
                    'desc' => ['platform_configs.key' => SORT_DESC],
                ],
                'value' => [
                    'asc' => ['platform_configs.value' => SORT_ASC],
                    'desc' => ['platform_configs.value' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'deleted_by' => $this->deleted_by,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
