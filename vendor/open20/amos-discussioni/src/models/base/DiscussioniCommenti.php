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
 * Class DiscussioniCommenti
 * This is the base-model class for table "discussioni_commenti".
 *
 * @property integer $id
 * @property string $testo
 * @property integer $discussioni_risposte_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\discussioni\models\DiscussioniRisposte $discussioniRisposte
 * @package open20\amos\discussioni\models\base
 * @deprecated from version 1.5.
 */
class DiscussioniCommenti extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discussioni_commenti';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testo'], 'string'],
            [['discussioni_risposte_id'], 'required'],
            [['discussioni_risposte_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
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
            'testo' => AmosDiscussioni::t('amosdiscussioni', 'Commento'),
            'discussioni_risposte_id' => AmosDiscussioni::t('amosdiscussioni', 'Discussioni Risposte ID'),
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
    public function getDiscussioniRisposte()
    {
        return $this->hasOne(DiscussioniRisposte::className(), ['id' => 'discussioni_risposte_id']);
    }
}
