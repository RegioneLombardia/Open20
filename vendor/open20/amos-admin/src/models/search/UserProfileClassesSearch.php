<?php

namespace open20\amos\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\admin\models\UserProfileClasses;

/**
 * UserProfileClassesSearch represents the model behind the search form about `open20\amos\admin\models\UserProfileClasses`.
 */
class UserProfileClassesSearch extends UserProfileClasses
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
            [['id', 'enabled', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'code', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = UserProfileClasses::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['user_profile_classes.name' => SORT_ASC],
                    'desc' => ['user_profile_classes.name' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['user_profile_classes.description' => SORT_ASC],
                    'desc' => ['user_profile_classes.description' => SORT_DESC],
                ],
                'enabled' => [
                    'asc' => ['user_profile_classes.enabled' => SORT_ASC],
                    'desc' => ['user_profile_classes.enabled' => SORT_DESC],
                ],
        ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }



        $query->andFilterWhere([
            'id' => $this->id,
            'enabled' => $this->enabled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
