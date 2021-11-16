<?php
/**
 * This model allows for storing of javascript logging entries linked to a specific audit entry
 */

namespace open20\amos\audit\models;

use open20\amos\audit\components\db\ActiveRecord;
use Yii;

/**
 * AuditJavascript
 *
 * @package open20\amos\audit\models
 * @property int    $id
 * @property int    $entry_id
 * @property string $created
 * @property string $type
 * @property string $message
 * @property string $origin
 * @property string $data
 *
 * @property AuditEntry    $entry
 */
class AuditJavascript extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_javascript}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntry()
    {
        return $this->hasOne(AuditEntry::className(), ['id' => 'entry_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('audit', 'ID'),
            'entry_id'  => Yii::t('audit', 'Entry ID'),
            'created'   => Yii::t('audit', 'Created'),
            'type'      => Yii::t('audit', 'Type'),
            'message'   => Yii::t('audit', 'Message'),
            'origin'    => Yii::t('audit', 'Origin'),
            'data'      => Yii::t('audit', 'Data'),
        ];
    }
}