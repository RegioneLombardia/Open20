<?php

namespace backend\modules\registeredusers\models\search;

use backend\modules\registeredusers\models\Operator;
use backend\modules\registeredusers\models\RegisteredUser;
use open20\amos\admin\models\UserProfile;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PreferenceCampainSearch represents the model behind the search form about `backend\modules\campains\models\base\PreferenceCampain`.
 */
class RegisteredUserSearch extends MyUserProfileSearch //\open20\amos\admin\models\search\UserProfileSearch
{
    public $isSearch = true;
    public $statusSearch;
    public $status;
    //private $container; 
    public function rules()
    {
        return [
            [[
                'nome',
                'cognome',
                'codice_fiscale',
                'status'
            ], 'safe'],
            [['comune_residenza_id'], 'safe'],
        ];
    }

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function search($params)
    {
        $query = \backend\modules\registeredusers\models\RegisteredUser::find();
//        $query->andWhere([UserProfile::tableName() . '.attivo' => 1]);
        $query->joinWith('user');
        $query->andWhere(['!=', 'user.status', '50']);
        // $NomeRuoli = ['PC_ADMIN','PC_STRUCTURE_ADMIN','PC_STRUCTURE_EDITOR','PC_EDITOR'];
        $query->leftJoin('auth_assignment', 'user_profile.user_id = auth_assignment.user_id')->andWhere(['auth_assignment.item_name' => self::roleToView()]);

        if (\Yii::$app->user->can('PC_STRUCTURE_EDITOR') || \Yii::$app->user->can('PC_STRUCTURE_ADMIN')) {
            $registereduser=RegisteredUser::findOne(\Yii::$app->user->identity->profile->id);
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
        $query->andFilterWhere([
            'comune_residenza_id' => $this->comune_residenza_id,
        ]);        $query->andFilterWhere([
            'user.status' => $this->status,
        ]);
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
