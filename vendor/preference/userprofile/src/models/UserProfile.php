<?php

namespace preference\userprofile\models;

use yii\helpers\ArrayHelper;
use open20\amos\admin\models\UserProfile as UserProfileBase;


class UserProfile extends UserProfileBase
{

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['pc_request_delete_date', 'safe'],
            [['preference_origin_system_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceOriginSystem::className(), 'targetAttribute' => ['preference_origin_system_id' => 'id']],
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceOriginSystem()
    {
        return $this->hasOne(PreferenceOriginSystem::className(), ['id' => 'preference_origin_system_id']);
    }

    public function getOriginSystemName()
    {
        return $this->preferenceOriginSystem->name;
    }
    
}
