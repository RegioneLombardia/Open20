<?php

namespace amos\sitemanagement\models\base;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "site_management_container".
 *
 * @property integer $id
 * @property integer $section_id
 * @property string $page_name
 * @property integer $is_masonry
 * @property integer $num_columns
 * @property integer $element_limit
 * @property integer $element_random
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManageContElemPubblication[] $siteManageContElemPubblications
 * @property \amos\sitemanagement\models\SiteManagementSection $siteManagementSection
 * @property \amos\sitemanagement\models\SiteManagementContainerElem[] $siteManagementContainerElems
 */
class SiteManagementContainer extends \open20\amos\core\record\Record
{

    const SCENARIO_FIXED_CONTAINER = 'fixed_container';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_container';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id',  'page_name', 'title'], 'required'],
            [['fixed_template_id'], 'required', 'on' => self::SCENARIO_FIXED_CONTAINER],
            [['section_id', 'num_columns', 'is_masonry', 'element_limit', 'element_random', 'created_by', 'updated_by', 'deleted_by','fixed_template_id'], 'integer'],
            [['description','page_name','permission'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => \amos\sitemanagement\models\SiteManagementSection::className(), 'targetAttribute' => ['section_id' => 'id']],
        ];
    }

    public function scenarios()
    {
        $parentScenarios = parent::scenarios();
        $scenarios = ArrayHelper::merge(
            $parentScenarios,
            [
                self::SCENARIO_FIXED_CONTAINER => $parentScenarios[self::SCENARIO_DEFAULT]
            ]
        );
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'permission' => Yii::t('amossitemanagement', 'Permission'),
            'page_name' => Yii::t('amossitemanagement', 'Page name'),
            'section_id' => Yii::t('amossitemanagement', 'Section'),
            'is_masonry' => Yii::t('amossitemanagement', 'Is Masonry'),
            'title' => Yii::t('amossitemanagement', 'Title'),
            'description' => Yii::t('amossitemanagement', 'Description'),
            'element_limit' => Yii::t('amossitemanagement', 'Max elements'),
            'element_random' => Yii::t('amossitemanagement', 'Scelta randomica degli elementi'),
            'num_columns' => Yii::t('amossitemanagement', 'Number of columns'),
            'fixed_template_id' => Yii::t('amossitemanagement', 'Template'),
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
    public function getSiteManageContElemPubblications()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManageContElemPubblication::className(), ['container_elem_mm_id' => 'id'])->via('siteManagementContainerElementMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementSection()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSection::className(), ['id' => 'section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementContainerElementMms()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementContainerElementMm::className(), ['container_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementElements()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManagementElement::className(), ['id' => 'element_id'])->via('siteManagementContainerElementMms');
    }
}
