<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models\base
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models\base;

use Yii;
use open20\amos\sondaggi\AmosSondaggi;

/**
 * This is the base-model class for table "sondaggi_risposte_predefinite".
 *
 * @property integer $id
 * @property string $risposta
 * @property integer $sondaggi_domande_id
 * @property integer $ordinamento
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\sondaggi\models\SondaggiDomandeCondizionate[] $sondaggiDomandeCondizionates
 * @property \open20\amos\sondaggi\models\SondaggiDomande $sondaggiDomande
 */
class SondaggiRispostePredefinite extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sondaggi_risposte_predefinite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['risposta'], 'safe'],
            [['sondaggi_domande_id'], 'required'],
            [['code'], 'string'],
            [['code'], 'unique', 'targetAttribute' => ['code', 'sondaggi_domande_id'], 'message' => AmosSondaggi::t('amossondaggi', '#duplicate_code')],
            [['ordine'], 'default', 'value'=> 'fine'],
            [['sondaggi_domande_id', 'ordinamento', 'created_by', 'updated_by', 'deleted_by', 'version', 'modello_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosSondaggi::t('amossondaggi', 'ID'),
            'risposta' => AmosSondaggi::t('amossondaggi', 'Risposta predefinita'),
            'sondaggi_domande_id' => AmosSondaggi::t('amossondaggi', 'Domanda'),
            'modello_id' => AmosSondaggi::t('amossondaggi', 'Id modello'),
            'ordinamento' => AmosSondaggi::t('amossondaggi', 'Ordinamento'),
            'code' => AmosSondaggi::t('amossondaggi', 'Codice'),
            'created_at' => AmosSondaggi::t('amossondaggi', 'Creato il'),
            'updated_at' => AmosSondaggi::t('amossondaggi', 'Aggiornato il'),
            'deleted_at' => AmosSondaggi::t('amossondaggi', 'Cancellato il'),
            'created_by' => AmosSondaggi::t('amossondaggi', 'Creato da'),
            'updated_by' => AmosSondaggi::t('amossondaggi', 'Aggiornato da'),
            'deleted_by' => AmosSondaggi::t('amossondaggi', 'Cancellato da'),
            'version' => AmosSondaggi::t('amossondaggi', 'Versione'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSondaggiDomandeCondizionates()
    {
        return $this->hasMany(\open20\amos\sondaggi\models\SondaggiDomandeCondizionate::className(), ['sondaggi_risposte_predefinite_id' => 'id'])
            ->andWhere([\open20\amos\sondaggi\models\SondaggiDomandeCondizionate::tableName().'.deleted_at' => null]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSondaggiDomande()
    {
        return $this->hasOne(\open20\amos\sondaggi\models\SondaggiDomande::className(), ['id' => 'sondaggi_domande_id'])
            ->andWhere([\open20\amos\sondaggi\models\SondaggiDomande::tableName().'.deleted_at' => null]);
    }
}
