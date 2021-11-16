<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\models;

use baibaratsky\yii\behaviors\model\SerializedAttributes;
use open20\amos\emailmanager\AmosEmail;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class EmailSpool extends ActiveRecord
{

    const STATUS_NEW = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_SENT = 2;
    const STATUS_ERROR = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_spool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority', 'model_id', 'sent', 'created_at', 'updated_at'], 'integer'],
            [['transport', 'template', 'status', 'model_name', 'to_address', 'from_address', 'subject', 'message',], 'string'],
            [['files', 'bcc', 'viewParams'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosEmail::t('amosemail', 'ID'),
            'priority' => AmosEmail::t('amosemail', 'Priority'),
            'model_id' => AmosEmail::t('amosemail', 'Model ID'),
            'sent' => AmosEmail::t('amosemail', 'Sent'),
            'template' => AmosEmail::t('amosemail', 'Template'),
            'status' => AmosEmail::t('amosemail', 'Status'),
            'transport' => AmosEmail::t('amosemail', 'Transport'),
            'subject' => AmosEmail::t('amosemail', 'Subject'),
            'model_name' => AmosEmail::t('amosemail', 'Model Name'),
            'message' => AmosEmail::t('amosemail', 'Message'),
            'to_address' => AmosEmail::t('amosemail', 'To address'),
            'from_address' => AmosEmail::t('amosemail', 'From address'),
            'created_at' => AmosEmail::t('amosemail', 'Created At'),
            'updated_at' => AmosEmail::t('amosemail', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            "Timestamp" => [
                'class' => TimestampBehavior::className(),
            ],
            'serializedAttributes' => [
                'class' => SerializedAttributes::className(),
                // Define the attributes you want to be serialized
                'attributes' => ['files', 'bcc', 'viewParams'],
                //'encode' => true,
            ]
        ]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '' . $this->id;
    }

}
