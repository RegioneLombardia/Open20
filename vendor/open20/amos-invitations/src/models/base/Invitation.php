<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\models\base
 * @category   CategoryName
 */

namespace open20\amos\invitations\models\base;

use open20\amos\core\record\Record;
use open20\amos\invitations\Module;

/**
 * Class Invitation
 *
 * This is the base-model class for table "invitation".
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $fiscal_code
 * @property string $message
 * @property string $send_time
 * @property integer $send
 * @property integer $invite_accepted
 * @property string $register_action
 * @property string $module_name
 * @property string $context_model_id
 * @property string $category
 * @property integer $invitation_user_id
 * @property integer $token
 * @property integer $token_expire_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\invitations\models\InvitationUser $invitationUser
 *
 * @package open20\amos\invitations\models\base
 */
class Invitation extends Record
{
    /**
     * @var Module $invitationsModule
     */
    protected $invitationsModule;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invitation';
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->invitationsModule = Module::instance();
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $requiredArray = [
            'invitation_user_id',
            'name',
            'surname'
        ];
        if ($this->invitationsModule && $this->invitationsModule->enableInviteMessage) {
            $requiredArray[] = 'message';
        }

        if($this->invitationsModule && $this->invitationsModule->enableFiscalCode && $this->invitationsModule->fiscalCodeRequired){
            $requiredArray[] = 'fiscal_code';
        }

        return [
            [['message'], 'string'],
            [['token', 'token_expire_date', 'category','send_time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['invite_accepted', 'send', 'invitation_user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [$requiredArray, 'required'],
            [['fiscal_code'], 'string', 'max' => 16],
            [['name', 'surname', 'register_action', 'module_name', 'context_model_id'], 'string', 'max' => 255],
            [['invitation_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->invitationsModule->model('InvitationUser'), 'targetAttribute' => ['invitation_user_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('amosinvitations', 'ID'),
            'name' => Module::t('amosinvitations', 'Name'),
            'surname' => Module::t('amosinvitations', 'Surname'),
            'fiscal_code' => Module::t('amosinvitations', 'Fiscal Code'),
            'message' => Module::t('amosinvitations', 'Message'),
            'send_time' => Module::t('amosinvitations', 'Time to send invitation'),
            'send' => Module::t('amosinvitations', 'This notification was sent?'),
            'invitation_user_id' => Module::t('amosinvitations', 'Person to invitate'),
            'created_at' => Module::t('amosinvitations', 'Created at'),
            'updated_at' => Module::t('amosinvitations', 'Updated at'),
            'deleted_at' => Module::t('amosinvitations', 'Deleted at'),
            'created_by' => Module::t('amosinvitations', 'Created by'),
            'updated_by' => Module::t('amosinvitations', 'Updated by'),
            'deleted_by' => Module::t('amosinvitations', 'Deleted by'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvitationUser()
    {
        return $this->hasOne($this->invitationsModule->model('InvitationUser'), ['id' => 'invitation_user_id']);
    }
}
