<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\models\search
 * @category   CategoryName
 */

namespace open20\amos\community\models\search;

use open20\amos\community\models\CommunityUserMm;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class CommunityUserMmSearch extends CommunityUserMm
{
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by', 'user_id', 'community_id'], 'integer'],
            [['status', 'role', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveQuery $query
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        return self::find()->distinct();
    }

    /**
     * @param array $params $_GET search parameters array
     * @param string $queryType , depending on the index tab user is on (communities created by me, my communities, all communities,..)
     * @param bool $onlyActiveStatus
     * @param int|null $limit the query limit
     * @return ActiveDataProvider $dataProvider
     */
    public function search($params, $limit = null, $onlyActiveStatus = false)
    {
        $query = $this->baseSearch($params);

        $query->limit($limit);

        $dp_params = ['query' => $query,];
        if ($limit) {
            $dp_params ['pagination'] = false;
        }
        $dataProvider = new ActiveDataProvider($dp_params);

        //check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort([
                'defaultOrder' => [
                    $this->orderAttribute => (int)$this->orderType
                ]
            ]);
        } else { //for widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            self::tableName().'.community_id' => $this->community_id,
            self::tableName().'.user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'status' => $this->status,
            'role' => $this->role
        ]);

        return $dataProvider;
    }
}