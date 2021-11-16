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
use open20\amos\core\record\ContentModel;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\notificationmanager\record\NotifyRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "discussioni_topic".
 *
 * @property integer $id
 * @property string $slug
 * @property string $titolo
 * @property string $testo
 * @property integer $hints
 * @property string $lat
 * @property string $lng
 * @property integer $in_evidenza
 * @property integer $primo_piano
 * @property string $status
 * @property integer $image_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 * @property integer $close_comment_thread
 *
 * @property \open20\amos\discussioni\models\DiscussioniRisposte[] $discussioniRisposte
 * @property \open20\amos\comments\models\Comment[] $discussionComments
 */
abstract class DiscussioniTopic extends ContentModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discussioni_topic';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testo'], 'string'],
            [['titolo', 'status'], 'required'],
            [['primo_piano','in_evidenza', 'hints', 'created_by', 'updated_by', 'deleted_by', 'version', 'image_id'], 'integer'],
            [['slug', 'close_comment_thread', 'created_at', 'updated_at', 'deleted_at', 'status'], 'safe'],
            [['titolo'], 'string', 'max' => 255]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosDiscussioni::t('amosdiscussioni', 'ID'),
            'titolo' => AmosDiscussioni::t('amosdiscussioni', '#title_field'),
            'testo' => AmosDiscussioni::t('amosdiscussioni', '#description_field'),
            'hints' => AmosDiscussioni::t('amosdiscussioni', 'Visualizzazioni'),
            'lat' => AmosDiscussioni::t('amosdiscussioni', 'Latitudine'),
            'lng' => AmosDiscussioni::t('amosdiscussioni', 'Longitudine'),
            'in_evidenza' => AmosDiscussioni::t('amosdiscussioni', 'In evidenza'),
            'primo_piano' => AmosDiscussioni::t('amosdiscussioni', 'Pubblica sul sito'),
            'status' => AmosDiscussioni::t('amosdiscussioni', 'Stato'),
            'image_id' => AmosDiscussioni::t('amosdiscussioni', 'Immagine'),
            'created_at' => AmosDiscussioni::t('amosdiscussioni', 'Creato il'),
            'updated_at' => AmosDiscussioni::t('amosdiscussioni', 'Aggiornato il'),
            'deleted_at' => AmosDiscussioni::t('amosdiscussioni', 'Cancellato il'),
            'created_by' => AmosDiscussioni::t('amosdiscussioni', 'Creato da'),
            'updated_by' => AmosDiscussioni::t('amosdiscussioni', 'Aggiornato da'),
            'deleted_by' => AmosDiscussioni::t('amosdiscussioni', 'Cancellato da'),
            'version' => AmosDiscussioni::t('amosdiscussioni', 'Versione numero'),
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     * @deprecated from version 1.5. Use [[DiscussioniTopic::getDiscussionComments()]] instead of this.
     */
    public function getDiscussioniRisposte()
    {
        return $this->hasMany(\open20\amos\discussioni\models\DiscussioniRisposte::className(), ['discussioni_topic_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscussionComments()
    {
        return $this->hasMany(\open20\amos\comments\models\Comment::className(), ['context_id' => 'id'])
            ->andWhere(['context' => \open20\amos\discussioni\models\DiscussioniTopic::className()]);
    }
}
