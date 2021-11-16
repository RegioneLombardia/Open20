<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\socialauth\utility
 * @category   CategoryName
 */

namespace open20\amos\socialauth\utility;

use open20\amos\admin\models\UserProfile;
use open20\amos\socialauth\models\SocialIdmUser;
use yii\base\Event;

/**
 * Class SocialAuthUtility
 * @package open20\amos\socialauth\utility
 */
class SocialAuthUtility
{

    /**
     * Questi sono metodi di accesso che non tengono conto del codice fiscale
     * Su questi metodi tutte le logiche sul codice fiscale non verranno prese in considerazione
     *
     * @var string[]
     */
    private static  $accessMethodsWithoutCF = [
        'EIDAS',
        'UTENTE',
    ];

    /**
     * This method takes an array of UserProfile objects and return an array with user id in the key and name, surname and email in the value.
     * @param UserProfile[] $userProfiles
     * @return array
     */
    public static function makeUsersByCFReadyForSelect($userProfiles)
    {
        $readyForSelect = [];
        foreach ($userProfiles as $userProfile) {
            $readyForSelect[$userProfile->user_id] = $userProfile->getNomeCognome() . ' (' . $userProfile->user->email . ')';
        }
        return $readyForSelect;
    }

    /**
     * This method takes an array of UserProfile objects and return an array with user id in the key and name, surname and email in the value.
     * @param UserProfile[] $userProfiles
     * @return array
     */
    public static function makeUsersByCFUserIds($userProfiles)
    {
        $userIds = [];
        foreach ($userProfiles as $userProfile) {
            $userIds[$userProfile->user_id] = $userProfile->user_id;
        }
        return $userIds;
    }

    /**
     * @param $userDatas
     * @return bool
     */
    public static function createIdmUser($userDatas, $user_id = null)
    {
        $userId = (!\open20\amos\core\utilities\CurrentUser::isPlatformGuest() ? \Yii::$app->user->id : 0);

        if(!empty($user_id)){
            $userId = $user_id;
        }

        $ok = true;

        if ($userDatas instanceof Event) {
            $userProfile = $userDatas->sender;
            if (!is_null($userProfile) && ($userProfile instanceof UserProfile)) {
                $userId = $userProfile->user_id;
            }
            $userDatas = \Yii::$app->session->get('IDM');

            if (
                !$userDatas ||
                is_null(\Yii::$app->request->get('from-shibboleth')) ||
                (!is_null(\Yii::$app->request->get('from-shibboleth')) && (\Yii::$app->request->get('from-shibboleth') == 0))
            ) {
                return false;
            }
        }

        $accessMethod = reset($userDatas['rawData']['saml-attribute-originedatiutente']);
        // l'update del codice fiscale va fatto solo per le origini con codice fiscale.
        if(!in_array($accessMethod, self::$accessMethodsWithoutCF)) {
            // Update codice fiscale
            self::updateFiscalCode($userId, $userDatas['codiceFiscale']);
        }

        $socialIdmUser = SocialIdmUser::findOne([
            'numeroMatricola' => $userDatas['matricola'],
            'emailAddress' => $userDatas['emailAddress'],
            'codiceFiscale' => $userDatas['codiceFiscale'],
            'user_id' => $userId,
        ]);

        if (is_null($socialIdmUser)) {
            $socialIdmUser = new SocialIdmUser();
            $socialIdmUser->numeroMatricola = $userDatas['matricola'];
            $socialIdmUser->nome = $userDatas['nome'];
            $socialIdmUser->cognome = $userDatas['cognome'];
            $socialIdmUser->emailAddress = $userDatas['emailAddress'];
            $socialIdmUser->codiceFiscale = $userDatas['codiceFiscale'];
            $socialIdmUser->user_id = $userId;
        }

        $socialIdmUser->accessMethod = $accessMethod;
        $socialIdmUser->accessLevel = reset($userDatas['rawData']['saml-attribute-tipoautenticazione']);
        $socialIdmUser->rawData = serialize($userDatas['rawData']);
        $ok = $socialIdmUser->save(false);

        //Remove session data
        \Yii::$app->session->remove('IDM');

        return $ok;
    }

    /**
     * @param $user_id
     * @return int
     */
    public static function disconnectIdm($user_id){
        $ok = SocialIdmUser::deleteAll(['user_id' => $user_id]);
        return $ok;
    }

    /**
     * @param int $userId
     * @param string $fiscalCode
     * @return bool
     */
    public static function updateFiscalCode($userId, $fiscalCode)
    {
        $user = UserProfile::findOne(['user_id' => $userId]);

        if($user instanceof UserProfile) {
            $user->codice_fiscale = $fiscalCode;
            return $user->save(false);
        }

        return false;
    }
    
    /**
     * @param int $userId
     * @return SocialIdmUser|null
     */
    public static function findSocialIdmByUserId($userId)
    {
        $socialIdmUser = SocialIdmUser::findOne(['user_id' => $userId]);
        return $socialIdmUser;
    }
}
