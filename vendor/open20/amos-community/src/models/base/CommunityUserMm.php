<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\models\base;

use open20\amos\community\AmosCommunity;
use open20\amos\core\record\Record;

/**
 * Class CommunityUserMm
 *
 * This is the base-model class for table "community_user_mm".
 *
 * @property integer $id
 * @property integer $community_id
 * @property integer $user_id
 * @property string $status
 * @property string $role
 * @property string $invited_at
 * @property string $invitation_accepted_at
 * @property string $invitation_partner_of
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\community\models\Community $community
 * @property \open20\amos\core\user\User $user
 * @property \open20\amos\core\user\User $partnerOf
 *
 * @package backend\modules\corsi\models\base
 */
class CommunityUserMm extends Record
{
    /**
     * constants for role and status of community members
     */
    const ROLE_COMMUNITY_MANAGER = 'COMMUNITY_MANAGER';
    const ROLE_PARTICIPANT = 'PARTICIPANT';
    const ROLE_READER = 'READER';
    const ROLE_AUTHOR = 'AUTHOR';
    const ROLE_EDITOR = 'EDITOR';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_WAITING_OK_COMMUNITY_MANAGER = 'REQUEST_SENT';
    const STATUS_WAITING_OK_USER = 'INVITED';
    const STATUS_INVITE_IN_PROGRESS = 'INVITING';
    const STATUS_MANAGER_TO_CONFIRM = 'MANAGER_TO_CONFIRM';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'community_user_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community_id', 'user_id', 'status', 'role'], 'required'],
            [['community_id', 'user_id', 'invitation_partner_of', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['status', 'role'], 'string'],
            [['created_at', 'updated_at', 'deleted_at', 'invited_at', 'invitation_accepted_at'], 'safe'],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\community\models\Community::className(), 'targetAttribute' => ['community_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['invitation_partner_of'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['invitation_partner_of' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosCommunity::t('amoscommunity', 'ID'),
            'community_id' => AmosCommunity::t('amoscommunity', 'Community ID'),
            'user_id' => AmosCommunity::t('amoscommunity', 'User ID'),
            'invitation_partner_of' => AmosCommunity::t('amoscommunity', '#invitation_partner_of'),
            'status' => AmosCommunity::t('amoscommunity', 'Status'),
            'role' => AmosCommunity::t('amoscommunity', 'Role'),
            'invited_at' => AmosCommunity::t('amoscommunity', '#invited_at'),
            'invitation_accepted_at' => AmosCommunity::t('amoscommunity', '#invitation_accepted_at'),
            'created_at' => AmosCommunity::t('amoscommunity', 'Created at'),
            'updated_at' => AmosCommunity::t('amoscommunity', 'Updated at'),
            'deleted_at' => AmosCommunity::t('amoscommunity', 'Deleted at'),
            'created_by' => AmosCommunity::t('amoscommunity', 'Created by'),
            'updated_by' => AmosCommunity::t('amoscommunity', 'Updated by'),
            'deleted_by' => AmosCommunity::t('amoscommunity', 'Deleted by'),
        ];
    }
    
    /**
     * Return all states
     * @return array States of the community user
     */
    public static function getUserStates()
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_REJECTED,
            self::STATUS_WAITING_OK_COMMUNITY_MANAGER,
            self::STATUS_WAITING_OK_USER,
            self::STATUS_INVITE_IN_PROGRESS,
            self::STATUS_MANAGER_TO_CONFIRM
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        return $this->hasOne(\open20\amos\community\models\Community::className(), ['id' => 'community_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerOf()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'invitation_partner_of']);
    }
}
