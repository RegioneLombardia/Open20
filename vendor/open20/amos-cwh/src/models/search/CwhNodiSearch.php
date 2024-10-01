<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\models\search;

use open20\amos\core\record\Record;
use open20\amos\cwh\models\base\CwhAuthAssignment;
use open20\amos\cwh\models\CwhNodi;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CwhNodiSearch represents the model behind the search form about `open20\amos\cwh\models\CwhNodi`.
 */
class CwhNodiSearch extends CwhNodi
{

    /**
     *
     * @param Record $model
     * @param bool $return_query
     * @return Record|Array
     */
    public static function findByModel(Record $model, $return_query = false)
    {
        $permissionPrefix = Yii::$app->getModule('cwh')->permissionPrefix;

        $permission = [
            $permissionPrefix.'_CREATE_'.get_class($model),
            $permissionPrefix.'_VALIDATE_'.get_class($model)
        ];

        $query = CwhNodi::find()
            ->leftJoin(CwhAuthAssignment::tableName(),
                CwhAuthAssignment::tableName().'.cwh_nodi_id = '.self::tableName().'.id')
            ->andWhere([
            CwhAuthAssignment::tableName().'.item_name' => $permission,
            CwhAuthAssignment::tableName().'.user_id' => Yii::$app->getUser()->id
        ]);

        if ($return_query) {
            return $query;
        }
        return $query->all();
    }

    public function rules()
    {
        return [
            [['id', 'classname'], 'safe'],
            [['cwh_config_id', 'record_id'], 'integer'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CwhNodi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cwh_config_id' => $this->cwh_config_id,
            'record_id' => $this->record_id,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'classname', $this->classname]);

        return $dataProvider;
    }
}