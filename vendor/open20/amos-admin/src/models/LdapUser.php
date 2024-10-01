<?php

namespace open20\amos\admin\models;

use open20\amos\core\user\User;
use yii\helpers\ArrayHelper;

/**
 * @var $id int
 * @var $username string
 * @var $email string
 * @var $first_name string
 * @var $last_name string
 * @var $status string
 * @var $identity_id string
 * @var $user_id int
 * @var $raw_data array
 * @var $created_at string
 * @var $updated_at string
 * @var $deleted_at string
 * @var $created_by int
 * @var $updated_by int
 * @var $deleted_by int
 *
 * @var User $user
 */
class LdapUser extends \open20\amos\core\record\Record
{
    public static function tableName()
    {
        return '{{%ldap_user}}';
    }

    public function rules()
    {
        return [
            [
                [
                    'id',
                    'user_id',
                    'created_by',
                    'updated_by',
                    'deleted_by'
                ],
                'integer'
            ],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [
                [
                    'username',
                    'email',
                    'first_name',
                    'last_name',
                    'status',
                    'identity_id',
                    'raw_data'
                ],
                'string'
            ],
            [['username', 'identity_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'status' => 'Status',
            'raw_data' => 'Raw Data',
            'identity_id' => 'Identity ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_id']);
    }

    /**
     * @param int|string $uid
     */
    public static function findIdentity($uid)
    {
        $user = \Yii::$app->ldapAuth->searchUid($uid);

        if (!$user) {
            return null;
        }

        $data = ArrayHelper::merge(\Yii::$app->ldapAuth->mapUserData($user), [
            'raw_data' => $user
        ]);

        return new static($data);
    }
}