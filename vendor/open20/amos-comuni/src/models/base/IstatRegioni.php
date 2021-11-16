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
 * This is the base-model class for table "istat_regioni".
 *
 * @property integer $id
 * @property string $nome
 * @property integer $istat_nazioni_id
 *
 * @property \open20\amos\comuni\models\IstatComuni[] $istatComunis
 * @property \open20\amos\comuni\models\IstatProvince[] $istatProvinces
 * @property \open20\amos\comuni\models\IstatNazioni $istatNazioni
 */
class IstatRegioni extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'istat_regioni';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nome'], 'required'],
            [['id', 'istat_nazioni_id'], 'integer'],
            [['nome'], 'string', 'max' => 255],
            [['istat_nazioni_id'], 'exist', 'skipOnError' => true, 'targetClass' => IstatNazioni::className(), 'targetAttribute' => ['istat_nazioni_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosComuni::t('amoscomuni', 'Codice Istat'),
            'nome' => AmosComuni::t('amoscomuni', 'Regione'),
            'istat_nazioni_id' => AmosComuni::t('amoscomuni', 'Istat Nazioni ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIstatComunis()
    {
        return $this->hasMany(\open20\amos\comuni\models\IstatComuni::className(), ['istat_regioni_id' => 'id'])->inverseOf('istatRegioni');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIstatProvinces()
    {
        return $this->hasMany(\open20\amos\comuni\models\IstatProvince::className(), ['istat_regioni_id' => 'id'])->inverseOf('istatRegioni');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIstatNazioni()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatNazioni::className(), ['id' => 'istat_nazioni_id'])->inverseOf('istatRegionis');
    }
}
