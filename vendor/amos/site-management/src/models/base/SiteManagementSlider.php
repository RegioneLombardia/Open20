<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_slider".
 *
 * @property integer $id
 * @property integer $section_id
 * @property string $title
 * @property string $description
 * @property string $view_path
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property SiteManagementSection $section
 */
class SiteManagementSlider extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_slider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['section_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description','view_path','permission'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementSection::className(), 'targetAttribute' => ['section_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'section_id' => Yii::t('amossitemanagement', 'Section'),
            'title' => Yii::t('amossitemanagement', 'Title'),
            'description' => Yii::t('amossitemanagement', 'Description'),
            'permission' => Yii::t('amossitemanagement', 'Permission'),
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
    public function getSection()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSection::className(), ['id' => 'section_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSliderElems()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementSliderElem::className(), ['slider_id' => 'id']);
    }
}
