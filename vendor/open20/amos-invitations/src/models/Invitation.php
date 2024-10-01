<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\models
 * @category   CategoryName
 */

namespace open20\amos\invitations\models;

use open20\amos\invitations\Module;
use yii\db\ActiveQuery;

/**
 * Class Invitation
 * This is the model class for table "invitation".
 *
 * @property boolean alreadySended
 * @property string nameSurname
 *
 * @package open20\amos\invitations\models
 */
class Invitation extends \open20\amos\invitations\models\base\Invitation
{
    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'surname',
                'label' => $labels['surname'],
                'type' => 'string'
            ],
            [
                'slug' => 'message',
                'label' => $labels['message'],
                'type' => 'text'
            ],
            [
                'slug' => 'send_time',
                'label' => $labels['send_time'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'send',
                'label' => $labels['send'],
                'type' => 'smallint'
            ],
            [
                'slug' => 'invitation_user_id',
                'label' => $labels['invitation_user_id'],
                'type' => 'integer'
            ],
        ];
    }

    public function representingColumn()
    {
        return $this->getNameSurname();
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

    public function attributeHints()
    {
        return [
        ];
    }

    public function getNameSurname()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getAlreadySended()
    {
        if (!empty($this->invitationUser)) {
            return self::alreadySended($this->invitationUser->email);
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'afterInsertGenerateToken'], $this);

    }

    public function afterInsertGenerateToken($event){
        /** @var  $invitation Invitation */
        $invitation = $event->data;
        $invitation->generateToken();
    }

    /**
     * @param $email
     * @return bool
     */
    public static function alreadySended($email)
    {
        /** @var InvitationUser $invitationUserModel */
        $invitationUserModel = Module::instance()->createModel('InvitationUser');

        /** @var ActiveQuery $query */
        $query = $invitationUserModel::find();
        $invitationUser = $query->joinWith('invitations')->andWhere(['invitation_user.email' => $email, 'invitation.send' => 1])->one();
        if (empty($invitationUser)) {
            return false;
        } else {
            return true;
        }
    }


    /**
     *
     */
    public function generateToken()
    {
        if ($this->invitationsModule->enableToken) {
            $tokenExpireDays = $this->invitationsModule->tokenExpireDays;
            //Generate a random string.
            $token = openssl_random_pseudo_bytes(16);

            //Convert the binary data into hexadecimal representation.
            $token = bin2hex($token);
            $this->token = $token;

            if (!empty($tokenExpireDays)) {
                $start_date = date('Y-m-d H:i:s');
                $expires = strtotime("+$tokenExpireDays days", strtotime($start_date));
                $this->token_expire_date = date('Y-m-d H:i:s', $expires);
            }
            $this->save(false);
        }
    }

    /**
     * @return bool
     */
    public function isTokenValid()
    {
        $now = new \DateTime();
        if (empty($this->token_expire_date)) {
            return true;
        }

        $expireDate = new \DateTime($this->token_expire_date);
        if ($now < $expireDate && !$this->invite_accepted) {
            return true;
        }
        return false;
    }
}
