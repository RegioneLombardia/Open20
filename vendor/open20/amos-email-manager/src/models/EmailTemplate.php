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

use open20\amos\emailmanager\AmosEmail;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class EmailTemplate extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'subject', 'heading', 'message'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosEmail::t('amosemail', 'ID'),
            'name' => AmosEmail::t('amosemail', 'Name'),
            'subject' => AmosEmail::t('amosemail', 'Subject'),
            'heading' => AmosEmail::t('amosemail', 'Heading'),
            'message' => AmosEmail::t('amosemail', 'Message'),
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
            'class' => TimestampBehavior::className(),
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
