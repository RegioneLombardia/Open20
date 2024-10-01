<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\helpers\Json;
use luya\admin\aws\ChangePasswordInterface;
use luya\admin\Module;
use luya\admin\traits\SoftDeleteTrait;
use luya\admin\ngrest\base\NgRestModel;
use luya\admin\aws\ChangePasswordActiveWindow;
use luya\admin\aws\UserHistorySummaryActiveWindow;
use luya\admin\base\RestActiveController;
use yii\base\InvalidArgumentException;
use luya\validators\StrengthValidator;
use luya\admin\aws\ApiRequestInsightActiveWindow;
use luya\admin\events\UserAccessTokenLoginEvent;
use luya\helpers\Html;
use luya\helpers\Url;
use WhichBrowser\Parser;
use luya\admin\models\UserDevice;
use luya\admin\models\UserLogin;

/**
 * User Model represents all Administration Users.
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property integer $title
 * @property string $email
 * @property string $password
 * @property string $password_salt
 * @property string $auth_token
 * @property integer $is_deleted
 * @property string $secure_token
 * @property integer $secure_token_timestamp
 * @property integer $force_reload
 * @property string $settings
 * @property UserSetting $setting Setting object to store data.
 * @property integer $is_api_user
 * @property integer $api_rate_limit
 * @property string $api_allowed_ips
 * @property integer $api_last_activity
 * @property string $email_verification_token
 * @property integer $email_verification_token_timestamp
 * @property integer $login_attempt
 * @property integer $login_attempt_lock_expiration
 * @property boolean $is_request_logger_enabled
 * @property int|null $login_2fa_enabled {@since 3.0.0}
 * @property string|null $login_2fa_secret {@since 3.0.0}
 * @property string|null $login_2fa_backup_key {@since 3.0.0}
 * @property string|null $password_verification_token {@since 3.0.0}
 * @property int|null $password_verification_token_timestamp {@since 3.0.0}
 * @property Group[] $groups Expand groups for this user
 *
 * @since 1.0.0
 */
class LuyaUser extends \luya\admin\models\User {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }

    /**
     * Generate, store and return the secure Login token.
     *
     * @return string
     */
    public function getAndStoreToken() {
        $token = $this->generateToken(6);

        $this->setAttribute('secure_token', sha1($token));
        $this->setAttribute('secure_token_timestamp', time());
        $this->update(false);

        return $token;
    }

    /**
     * Return sensitive fields from api exposure.
     *
     * {@inheritDoc}
     */
    public function fields() {
        $fields = parent::fields();
        unset($fields['password'], $fields['password_salt'], $fields['auth_token'], $fields['is_deleted'], $fields['email_verification_token'], $fields['cookie_token'], $fields['secure_token'], $fields['settings']);
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        // $tokenOk =  $authToken = Yii::$app->security->hashData($idSession, $user->password_salt);
        if (empty($token) || !is_scalar($token)) {
            throw new InvalidArgumentException("The provided access token is invalid.");
        }

        $event = new UserAccessTokenLoginEvent();
        $event->type = $type;
        $event->token = $token;
        Yii::$app->trigger(Module::EVENT_USER_ACCESS_TOKEN_LOGIN, $event);

        if ($event->user) {
            $user = $event->user;
        } else {
            $userId = UserLogin::find()->select(['user_id'])->where(['auth_token' => $token, 'ip' => Yii::$app->request->userIP, 'is_destroyed' => false])->scalar();
            if (!empty($userId)) {
                $user = static::findOne(['id' => $userId]);
            } else {
                $user = null;
            }
        }

        // if the given user can be found, udpate the api last activity timestamp.
        if ($user) {
            $user->updateAttributes(['api_last_activity' => time()]);
        }

        // this ensures the user cookie won't be destroyed.
        Yii::$app->adminuser->enableAutoLogin = false;
        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        $userAgent = Yii::$app->request->userAgent;

        // no user agent, dissable auto login
        if (empty($userAgent)) {
            return false;
        }

        $checksum = UserDevice::generateUserAgentChecksum($userAgent);

        $model = UserDevice::find()->where(['user_id' => $this->id, 'user_agent_checksum' => $checksum])->one();

        if ($model) {
            // update last update timestamp and return existing auth key
            $model->touch('updated_at');
            return $model->auth_key;
        }

        $model = new UserDevice();
        $model->user_id = $this->id;
        $model->user_agent = $userAgent;
        $model->user_agent_checksum = $checksum;
        $model->auth_key = Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomKey() . $checksum);

        if ($model->save()) {
            return $model->auth_key;
        }

        return false;
    }
}
