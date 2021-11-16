<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\models\base;

use open20\amos\discussioni\AmosDiscussioni;

/**
 * Class DiscussioniRisposte
 * This is the base-model class for table "discussioni_risposte".
 *
 * @property integer $id
 * @property string $testo
 * @property integer $discussioni_topic_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\discussioni\models\DiscussioniCommenti[] $discussioniCommentis
 * @property \open20\amos\discussioni\models\DiscussioniTopic $discussioniTopic
 * @package open20\amos\discussioni\models\base
 * @deprecated from version 1.5.
 */
class DiscussioniRisposte extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discussioni_risposte';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testo'], 'string'],
            [['discussioni_topic_id'], 'required'],
            [['discussioni_topic_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosDiscussioni::t('amosdiscussioni', 'ID'),
            'testo' => AmosDiscussioni::t('amosdiscussioni', 'Risposta'),
            'discussioni_topic_id' => AmosDiscussioni::t('amosdiscussioni', 'Discussione'),
            'created_at' => AmosDiscussioni::t('amosdiscussioni', 'Creato il'),
            'updated_at' => AmosDiscussioni::t('amosdiscussioni', 'Aggiornato il'),
            'deleted_at' => AmosDiscussioni::t('amosdiscussioni', 'Cancellato il'),
            'created_by' => AmosDiscussioni::t('amosdiscussioni', 'Creato da'),
            'updated_by' => AmosDiscussioni::t('amosdiscussioni', 'Aggiornato da'),
            'deleted_by' => AmosDiscussioni::t('amosdiscussioni', 'Cancellato da'),
            'version' => AmosDiscussioni::t('amosdiscussioni', 'Versione numero'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscussioniCommentis()
    {
        return $this->hasMany(\open20\amos\discussioni\models\DiscussioniCommenti::className(), ['discussioni_risposte_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscussioniTopic()
    {
        return $this->hasOne(\open20\amos\discussioni\models\DiscussioniTopic::className(), ['id' => 'discussioni_topic_id']);
    }
}
