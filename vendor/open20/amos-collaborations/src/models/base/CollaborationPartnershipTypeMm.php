<?php

namespace open20\amos\collaborations\models\base;

use Yii;

/**
 * This is the base-model class for table "collaboration_partnership_type_mm".
 *
 * @property integer $id
 * @property integer $collaboration_proposals_id
 * @property integer $collaboration_partnership_type_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 *
 * @property \open20\amos\collaborations\models\CollaborationProposals $collaborationProposals
 */
class  CollaborationPartnershipTypeMm extends \yii\db\ActiveRecord
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collaboration_partnership_type_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collaboration_proposals_id', 'collaboration_partnership_type_id'], 'required'],
            [['collaboration_proposals_id', 'collaboration_partnership_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['collaboration_proposals_id'], 'exist', 'skipOnError' => true, 'targetClass' => CollaborationProposals::className(), 'targetAttribute' => ['collaboration_proposals_id' => 'id']],
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
            'collaboration_partnership_type_id' => Yii::t('amoscollaborations', 'Collaboration Partnership Type ID'),
            'created_by' => Yii::t('amoscollaborations', 'Created By'),
            'created_at' => Yii::t('amoscollaborations', 'Created At'),
            'updated_by' => Yii::t('amoscollaborations', 'Updated By'),
            'updated_at' => Yii::t('amoscollaborations', 'Updated At'),
            'deleted_by' => Yii::t('amoscollaborations', 'Deleted By'),
            'deleted_at' => Yii::t('amoscollaborations', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationProposals()
    {
        return $this->hasOne(\open20\amos\collaborations\models\CollaborationProposals::className(), ['id' => 'collaboration_proposals_id']);
    }
}
