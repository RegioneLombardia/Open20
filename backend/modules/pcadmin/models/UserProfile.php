<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models
 * @category   CategoryName
 */

namespace backend\modules\pcadmin\models;

use open20\amos\core\behaviors\AttributesChangeLogBehavior;
use yii\helpers\ArrayHelper;


use open20\amos\admin\models\UserProfile as BaseUserProfile;


/**
 * Class UserProfile
 *
 * This is the model class for table "user_profile".
 *
 * @package backend\modules\pcadmin\modules
 */
class UserProfile extends BaseUserProfile
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'AttributesChangeLogBehavior' => [
                'class' => AttributesChangeLogBehavior::className(),
                'attributesToLog' => [
                    'privacy',
                ]
            ]
        ]);
    }
}