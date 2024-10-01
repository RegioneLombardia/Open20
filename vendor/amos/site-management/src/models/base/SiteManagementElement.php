<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_element".
 *
 * @property integer $id
 * @property integer $template_id
 * @property string $title
 * @property string $description
 * @property string $elem_order
 * @property integer content_model_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManagementContainerElementMm[] $siteManagementContainerElementMms
 * @property \amos\sitemanagement\models\SiteManagementTemplate $template
 */
class SiteManagementElement extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_element';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'title'], 'required'],
            [['content_model_id', 'template_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'elem_order'], 'string', 'max' => 255],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementTemplate::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'template_id' => Yii::t('amossitemanagement', 'Template'),
            'title' => Yii::t('amossitemanagement', 'Title element'),
            'description' => Yii::t('amossitemanagement', 'Description element'),
            'elem_order' => Yii::t('amossitemanagement', 'Order'),
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
    public function getSiteManagementContainerElementMms()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementContainerElementMm::className(), ['element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementTemplate()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementTemplate::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementContainerElemeFieldsVals()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementContainerElemFieldsVal::className(), ['container_elem_id' => 'id']);
    }
}
