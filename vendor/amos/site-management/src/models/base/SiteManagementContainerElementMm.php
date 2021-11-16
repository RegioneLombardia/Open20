<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_container_element_mm".
 *
 * @property integer $id
 * @property integer $container_id
 * @property integer $element_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManageContElemPubblication[] $siteManageContElemPubblications
 * @property \amos\sitemanagement\models\SiteManagementElement $element
 * @property \amos\sitemanagement\models\SiteManagementContainer $container
 */
class SiteManagementContainerElementMm extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_container_element_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['container_id', 'element_id'], 'required'],
            [['container_id', 'element_id', 'elem_order', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['element_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementElement::className(), 'targetAttribute' => ['element_id' => 'id']],
            [['container_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementContainer::className(), 'targetAttribute' => ['container_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'container_id' => Yii::t('amossitemanagement', 'Container'),
            'element_id' => Yii::t('amossitemanagement', 'Element'),
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
    public function getSiteManageContElemPubblication()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManageContElemPubblication::className(), ['container_elem_mm_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementElement::className(), ['id' => 'element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContainer()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementContainer::className(), ['id' => 'container_id']);
    }
}
