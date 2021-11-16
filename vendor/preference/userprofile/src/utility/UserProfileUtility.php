<?php
namespace preference\userprofile\utility;

use open20\amos\admin\models\UserOtpCode;
use open20\amos\socialauth\models\SocialIdmUser;
use open20\amos\socialauth\Module;
use open20\amos\socialauth\utility\SocialAuthUtility;
use preference\userprofile\models\UserProfile;
use Yii;
use yii\helpers\VarDumper;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    preference\userprofile\utilty
 * @category
 */

/**
 * Description of UserProfileUtility
 *
 */
class UserProfileUtility
{

    /**
     * @param $userId
     * @return array
     */
    public static function getIDMFields($userId)
    {
        $data = [];

        /** @var Module $moduleSa */
        $moduleSa = Yii::$app->getModule('socialauth');
        if ($moduleSa) {
            $idmUser = $moduleSa->findSocialIdmByUserId($userId);
            if (!empty($idmUser)){
                /** @var SocialIdmUser $idmUser */
                $idmUser = $moduleSa->findSocialIdmByUserId($userId);
//                $data['accessMethod'] = $idmUser->accessMethod;
                $accessMethod = $idmUser->accessMethod;
                $idmData = unserialize($idmUser->rawData);
                $data['typeId'] = self::getTypePersonIdentifier($idmData, $accessMethod);
                $data['id'] = self::getPersonIdentifier($idmData, $accessMethod);
            } elseif (!empty(Yii::$app->session->get('IDM'))) {
                $userDatas = Yii::$app->session->get('IDM');
                $accessMethod = null;
                if (isset($userDatas['rawData']['saml-attribute-originedatiutente'][0])) {
                    $accessMethod = $userDatas['rawData']['saml-attribute-originedatiutente'][0];
                }
                $data['typeId'] = self::getTypePersonIdentifier($userDatas['rawData'], $accessMethod);
                $data['id'] = self::getPersonIdentifier($userDatas['rawData'], $accessMethod);
            }
        }

        return $data;
    }

    /**
     * @param $rawData
     * @param $accessMethod
     * @return mixed|string
     */
    private static function getTypePersonIdentifier($rawData, $accessMethod)
    {
        switch ($accessMethod) {
            case 'SPID':
            case 'SMARTCARD':
            case 'ARCHIVIO_CARTE':
            case 'IDM':
                    return 'Codice Fiscale';
                break;
            case 'EIDAS':
                    return 'ID eIDAS';
                break;
            case 'UTENTE':
                    return 'Nome Utente (stranieri)';
                break;
            default:
                return '';
        }

        return '';
    }

    /**
     * @param $rawData
     * @param $accessMethod
     * @return mixed|string
     */
    private static function getPersonIdentifier($rawData, $accessMethod)
    {
        switch ($accessMethod) {
            case 'SPID':
            case 'SMARTCARD':
            case 'ARCHIVIO_CARTE':
            case 'IDM':
                if (isset($rawData['saml-attribute-codicefiscale'][0])) {
                    return $rawData['saml-attribute-codicefiscale'][0];
                }
                break;
            case 'EIDAS':
                if (isset($rawData['saml-attribute-identificativoutente'][0])) {
                    return $rawData['saml-attribute-identificativoutente'][0];
                }
                break;
            case 'UTENTE':
                if (isset($rawData['saml-attribute-nomeutente'][0])) {
                    return $rawData['saml-attribute-nomeutente'][0];
                }
                break;
            default:
                return '';
        }

        return '';
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function getIDMLabels($key)
    {
        $data = [];
//        $data['accessMethod'] = Yii::t('preferenceuser', 'Metodo di autenticazione');
        $data['id'] = Yii::t('preferenceuser', 'ID utente');
        $data['typeId'] = Yii::t('preferenceuser', 'Tipo ID');
        return isset($data[$key])? $data[$key]: '';
    }

    /**
     * @param null $user
     * @throws \yii\base\InvalidConfigException
     */
    public static function generateOPT($user = null)
    {
        $code = (string)rand(10000, 99999);
        $id_session = \Yii::$app->session->getId();

        if (!empty($user)) {
            $authentication = UserOtpCode::find()
                ->andWhere(['user_id' => $user->id])->one();
        }else{
            $authentication = UserOtpCode::find()
                ->andWhere(['session_id' => $id_session])->one();
        }

        if (empty($authentication)) {
            $authentication = new UserOtpCode();
        }

        $expireDate = new \DateTime();
        $expireDate->modify('+5 minutes');

        $authentication->session_id = $id_session;
        if (!empty($user)) {
            $authentication->user_id = $user->id;
        }

        $authentication->type = UserOtpCode::TYPE_AUTH_EMAIL;
        $authentication->otp_code = $code;
        $authentication->expire = $expireDate->format('Y-m-d H:i:s');

        $authentication->save();

        return $authentication;


    }

    /**
     *
     * @param UserProfile $userProfile
     * @throws \yii\db\Exception
     */
    public static function deleteProfile($userProfile)
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = $userProfile->user;
            $user->status = 50;
            $userProfile->pc_request_delete_date = date("Y-m-d H:i:s");

            $deletePrefixString = 'DELETED' .$user->id. '_';
            if (!empty($userProfile->codice_fiscale)) {
                $userProfile->codice_fiscale = $deletePrefixString . $userProfile->codice_fiscale;
            }

            $user->email = $deletePrefixString . $user->email;
            $user->username = $deletePrefixString . $user->username;

            // Elimino IDM user...
            SocialAuthUtility::disconnectIdm($user->id);

            $user->save(false);
            $userProfile->save(false);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

    }

}
