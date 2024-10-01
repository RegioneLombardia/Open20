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
 * Class MetadataType
 *
 * This is the base-model class for table "site_management_metadata_type".
 *
 * @property integer $id
 * @property string $type
 *
 * @property \amos\sitemanagement\models\Metadata[] $metadatas
 *
 * @package amos\sitemanagement\models\base
 */
class MetadataType extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_metadata_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amossitemanagement', 'ID'),
            'type' => Module::t('amossitemanagement', 'Type')
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetadatas()
    {
        return $this->hasMany(\amos\sitemanagement\models\Metadata::className(), ['metadata_type_id' => 'id']);
    }
}
