<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_template".
 *
 * @property integer $id
 * @property integer $name
 * @property string $description
 * @property string $view_path
 * @property string $content_model
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManagementContainerElem[] $siteManagementContainerElems
 */
class SiteManagementTemplate extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'content_model'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['view_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'name' => Yii::t('amossitemanagement', 'Template'),
            'description' => Yii::t('amossitemanagement', 'Description'),
            'view_path' => Yii::t('amossitemanagement', 'View path'),
            'content_model' => Yii::t('amossitemanagement', 'Model content'),
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
    public function getSiteManagementContainerElems()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementContainerElem::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementTemplateFields()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementTemplateFields::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementFieldsTypes()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementFieldsType::className(), ['id' => 'field_id'])->via('siteManagementTemplateFields');
    }
}
