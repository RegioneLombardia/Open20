<?php

namespace open20\amos\events\models\base;

use open20\amos\core\user\User;
use Yii;

/**
 * This is the base-model class for table "event_invitation_sent".
 *
 * @property integer $id
 * @property integer $event_internal_list_id
 * @property integer $user_id
 * @property string $email
 * @property string $send_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \app\models\EventInternalList $eventInternalList
 * @property \app\models\User $user
 */
class  EventInvitationSent extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_invitation_sent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_internal_list_id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['template', 'send_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['email'], 'string', 'max' => 255],
            [['event_internal_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventInternalList::className(), 'targetAttribute' => ['event_internal_list_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'event_internal_list_id' => Yii::t('amosevents', 'Internal list'),
            'user_id' => Yii::t('amosevents', 'User'),
            'email' => Yii::t('amosevents', 'Email'),
            'send_at' => Yii::t('amosevents', 'Send at'),
            'created_at' => Yii::t('amosevents', 'Created at'),
            'updated_at' => Yii::t('amosevents', 'Updated at'),
            'deleted_at' => Yii::t('amosevents', 'Deleted at'),
            'created_by' => Yii::t('amosevents', 'Created by'),
            'updated_by' => Yii::t('amosevents', 'Updated at'),
            'deleted_by' => Yii::t('amosevents', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventInternalList()
    {
        return $this->hasOne(\open20\amos\events\models\EventInternalList::className(), ['id' => 'event_internal_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
