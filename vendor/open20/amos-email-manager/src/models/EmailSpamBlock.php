<?php

namespace open20\amos\emailmanager\models;

/**
 * Model class for table "email_spam_block".
 *
 * @property integer $id
 * @property integer $sender_user_id
 * @property string $sending_date
 * @property integer $counter
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class EmailSpamBlock extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_spam_block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_user_id', 'sending_date'], 'required'],
            [['sender_user_id', 'counter', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['sending_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_user_id' => 'Sender User ID',
            'sending_date' => 'Sending date',
            'counter' => 'Counter',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'deleted_by' => 'Deleted by',
        ];
    }
}
