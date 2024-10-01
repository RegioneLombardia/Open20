<?php

namespace frontend\modules\userprofiling\models\base;

use Yii;

/**
 * This is the base-model class for table "privacy_history".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $timestamp
 * @property integer $isAccepted
 * @property integer $token
 * @property integer $notification_level
 */
class  PrivacyHistory extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'privacy_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'timestamp', 'isAccepted'], 'required'],
            [['id_user', 'timestamp', 'isAccepted', 'notification_level'], 'integer'],
            [['token'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_user' => Yii::t('app', 'Id User'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'isAccepted' => Yii::t('app', 'Is Accepted'),
        ];
    }
}
