<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\models\base
 * @category   CategoryName
 */

namespace amos\sitemanagement\models\base;

use amos\sitemanagement\Module;
use open20\amos\core\record\Record;
use yii\helpers\ArrayHelper;

/**
 * Class Metadata
 *
 * This is the base-model class for table "site_management_metadata".
 *
 * @property integer $id
 * @property string $key_value
 * @property string $content
 * @property integer $metadata_type_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\MetadataType $metadataType
 *
 * @package amos\sitemanagement\models\base
 */
class Metadata extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_metadata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['metadata_type_id', 'key_value', 'content'], 'required'],
            [['key_value'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['metadata_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['metadata_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => \amos\sitemanagement\models\MetadataType::className(), 'targetAttribute' => ['metadata_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amossitemanagement', 'ID'),
            'key_value' => Module::t('amossitemanagement', 'Key'),
            'content' => Module::t('amossitemanagement', 'Content'),
            'metadata_type_id' => Module::t('amossitemanagement', 'Metadata Type Id'),
            'metadataType' => Module::t('amossitemanagement', 'Metadata Type'),
            'created_at' => Module::t('amoscore', '#created_at'),
            'updated_at' => Module::t('amoscore', '#updated_at'),
            'deleted_at' => Module::t('amoscore', '#deleted_at'),
            'created_by' => Module::t('amoscore', '#created_by'),
            'updated_by' => Module::t('amoscore', '#updated_by'),
            'deleted_by' => Module::t('amoscore', '#deleted_by')
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetadataType()
    {
        return $this->hasOne(\amos\sitemanagement\models\MetadataType::className(), ['id' => 'metadata_type_id']);
    }
}
