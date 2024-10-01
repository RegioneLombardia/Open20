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
 * This is the base-model class for table "cwh_pubblicazioni_cwh_nodi_editori_mm".
 *
 * @property integer $id
 * @property integer $cwh_pubblicazioni_id
 * @property integer $cwh_config_id
 * @property integer $cwh_network_id
 * @property string $cwh_nodi_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\cwh\models\CwhNodi $cwhNodi
 * @property \open20\amos\cwh\models\CwhPubblicazioni $cwhPubblicazioni
 */
class CwhPubblicazioniCwhNodiEditoriMm extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cwh_pubblicazioni_cwh_nodi_editori_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cwh_pubblicazioni_id', 'cwh_nodi_id', 'cwh_config_id', 'cwh_network_id'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['cwh_nodi_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'cwh_pubblicazioni_id' => AmosCwh::t('amoscwh', 'Cwh Pubblicazioni ID'),
            'cwh_nodi_id' => AmosCwh::t('amoscwh', 'Cwh Nodi ID'),
            'cwh_config_id' => AmosCwh::t('amoscwh', 'Cwh Config ID'),
            'cwh_network_id' => AmosCwh::t('amoscwh', 'Cwh Network ID'),
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
    public function getCwhNodi()
    {
        return $this->hasOne(\open20\amos\cwh\models\CwhNodi::className(), ['id' => 'cwh_nodi_id', 'cwh_config_id' => 'cwh_config_id', 'record_id' => 'cwh_network_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhPubblicazioni()
    {
        return $this->hasOne(\open20\amos\cwh\models\CwhPubblicazioni::className(), ['id' => 'cwh_pubblicazioni_id']);
    }
}
