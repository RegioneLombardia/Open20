<?php
namespace open20\amos\mobile\bridge\modules\v1\models;

use open20\amos\mobile\bridge\Module;
use Yii;

class ChatMessages extends \open20\amos\core\record\Record
{
    /**
     * Override connection to prevent charset whithout emoji support
     * @return \yii\db\Connection
     */
    public static function getDb() {
        $db = parent::getDb();

        $connection = new \yii\db\Connection([
            'dsn' => $db->dsn,
            'username' => $db->username,
            'password' => $db->password,
            'charset' => 'utf8mb4',
            //'collation' => 'utf32_unicode_ci'
        ]);

        return $connection;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'amoschat_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'receiver_id', 'text'], 'required'],
            [['text'], 'string'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => Module::t('amosrapid','Nome'),
            'receiver_id' => Module::t('amosrapid','Description'),
            'text' => Module::t('amosrapid','Slug'),
            'created_at' => Module::t('amosrapid','Creato il'),
            'updated_at' => Module::t('amosrapid','Aggiornato il'),
            'deleted_at' => Module::t('amosrapid','Cancellato il'),
            'created_by' => Module::t('amosrapid','Creato da'),
            'updated_by' => Module::t('amosrapid','Aggiornato da'),
            'deleted_by' => Module::t('amosrapid','Cancellato da'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }
}
