<?php

namespace open20\amos\collaborations\models\base;

use Yii;

/**
 * This is the base-model class for table "collaboration_partner_type_and_size_mm".
 *
 * @property integer $id
 * @property integer $collaboration_proposals_id
 * @property integer $collaboration_partner_type_and_size_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 */
class  CollaborationPartnerTypeAndSizeMm extends \yii\db\ActiveRecord
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collaboration_partner_type_and_size_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collaboration_proposals_id', 'collaboration_partner_type_and_size_id'], 'required'],
            [['collaboration_proposals_id', 'collaboration_partner_type_and_size_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoscollaborations', 'ID'),
            'collaboration_proposals_id' => Yii::t('amoscollaborations', 'Collaboration Proposals ID'),
            'collaboration_partner_type_and_size_id' => Yii::t('amoscollaborations', 'Collaboration Partner Type and Size ID'),
            'created_by' => Yii::t('amoscollaborations', 'Created By'),
            'created_at' => Yii::t('amoscollaborations', 'Created At'),
            'updated_by' => Yii::t('amoscollaborations', 'Updated By'),
            'updated_at' => Yii::t('amoscollaborations', 'Updated At'),
            'deleted_by' => Yii::t('amoscollaborations', 'Deleted By'),
            'deleted_at' => Yii::t('amoscollaborations', 'Deleted At'),
        ];
    }
}
