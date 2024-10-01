<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_section".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManagementSlider[] $siteManagementSliders
 */
class SiteManagementSection extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'name' => Yii::t('amossitemanagement', 'Section'),
            'description' => Yii::t('amossitemanagement', 'Description'),
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
    public function getSiteManagementSlider()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSlider::className(), ['section_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementContainer()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementContainer::className(), ['section_id' => 'id']);
    }
}
