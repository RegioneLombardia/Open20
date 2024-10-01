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
 * This is the base-model class for table "cwh_pubblicazioni".
 *
 * @property integer $id
 * @property integer $cwh_config_id
 * @property integer $cwh_config_contents_id
 * @property integer $content_id
 * @property integer $cwh_regole_pubblicazione_id
 * @property integer $ignore_notify_editorial_staff
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\cwh\models\CwhConfig $cwhConfig
 * @property \open20\amos\cwh\models\CwhRegolePubblicazione $cwhRegolePubblicazione
 * @property \open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm[] $cwhPubblicazioniCwhNodiEditoriMms
 * @property \open20\amos\cwh\models\CwhNodi[] $destinatari
 * @property \open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm[] $cwhPubblicazioniCwhNodiValidatoriMms
 * @property \open20\amos\cwh\models\CwhNodi[] $validatori
 */
class CwhPubblicazioni extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cwh_pubblicazioni';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cwh_config_contents_id', 'content_id', 'cwh_regole_pubblicazione_id'], 'required'],
            [['ignore_notify_editorial_staff', 'cwh_config_contents_id', 'content_id', 'cwh_regole_pubblicazione_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosCwh::t('amoscwh', 'ID'),
            'cwh_config_id' => AmosCwh::t('amoscwh', 'Cwh Config ID'),
            'cwh_config_contents_id' => AmosCwh::t('amoscwh', 'Cwh Config Contents ID'),
            'content_id' => AmosCwh::t('amoscwh', 'Content ID'),
            'cwh_regole_pubblicazione_id' => AmosCwh::t('amoscwh', 'Cwh Regole Pubblicazione ID'),
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
    public function getCwhConfigContents()
    {
        return $this->hasOne(\open20\amos\cwh\models\CwhConfigContents::className(), ['id' => 'cwh_config_contents_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhRegolePubblicazione()
    {
        return $this->hasOne(\open20\amos\cwh\models\CwhRegolePubblicazione::className(), ['id' => 'cwh_regole_pubblicazione_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhPubblicazioniCwhNodiEditoriMms()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm::className(), ['cwh_pubblicazioni_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestinatari()
    {
        //using ->via(relationName), it was viaTable(cwh_pubblicazioni_nodi_editori) but it did ignore soft delete
        return $this->hasMany(\open20\amos\cwh\models\CwhNodi::className(), ['id' => 'cwh_nodi_id', 'cwh_config_id' => 'cwh_config_id', 'record_id' => 'cwh_network_id'])->via('cwhPubblicazioniCwhNodiEditoriMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhPubblicazioniCwhNodiValidatoriMms()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm::className(), ['cwh_pubblicazioni_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValidatori()
    {
        //using ->via(relationName), it was viaTable(cwh_pubblicazioni_nodi_validatori) but it did ignore soft delete
        return $this->hasMany(\open20\amos\cwh\models\CwhNodi::className(), ['id' => 'cwh_nodi_id', 'cwh_config_id' => 'cwh_config_id', 'record_id' => 'cwh_network_id'])->via('cwhPubblicazioniCwhNodiValidatoriMms');
    }
}
