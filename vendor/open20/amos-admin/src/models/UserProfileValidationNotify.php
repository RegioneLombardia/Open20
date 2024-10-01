<?php

namespace open20\amos\admin\models;

use open20\amos\admin\AmosAdmin;

/**
 * This is the model class for table "user_profile_validation_notify".
 */
class UserProfileValidationNotify extends \open20\amos\admin\models\base\UserProfileValidationNotify
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;
    
    public function representingColumn()
    {
        return [
            'status'
        ];
    }
    
    public function getEditFields()
    {
        $labels = $this->attributeLabels();
        
        return [
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'integer'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
        ];
    }
    
    /**
     * @param int $user_id
     * @param bool $status
     * @throws \yii\base\InvalidConfigException
     */
    public function createNotify($user_id, $status)
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();

        /** @var UserProfileValidationNotify $newModel */
        $newModel = $adminModule->createModel('UserProfileValidationNotify');
        
        /** @var UserProfileValidationNotify $notify */
        $notify = $newModel::find()->andWhere(['user_id' => $user_id])->andWhere(['status' => $status])->one();
        
        if (empty($notify)) {
            /** @var UserProfileValidationNotify $notify */
            $notify = $adminModule->createModel('UserProfileValidationNotify');
            $notify->user_id = $user_id;
            $notify->status = $status;
            $notify->save();
        }
    }
}
