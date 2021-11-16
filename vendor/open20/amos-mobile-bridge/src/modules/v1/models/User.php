<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-mobile-bridge
 * @category   CategoryName
 */
namespace open20\amos\mobile\bridge\modules\v1\models;

class User extends \open20\amos\core\user\User
{
    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'slimProfile';
        $fields[] = 'socialIdm';
        $fields[] = 'userImage';
        $fields[] = 'accessToken';
        $fields[] = 'fcmToken';
        return $fields;
    }

    /**
     * @return mixed
     */
    public function getSlimProfile()
    {
        return $this->profile->toArray(
            [
                'nome',
                'cognome',
                'sesso',
                'presentazione_breve',
                'presentazione_personale'
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getSocialIdm()
    {
        return $this->socialIdmProfile ? $this->socialIdmProfile->toArray(
            [
                'numeroMatricola',
                'codiceFiscale',
                'accessMethod',
                'accessLevel'
            ]
        ) : null;
    }

    /**
     * @return string
     */
    public function getUserImage()
    {
        $userProfileImage = $this->profile->userProfileImage;

        return $userProfileImage ? $userProfileImage->getWebUrl('original', true) : '';
    }

    public function getAccessToken() {
        $token = AccessTokens::findOne(['user_id' => $this->id]);

        return $token->access_token;
    }

    public function getFcmToken() {
        $token = AccessTokens::findOne(['user_id' => $this->id]);

        return $token->fcm_token;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $Token = AccessTokens::findOne(['access_token' => $token]);

        if ($Token) {
            return $Token->user;
        }

        return false;
    }

    /**
     * 
     * @param type $deviceToken
     * @param type $deviceOs
     * @return \open20\amos\mobile\bridge\modules\v1\models\AccessTokens
     */
    public function refreshAccessToken($deviceToken, $deviceOs)
    {
        $token = AccessTokens::find()
            ->andWhere(['fcm_token' => $deviceToken])
            ->andWhere(['device_os' => $deviceOs])
            ->andWhere(['user_id' => $this->id])
            ->one();

        if(is_null($token)){
            $token = new AccessTokens();
            $token->user_id = $this->id;
            $token->access_token = \Yii::$app->getSecurity()->generateRandomString();
            $token->fcm_token = $deviceToken;
            $token->device_os = $deviceOs;
            $token->save(false);
        }
        return $token;
    }

    public function getSocialIdmProfile() {
        $socialModule = \Yii::$app->getModule('socialauth');

        if($socialModule->id) {
            return $this->hasOne(\open20\amos\socialauth\models\SocialIdmUser::className(), ['user_id' => 'id']);
        }

        return null;
    }
}