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
 * This is the base-model class for table "istat_unione_dei_comuni".
 *
 * @property integer $id
 * @property string $nome
 * @property string $sito
 * @property integer $istat_province_id
 *
 * @property \open20\amos\comuni\models\IstatComuni[] $istatComunis
 * @property \open20\amos\comuni\models\IstatProvince $istatProvince
 */
class IstatUnioneDeiComuni extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'istat_unione_dei_comuni';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nome', 'istat_province_id'], 'required'],
            [['id', 'istat_province_id'], 'integer'],
            [['nome', 'sito'], 'string', 'max' => 255],
            [['istat_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => IstatProvince::className(), 'targetAttribute' => ['istat_province_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosComuni::t('amoscomuni', 'Codice Gestione Associata'),
            'nome' => AmosComuni::t('amoscomuni', 'Unione dei comuni'),
            'sito' => AmosComuni::t('amoscomuni', 'Sito web'),
            'istat_province_id' => AmosComuni::t('amoscomuni', 'Provincia'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIstatComunis()
    {
        return $this->hasMany(\open20\amos\comuni\models\IstatComuni::className(), ['istat_unione_dei_comuni_id' => 'id'])->inverseOf('istatUnioneDeiComuni');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIstatProvince()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatProvince::className(), ['id' => 'istat_province_id'])->inverseOf('istatUnioneDeiComunis');
    }
}
