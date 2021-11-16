<?php

/**
 * Lombardia Informatica S.p.A.
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
    public static function findByModel(Record $model)
    {
        $user_id = Yii::$app->getUser()->getId();


        $permission = [
            Yii::$app->getModule('cwh')->permissionPrefix . '_CREATE_' . get_class($model),
            Yii::$app->getModule('cwh')->permissionPrefix . '_VALIDATE_' . get_class($model)
        ];


        return CwhNodi::find()
            ->leftJoin(CwhAuthAssignment::tableName(), CwhAuthAssignment::tableName() . '.cwh_nodi_id = ' . self::tableName() . '.id')
            ->andWhere([
                CwhAuthAssignment::tableName() . '.item_name' => $permission,
                CwhAuthAssignment::tableName() . '.user_id' => Yii::$app->getUser()->id
            ])->all();

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
