<?php

namespace backend\modules\campains\models\base;

use backend\modules\campains\models\PreferenceCampainChannelMm;
use open20\amos\core\user\User;
use backend\modules\campains\Module;
use Yii;

/**
 * This is the base-model class for table "preference_communication_sent".
 *
 * @property integer $id
 * @property integer $event_communication_id
 * @property integer $user_id
 * @property string $email
 * @property string $phone
 * @property string $sent_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property PreferenceCampainChannelMm $preferenceCampainChannel
 * @property User $user
 */
class PreferenceCommunicationSent extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_communication_sent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preference_campain_channel_mm_id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['sent_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 255],
            [['preference_campain_channel_mm_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceCampainChannelMm::className(), 'targetAttribute' => ['preference_campain_channel_mm_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('campains',  'ID'),
            'preference_campain_channel_mm_id' => Module::t('campains',  'channel'),
            'user_id' => Module::t('campains',  'User'),
            'email' => Module::t('campains',  'Email'),
            'phone' => Module::t('campains',  'Phone'),
            'sent_at' => Module::t('campains',  'Send at'),
            'created_at' => Module::t('campains',  'Created at'),
            'updated_at' => Module::t('campains',  'Updated at'),
            'deleted_at' => Module::t('campains',  'Deleted at'),
            'created_by' => Module::t('campains',  'Created by'),
            'updated_by' => Module::t('campains',  'Updated at'),
            'deleted_by' => Module::t('campains',  'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceCampainChannel()
    {
        return $this->hasOne(PreferenceCampainChannelMm::className(), ['id' => 'preference_campain_channel_mm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
