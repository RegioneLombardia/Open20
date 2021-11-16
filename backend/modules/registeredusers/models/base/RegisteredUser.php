<?php

namespace backend\modules\registeredusers\models\base;

use Yii;
use DateTime;
use Throwable;
use yii\helpers\ArrayHelper;
use open20\amos\core\user\User;
use preference\userprofile\models\UserProfile;
use open20\amos\core\record\ContentModel;


class RegisteredUser extends UserProfile
{

    public $email;
    public $password;
    public $role;

    /**
     * Metodo beforeSave per il set manualmente del formato per la data nascita campo nascita_data, formato ('Y-m-d') da formato ('d-m-Y')
     *
     * @param boolean $insert
     * @return void
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        /**
         * set manualmente del formato per la data nascita campo nascita_data, formato ('Y-m-d') da formato ('d-m-Y')
         * e controllo la validita della data inserita
         */
        try {

            if( null != \Yii::$app->request->post("nascita_data-registereduser-nascita_data") ){

                $date = new DateTime(\Yii::$app->request->post("nascita_data-registereduser-nascita_data"));
                $post_data_nascita = explode('-', \Yii::$app->request->post("nascita_data-registereduser-nascita_data"));

                if( $date->format('d') == $post_data_nascita[0] 
                    && $date->format('m') == $post_data_nascita[1] 
                    && $date->format('Y') == $post_data_nascita[2]){
                        
                    $this->nascita_data = $date->format('Y-m-d');

                }else{
                    $this->addError("nascita_data", 'Data nascita non valida.');
                    return false;
                }
            }


        } catch (\Throwable $th) {
            $this->addError("nascita_data", 'Data nascita non valida.');
            return false;
        }

        return true;
    }


    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'email' => 'Email',
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['role', 'safe'],
            ['password', 'safe'],
            //  ['pc_structure_id', 'integer'],
            ['email', 'required'],
            // [['pc_structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\modules\registeredusers\models\PreferenceStructure::className(), 'targetAttribute' => ['pc_structure_id' => 'id']],
            ['email', 'email'],
            //['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],


        ]);
    }
    //
    //    public function getPCRole()
    //    {
    //        $ruoli = \Yii::$app->authManager->getRolesByUser($this->user_id);
    //        foreach ($ruoli as $ruoloC) {
    //            if (in_array($ruoloC->name, \backend\modules\registeredusers\models\search\OperatorSearch::roleToView())) {
    //                return $ruoloC;
    //            }
    //        }
    //        return null;
    //    }
    //    /**
    //     * @return \yii\db\ActiveQuery
    //     */
    //    public function getPreferenceStructure()
    //    {
    //        return $this->hasOne(PreferenceStructure::className(), ['id' => 'pc_structure_id']);
    //    }

}
