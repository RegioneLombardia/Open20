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
 * Class CwhTagOwnerInterestMm
 *
 * This is the base-model class for table "cwh_tag_owner_interest_mm".
 *
 * @property integer $id
 * @property string $interest_classname
 * @property string $classname
 * @property string $record_id
 * @property integer $tag_id
 * @property integer $root_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package open20\amos\cwh\models\base
 */
class CwhTagOwnerInterestMm extends \open20\amos\core\record\AmosRecordAudit
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cwh_tag_owner_interest_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'root_id', 'record_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['interest_classname', 'classname'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosCwh::t('amoscwh', 'ID'),
            'interest_classname' => AmosCwh::t('amoscwh', 'Contenuto di preferenza'),
            'classname' => AmosCwh::t('amoscwh', 'Proprietario'),
            'record_id' => AmosCwh::t('amoscwh', 'Proprietario id'),
            'tag_id' => AmosCwh::t('amoscwh', 'Tag'),
            'root_id' => AmosCwh::t('amoscwh', 'Albero'),
            'created_at' => AmosCwh::t('amoscwh', 'Creato il'),
            'updated_at' => AmosCwh::t('amoscwh', 'Aggiornato il'),
            'deleted_at' => AmosCwh::t('amoscwh', 'Cancellato il'),
            'created_by' => AmosCwh::t('amoscwh', 'Creato da'),
            'updated_by' => AmosCwh::t('amoscwh', 'Aggiornato da'),
            'deleted_by' => AmosCwh::t('amoscwh', 'Cancellato da'),
        ]);
    }
}
