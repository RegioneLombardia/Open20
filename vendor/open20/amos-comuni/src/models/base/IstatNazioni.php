<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\models\base;

use Yii;
use open20\amos\comuni\AmosComuni;

/**
 * This is the base-model class for table "istat_nazioni".
 *
 * @property integer $id
 * @property string $nome
 * @property string $nome_inglese
 * @property integer $area
 * @property integer $unione_europea
 * @property string $codice_catastale
 * @property string $iso2
 * @property string $iso3
 * @property integer $istat_continenti_id
 *
 * @property \open20\amos\comuni\models\IstatContinenti $istatContinenti
 * @property \open20\amos\comuni\models\IstatRegioni[] $istatRegionis
 */
class IstatNazioni extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'istat_nazioni';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nome', 'istat_continenti_id'], 'required'],
            [['id', 'area', 'unione_europea', 'istat_continenti_id', 'iso2', 'iso3'], 'integer'],
            [['nome', 'nome_inglese', 'codice_catastale'], 'string', 'max' => 255],
            [['istat_continenti_id'], 'exist', 'skipOnError' => true, 'targetClass' => IstatContinenti::className(), 'targetAttribute' => ['istat_continenti_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosComuni::t('amoscomuni', 'Codice Istat'),
            'nome' => AmosComuni::t('amoscomuni', 'Nazione'),
            'nome_inglese' => AmosComuni::t('amoscomuni', 'Nome inglese'),
            'area' => AmosComuni::t('amoscomuni', 'Area'),
            'unione_europea' => AmosComuni::t('amoscomuni', 'Appartenente all\'UE'),
            'codice_catastale' => AmosComuni::t('amoscomuni', 'Codice catastale'),
            'istat_continenti_id' => AmosComuni::t('amoscomuni', 'Continente'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIstatContinenti()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatContinenti::className(), ['id' => 'istat_continenti_id'])->inverseOf('istatNazionis');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIstatRegionis()
    {
        return $this->hasMany(\open20\amos\comuni\models\IstatRegioni::className(), ['istat_nazioni_id' => 'id'])->inverseOf('istatNazioni');
    }
}
