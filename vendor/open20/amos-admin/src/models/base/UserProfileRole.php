<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models\base
 * @category   CategoryName
 */

namespace open20\amos\admin\models\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\Record;

/**
 * Class UserProfileRole
 * This is the base-model class for table "user_profile_role".
 *
 * @property integer $id
 * @property string $name
 * @property integer $enabled
 * @property integer $order
 * @property integer $type_cat
 *
 * @property \open20\amos\admin\models\UserProfile[] $userProfiles
 *
 * @package open20\amos\admin\models\base
 */
class UserProfileRole extends Record
{
    const OTHER = 1;
    const TYPE_CAT_GENERIC = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_role';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'enabled', 'order'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['enabled', 'order', 'type_cat'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosAdmin::t('amosadmin', 'ID'),
            'name' => AmosAdmin::t('amosadmin', 'Name'),
            'enabled' => AmosAdmin::t('amosadmin', 'Enabled'),
            'order' => AmosAdmin::t('amosadmin', 'Order'),
            'type_cat' => AmosAdmin::t('amosadmin', 'Type Cat')
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        $modelClass = \open20\amos\admin\AmosAdmin::instance()->createModel('UserProfile');
        return $this->hasMany($modelClass::className(), ['user_profile_role_id' => 'id']);
    }
}
