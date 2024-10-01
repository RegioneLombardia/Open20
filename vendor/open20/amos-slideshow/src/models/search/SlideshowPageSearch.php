<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

namespace open20\amos\slideshow\models\search;

use open20\amos\slideshow\models\SlideshowPage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SlideshowPageSearch
 * @package open20\amos\slideshow\models\search
 *
 * SlideshowPageSearch represents the model behind the search form about `open20\amos\slideshow\models\SlideshowPages`.
 */
class SlideshowPageSearch extends SlideshowPage
{
    public function rules()
    {
        return [
            [['id', 'ordinal', 'slideshow_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'pageContent', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SlideshowPage::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ordinal' => $this->ordinal,
            'slideshow_id' => $this->slideshow_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'pageContent', $this->pageContent]);

        return $dataProvider;
    }

    public function searchSlideshowPages($params)
    {
        $query = SlideshowPage::find();
        $query->andFilterWhere([
            'slideshow_id' => $params['slideshowId']
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ordinal' => SORT_ASC,
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
