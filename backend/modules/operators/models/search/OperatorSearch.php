<?php

namespace backend\modules\operators\models\search;

use backend\modules\operators\models\Operator;
use open20\amos\admin\models\UserProfile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * PreferenceCampainSearch represents the model behind the search form about `backend\modules\campains\models\base\PreferenceCampain`.
 */
class OperatorSearch extends \open20\amos\admin\models\search\UserProfileSearch
{
    public $struttura;
    public function rules()
    {
        return [
            [[
                'nome',
                'cognome',
            ], 'safe'],
            [['struttura'], 'safe'],
        ];
    }
    public function search($params)
    {
        /** @var ActiveQuery $query */
        $query = \backend\modules\operators\models\Operator::find();
        $query->innerJoinWith('user');
        // $query->andWhere(['user.status' => 10]);

        //$query->andWhere([UserProfile::tableName() . '.attivo' => 1]);
        // $NomeRuoli = ['PC_ADMIN','PC_STRUCTURE_ADMIN','PC_STRUCTURE_EDITOR','PC_EDITOR'];
        $query->leftJoin('auth_assignment', 'user_profile.user_id = auth_assignment.user_id')->andWhere(['auth_assignment.item_name' => self::roleToView()]);

        if (\Yii::$app->user->can('PC_STRUCTURE_EDITOR') || \Yii::$app->user->can('PC_STRUCTURE_ADMIN')|| \Yii::$app->user->can('PC_STRUCTURE_APPROVER')) {
            $operatore=Operator::findOne(\Yii::$app->user->identity->profile->id);
            if(!empty($operatore->pc_structure_id)){
                $query->andWhere([UserProfile::tableName() . '.pc_structure_id' => $operatore->pc_structure_id]);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $this->setUserProfileSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);
        $query->joinWith('preferenceStructure');
        $query->andFilterWhere(['like', 'preference_structure.id', $this->struttura]);

        return $dataProvider;
    }

    public static function roleToView()
    {
        return [
            'PC_ADMIN' => 'PC_ADMIN',
            'PC_STRUCTURE_ADMIN' => 'PC_STRUCTURE_ADMIN',
            'PC_STRUCTURE_EDITOR' => 'PC_STRUCTURE_EDITOR',
            'PC_EDITOR' => 'PC_EDITOR',
            'PC_APPROVER' => 'PC_APPROVER',
            'PC_STRUCTURE_APPROVER' => 'PC_STRUCTURE_APPROVER'
        ];
    }
    public static function roleToAssignByRole()
    {
        if (\Yii::$app->user->can('PC_ADMIN')) {
            return [
                'PC_ADMIN' => 'Amministratore',
                'PC_STRUCTURE_ADMIN' => 'Amministratore di struttura',
                'PC_EDITOR' => 'Operatore',
                'PC_STRUCTURE_EDITOR' => 'Operatore di struttura',
                'PC_APPROVER' => 'Validatore',
                'PC_STRUCTURE_APPROVER' => 'Validatore di struttura'
            ];
        } 
        
        if (\Yii::$app->user->can('PC_STRUCTURE_ADMIN')) {
            return [
                'PC_STRUCTURE_ADMIN' => 'Amministratore di struttura',
                'PC_STRUCTURE_EDITOR' => 'Operatore di struttura',
                'PC_STRUCTURE_APPROVER' => 'Validatore di struttura'
            ];
        }

        return [];
    }
}
