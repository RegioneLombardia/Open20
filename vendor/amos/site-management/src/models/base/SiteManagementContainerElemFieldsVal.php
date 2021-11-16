<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_container_elem_fields_val".
 *
 * @property integer $id
 * @property integer $container_elem_id
 * @property integer $field_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManagementContainerElem $containerElem
 * @property \amos\sitemanagement\models\SiteManagementFieldsType $field
 */
class SiteManagementContainerElemFieldsVal extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_container_elem_fields_val';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['container_elem_id', 'field_id'], 'required'],
            [['container_elem_id', 'field_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['value'], 'string'],
            [['container_elem_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementElement::className(), 'targetAttribute' => ['container_elem_id' => 'id']],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementFieldsType::className(), 'targetAttribute' => ['field_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'container_elem_id' => Yii::t('amossitemanagement', 'Element'),
            'field_id' => Yii::t('amossitemanagement', 'Field'),
            'value' => Yii::t('amossitemanagement', 'Value'),
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
    public function getSiteManagementElement()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementElement::className(), ['id' => 'container_elem_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementFieldsType()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementFieldsType::className(), ['id' => 'field_id']);
    }
}
