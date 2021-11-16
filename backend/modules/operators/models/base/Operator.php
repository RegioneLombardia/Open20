<?php

namespace backend\modules\operators\models\base;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\record\ContentModel;
use open20\amos\core\user\User;
use open20\amos\core\helpers\Html;
use open20\amos\core\validators\CfPivaValidator;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;


class Operator extends UserProfile
{

    public $email;
    public $password;
    public $role;
    public $frontend_role_flag;

    //public $pc_structure_id;

    public function rules()
    {
        $toret =  ArrayHelper::merge([
            ['role', 'required'],
            ['password', 'safe'],
            ['pc_structure_id', 'integer'],
            [['email', 'codice_fiscale'], 'required'],
            [['pc_structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\modules\operators\models\PreferenceStructure::className(), 'targetAttribute' => ['pc_structure_id' => 'id']],
            [['pc_structure_id'], 'required', 'when' => function ($model) {
                return ($this->role == 'PC_STRUCTURE_EDITOR' || $this->role == 'PC_STRUCTURE_ADMIN' || $this->role == 'PC_STRUCTURE_APPROVER');
            }, 'whenClient' => "function (attribute, value) {
                return ($('#" . Html::getInputId($this, 'role') . "').val() == 'PC_STRUCTURE_EDITOR' || $('#" . Html::getInputId($this, 'role') . "').val() == 'PC_STRUCTURE_ADMIN' || $('#" . Html::getInputId($this, 'role') . "').val() == 'PC_STRUCTURE_APPROVER');
            }"],
            ['email', 'email'],
            ['frontend_role_flag', 'safe'],
            // ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            // ['codice_fiscale', 'unique', 'targetClass' => 'open20\amos\admin\models\UserProfile', 'message' => 'This email address has already been taken.'],


        ], parent::rules());
        return $toret;
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'frontend_role_flag' => 'Attiva profilo utente di frontend',
            ]);
    }

    public function getPCRole()
    {
        $ruoli = \Yii::$app->authManager->getRolesByUser($this->user_id);
        foreach ($ruoli as $ruoloC) {
            if (in_array($ruoloC->name, \backend\modules\operators\models\search\OperatorSearch::roleToView())) {
                return $ruoloC;
            }
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceStructure()
    {
        return $this->hasOne(PreferenceStructure::className(), ['id' => 'pc_structure_id']);
    }

    public function getRoleName()
    {
        $names = [
            'PC_ADMIN' => 'Amministratore',
            'PC_STRUCTURE_ADMIN' => 'Amministratore di struttura',
            'PC_EDITOR' => 'Operatore',
            'PC_STRUCTURE_EDITOR' => 'Operatore di struttura',
            'PC_APPROVER' => 'Validatore',
            'PC_STRUCTURE_APPROVER' => 'Validatore di struttura'
        ];

        return isset($names[$this->role])? $names[$this->role]: '';
    }

}
