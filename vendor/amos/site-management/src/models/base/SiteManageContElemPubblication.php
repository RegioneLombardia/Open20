<?php

namespace amos\sitemanagement\models\base;

use open20\amos\core\user\User;
use Yii;

/**
 * This is the base-model class for table "site_manage_cont_elem_pubblication".
 *
 * @property integer $id
 * @property integer $container_id
 * @property integer $container_elem_id
 * @property integer $pubblication_type_id
 * @property string $start_date
 * @property string $end_date
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\sitemanagement\models\SiteManagementContainerElem $containerElem
 * @property \amos\sitemanagement\models\SiteManagementContainer $container
 * @property \amos\sitemanagement\models\SiteManageContElemPubblicationClass[] $siteManageContElemPubblicationClasses
 * @property \amos\sitemanagement\models\SiteManageContElemPubblicationUserMm[] $siteManageContElemPubblicationUserMms
 */
class SiteManageContElemPubblication extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_manage_cont_elem_pubblication';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['container_id', 'container_elem_mm_id', 'pubblication_type_id'], 'required'],
            [['container_id', 'container_elem_mm_id', 'pubblication_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['start_date', 'end_date', 'start_time', 'end_time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['container_elem_mm_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementContainerElementMm::className(), 'targetAttribute' => ['container_elem_mm_id' => 'id']],
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
            'container_elem_id' => Yii::t('amossitemanagement', 'Element'),
            'pubblication_type_id' => Yii::t('amossitemanagement', 'Pubblication type'),
            'start_date' => Yii::t('amossitemanagement', 'Start date'),
            'end_date' => Yii::t('amossitemanagement', 'End date'),
            'start_time' => Yii::t('amossitemanagement', 'Start time'),
            'end_time' => Yii::t('amossitemanagement', 'End time'),
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
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementContainerElementMm::className(), ['id' => 'container_elem_mm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContainer()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementContainer::className(), ['id' => 'container_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagementElement()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementElement::className(), ['id' => 'element_id'])->via('siteManagementContainerElementMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManagePubblicationType()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagePubblicationType::className(), ['id' => 'pubblication_type_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManageContElemPubblicationClasses()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManageContElemPubblicationClass::className(), ['cont_elem_pubblication_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteManageContElemPubblicationUserMms()
    {
        return $this->hasMany(\amos\sitemanagement\models\SiteManageContElemPubblicationUserMm::className(), ['cont_elem_pubblication_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->via('siteManageContElemPubblicationUserMms');
    }
}
