<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\models\base
 * @category   CategoryName
 */

namespace open20\amos\cwh\models\base;

use open20\amos\cwh\AmosCwh;
use yii\helpers\ArrayHelper;

/**
 * Class CwhRegolePubblicazione
 *
 * This is the base-model class for table "cwh_regole_pubblicazione".
 *
 * @property integer $id
 * @property string $nome
 * @property string $codice
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\cwh\models\CwhPubblicazioni[] $cwhPubblicazionis
 *
 * @package open20\amos\cwh\models\base
 */
class CwhRegolePubblicazione extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cwh_regole_pubblicazione';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['nome', 'codice'], 'string', 'max' => 255],
            [['codice'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosCwh::t('amoscwh', 'Id'),
            'nome' => AmosCwh::t('amoscwh', 'Codice'),
            'codice' => AmosCwh::t('amoscwh', 'Codice'),
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
    public function getCwhPubblicazionis()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhPubblicazioni::className(), ['cwh_regole_pubblicazione_id' => 'id']);
    }
}
