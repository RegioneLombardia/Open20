<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\models\base;

use open20\amos\cwh\AmosCwh;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "cwh_nodi".
 *
 * @property string $id
 * @property integer $cwh_config_id
 * @property integer $record_id
 * @property string $classname
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 * @property integer $visibility
 *
 * @property \open20\amos\cwh\models\CwhConfig $cwhConfig
 * @property \open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm[] $cwhPubblicazioniCwhNodiEditoriMms
 * @property \open20\amos\cwh\models\CwhPubblicazioni[] $cwhPubblicazionis
 * @property \open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm[] $cwhPubblicazioniCwhNodiValidatoriMms
 * @property \open20\amos\cwh\models\CwhPubblicazioni[] $cwhPubblicazionis0
 */
class CwhNodiView extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cwh_nodi_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cwh_config_id'], 'required'],
            [['cwh_config_id', 'record_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['id', 'classname'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosCwh::t('amoscwh', 'Id (CLASSNAME-RECORD_ID)'),
            'cwh_config_id' => AmosCwh::t('amoscwh', 'Cwh Config ID'),
            'text' => AmosCwh::t('amoscwh', 'Descrizione'),
            'record_id' => AmosCwh::t('amoscwh', 'Record ID'),
            'classname' => AmosCwh::t('amoscwh', 'Classname'),
            'created_at' => AmosCwh::t('amoscwh', 'Creato il'),
            'updated_at' => AmosCwh::t('amoscwh', 'Aggiornato il'),
            'deleted_at' => AmosCwh::t('amoscwh', 'Cancellato il'),
            'created_by' => AmosCwh::t('amoscwh', 'Creato da'),
            'updated_by' => AmosCwh::t('amoscwh', 'Aggiornato da'),
            'deleted_by' => AmosCwh::t('amoscwh', 'Cancellato da'),
            'version' => AmosCwh::t('amoscwh', 'Versione numero'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhConfig()
    {
        return $this->hasOne(\open20\amos\cwh\models\CwhConfig::className(), ['id' => 'cwh_config_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhPubblicazioniCwhNodiEditoriMms()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm::className(), ['cwh_nodi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhPubblicazionis()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhPubblicazioni::className(), ['id' => 'cwh_pubblicazioni_id'])->viaTable('cwh_pubblicazioni_cwh_nodi_editori_mm', ['cwh_nodi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhPubblicazioniCwhNodiValidatoriMms()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm::className(), ['cwh_nodi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhPubblicazionis0()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhPubblicazioni::className(), ['id' => 'cwh_pubblicazioni_id'])->viaTable('cwh_pubblicazioni_cwh_nodi_validatori_mm', ['cwh_nodi_id' => 'id']);
    }
}
