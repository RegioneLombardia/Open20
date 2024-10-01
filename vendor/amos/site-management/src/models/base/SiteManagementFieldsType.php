<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_fields_type".
 *
 * @property integer $id
 * @property integer $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManagementContainerElemFieldsVal[] $siteManagementContainerElemFieldsVals
 * @property \amos\sitemanagement\models\SiteManagementTemplateFields[] $siteManagementTemplateFields
 */
class SiteManagementFieldsType extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_fields_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'type', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'name' => Yii::t('amossitemanagement', 'Name'),
            'created_at' => Yii::t('amossitemanagement', 'Created at'),
            'updated_at' => Yii::t('amossitemanagement', 'Updated at'),
            'deleted_at' => Yii::t('amossitemanagement', 'Deleted at'),
            'created_by' => Yii::t('amossitemanagement', 'Created by'),
            'updated_by' => Yii::t('amossitemanagement', 'Updated at'),
            'deleted_by' => Yii::t('amossitemanagement', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementContainerElemFieldsVals()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementContainerElemFieldsVal::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementTemplateFields()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementTemplateFields::className(), ['field_id' => 'id']);
    }
}
