<?php

/**
 * Lombardia Informatica S.p.A.
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
 * Class InvitationUser
 *
 * This is the base-model class for table "invitation_user".
 *
 * @property integer $id
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\invitations\models\Invitation[] $invitations
 *
 * @package open20\amos\invitations\models\base
 */
class InvitationUser extends Record
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
        return 'invitation_user';
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
        return [
            [['email'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('amosinvitations', 'ID'),
            'email' => Module::t('amosinvitations', 'Email'),
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
    public function getInvitations()
    {
        return $this->hasMany(\open20\amos\invitations\models\Invitation::className(), ['invitation_user_id' => 'id']);
    }
}
