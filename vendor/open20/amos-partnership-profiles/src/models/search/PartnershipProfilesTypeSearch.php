<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\models\search
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\models\search;

use open20\amos\partnershipprofiles\models\PartnershipProfilesType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class PartnershipProfilesTypeSearch
 * PartnershipProfilesTypeSearch represents the model behind the search form about `open20\amos\partnershipprofiles\models\PartnershipProfilesType`.
 * @package open20\amos\partnershipprofiles\models\search
 */
class PartnershipProfilesTypeSearch extends PartnershipProfilesType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
    
    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PartnershipProfilesType::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => [self::tableName() . '.name' => SORT_ASC],
                    'desc' => [self::tableName() . '.name' => SORT_DESC],
                ],
                'description' => [
                    'asc' => [self::tableName() . '.description' => SORT_ASC],
                    'desc' => [self::tableName() . '.description' => SORT_DESC],
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
        
        $query->andFilterWhere(['like', self::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', self::tableName() . '.description', $this->description]);
        
        return $dataProvider;
    }
}
