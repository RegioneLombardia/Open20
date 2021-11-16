<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\models
 * @category   CategoryName
 */

namespace open20\amos\invitations\models;

use open20\amos\invitations\Module;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class InvitationUser
 * This is the model class for table "invitation_user".
 *
 * @property integer numberNotificationSended
 * @property integer numberNotificationSendedByMe
 *
 * @package open20\amos\invitations\models
 */
class InvitationUser extends \open20\amos\invitations\models\base\InvitationUser
{
    public static function getEditFields()
    {
        $labels = self::attributeLabels();
        
        return [
            [
                'slug' => 'email',
                'label' => $labels['email'],
                'type' => 'string'
            ],
        ];
    }
    
    /**
     * @inheridoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'numberNotificationSended' => Yii::t('amosinvitations', 'Number of invitation to ths email'),
            'numberNotificationSendedByMe' => Yii::t('amosinvitations', 'Number of invitation to ths email by me')
        ]);
    }
    
    /**
     * @inheridoc
     */
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }
    
    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }
    
    /**
     * @inheridoc
     */
    public function attributeHints()
    {
        return [
        ];
    }
    
    public function getNumberNotificationSended()
    {
        if (!empty($this->id)) {
            return self::numberNotificationsSended($this->id);
        } else {
            return 0;
        }
    }
    
    public function getNumberNotificationSendedByMe()
    {
        if (!empty($this->id)) {
            return self::numberNotificationsSendedByUserId($this->id, Yii::$app->user->id);
        } else {
            return 0;
        }
    }
    
    public static function getInvitationUserFromEmail($email)
    {
        return self::find()->andWhere(['email' => $email])->one();
    }
    
    /**
     * @param $invitationUserId
     * @return int
     */
    public static function numberNotificationsSended($invitationUserId = null)
    {
        $invitationUser = self::findOne($invitationUserId);
        if (!empty($invitationUser)) {
            /** @var Invitation $invitationModel */
            $invitationModel = Module::instance()->createModel('Invitation');
            return count($invitationModel::find()->andWhere(['invitation_user_id' => $invitationUserId, 'send' => true])->all());
        } else {
            return 0;
        }
    }
    
    /**
     * @param $invitationUserId
     * @param null $userId
     * @return int
     */
    public static function numberNotificationsSendedByUserId($invitationUserId = null, $userId = null)
    {
        $invitationUser = self::findOne($invitationUserId);
        if (!empty($invitationUser)) {
            /** @var Invitation $invitationModel */
            $invitationModel = Module::instance()->createModel('Invitation');
            return count($invitationModel::find()->andWhere([
                'invitation_user_id' => $invitationUserId,
                'send' => true,
                'created_by' => $userId,
            ])->all());
        } else {
            return 0;
        }
    }
}
