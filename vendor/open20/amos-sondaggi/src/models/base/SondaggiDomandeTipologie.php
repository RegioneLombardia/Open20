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
 * This is the base-model class for table "sondaggi_domande_tipologie".
 *
 * @property integer $id
 * @property string $tipologia
 * @property string $descrizione
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\sondaggi\models\SondaggiDomande[] $sondaggiDomandes
 */
class SondaggiDomandeTipologie extends \open20\amos\core\record\Record {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'sondaggi_domande_tipologie';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tipologia'], 'required'],
            [['descrizione'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['attivo', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['tipologia'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => AmosSondaggi::t('amossondaggi', 'ID'),
            'tipologia' => AmosSondaggi::t('amossondaggi', 'Tipologia'),
            'descrizione' => AmosSondaggi::t('amossondaggi', 'Descrizione'),
            'attivo' => AmosSondaggi::t('amossondaggi', 'Attivo'),
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
    public function getSondaggiDomandes() {
        return $this->hasMany(\open20\amos\sondaggi\models\SondaggiDomande::className(), ['sondaggi_domande_tipologie_id' => 'id'])
            ->andWhere([\open20\amos\sondaggi\models\SondaggiDomande::tableName().'.deleted_at' => null]);
    }

}
