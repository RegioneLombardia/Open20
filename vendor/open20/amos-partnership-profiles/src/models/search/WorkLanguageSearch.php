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

use open20\amos\partnershipprofiles\models\WorkLanguage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class WorkLanguageSearch
 * WorkLanguageSearch represents the model behind the search form about `open20\amos\partnershipprofiles\models\WorkLanguage`.
 * @package open20\amos\partnershipprofiles\models\search
 */
class WorkLanguageSearch extends WorkLanguage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['work_language_code', 'work_language', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = WorkLanguage::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                'work_language_code' => [
                    'asc' => [self::tableName() . '.work_language_code' => SORT_ASC],
                    'desc' => [self::tableName() . '.work_language_code' => SORT_DESC],
                ],
                'work_language' => [
                    'asc' => [self::tableName() . '.work_language' => SORT_ASC],
                    'desc' => [self::tableName() . '.work_language' => SORT_DESC],
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
        
        $query->andFilterWhere(['like', self::tableName() . '.work_language_code', $this->work_language_code]);
        $query->andFilterWhere(['like', self::tableName() . '.work_language', $this->work_language]);
        
        return $dataProvider;
    }
}
