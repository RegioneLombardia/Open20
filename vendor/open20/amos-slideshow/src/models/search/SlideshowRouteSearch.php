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

use open20\amos\slideshow\models\SlideshowRoute;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SlideshowRouteSearch
 * @package open20\amos\slideshow\models\search
 *
 * SlideshowRouteSearch represents the model behind the search form about `open20\amos\slideshow\models\SlideshowRoute`.
 */
class SlideshowRouteSearch extends SlideshowRoute
{
    public function rules()
    {
        return [
            [['id', 'already_view', 'slideshow_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['route', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SlideshowRoute::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'already_view' => $this->already_view,
            'slideshow_id' => $this->slideshow_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'route', $this->route]);

        return $dataProvider;
    }
}
