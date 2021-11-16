<?php

namespace open20\amos\cwh\models\base;

use open20\amos\cwh\AmosCwh;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "cwh_config".
 *
 * @property integer $id
 * @property string $classname
 * @property string $label
 * @property string $tablename
 * @property string $status_attribute
 * @property string $status_value
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 */
class CwhConfigContents extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cwh_config_contents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['classname', 'tablename', 'label', 'status_attribute', 'status_value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosCwh::t('amoscwh', 'Id'),
            'classname' => AmosCwh::t('amoscwh', 'Classname'),
            'tablename' => AmosCwh::t('amoscwh', 'Table Name'),
            'label' => AmosCwh::t('amoscwh', 'Label'),
            'status_attribute' => AmosCwh::t('amoscwh', 'Campo dello stato del workflow (es: status)'),
            'status_value' => AmosCwh::t('amoscwh', 'Quale stato del workflow rende modificabile ambito di pubblicazione?'),
            'created_at' => AmosCwh::t('amoscwh', 'Creato il'),
            'updated_at' => AmosCwh::t('amoscwh', 'Aggiornato il'),
            'deleted_at' => AmosCwh::t('amoscwh', 'Cancellato il'),
            'created_by' => AmosCwh::t('amoscwh', 'Creato da'),
            'updated_by' => AmosCwh::t('amoscwh', 'Aggiornato da'),
            'deleted_by' => AmosCwh::t('amoscwh', 'Cancellato da'),
            'version' => AmosCwh::t('amoscwh', 'Versione numero'),
        ]);
    }
}
