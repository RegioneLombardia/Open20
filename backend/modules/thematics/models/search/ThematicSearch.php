<?php

namespace backend\modules\thematics\models\search;

use backend\modules\thematics\models\Operator;
use backend\modules\thematics\models\Thematic;
use open20\amos\admin\models\UserProfile;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PreferenceCampainSearch represents the model behind the search form about `backend\modules\campains\models\base\PreferenceCampain`.
 */
class ThematicSearch extends \backend\modules\thematics\models\Thematic
{

    public function search($params)
    {
        $query = \backend\modules\thematics\models\Thematic::find();
        $query->andWhere([UserProfile::tableName() . '.attivo' => 1]);
        // $NomeRuoli = ['PC_ADMIN','PC_STRUCTURE_ADMIN','PC_STRUCTURE_EDITOR','PC_EDITOR'];
        $query->leftJoin('auth_assignment', 'user_profile.user_id = auth_assignment.user_id')->andWhere(['auth_assignment.item_name' => self::roleToView()]);

        if (\Yii::$app->user->can('PC_STRUCTURE_EDITOR') || \Yii::$app->user->can('PC_STRUCTURE_ADMIN')) {
            $registereduser=Thematic::findOne(\Yii::$app->user->identity->profile->id);
            if(!empty($registereduser->pc_structure_id)){
                $query->andWhere([UserProfile::tableName() . '.pc_structure_id' => $registereduser->pc_structure_id]);
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

        return $dataProvider;
    }

    public static function roleToView()
    {
        return [
            'PC_REGISTERD_USER' => 'PC_REGISTERD_USER',
                    ];
    }
    public static function roleToAssignByRole()
    {
        if (\Yii::$app->user->can('PC_ADMIN')) {
            return [
                'PC_ADMIN' => 'PC_ADMIN',
                'PC_STRUCTURE_ADMIN' => 'PC_STRUCTURE_ADMIN',
                'PC_STRUCTURE_EDITOR' => 'PC_STRUCTURE_EDITOR',
                'PC_EDITOR' => 'PC_EDITOR'
            ];
        }else if (\Yii::$app->user->can('PC_STRUCTURE_ADMIN')) {
            return [
                'PC_STRUCTURE_ADMIN' => 'PC_STRUCTURE_ADMIN',
                'PC_STRUCTURE_EDITOR' => 'PC_STRUCTURE_EDITOR',
            ];
        }

        return [''];
    }
}
