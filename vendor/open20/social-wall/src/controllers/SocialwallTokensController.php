<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\socialwall\controllers 
 */
 
namespace open20\socialwall\controllers;

use open20\socialwall\models\SocialwallTokens;
use open20\socialwall\models\SocialwallTokensFacebookAccounts;
use open20\socialwall\Module;
use open20\socialwall\utility\Social;
use open20\socialwall\utility\social_clients\facebook\FacebookClient;
use open20\socialwall\utility\social_clients\instagram\InstagramClient;
use open20\socialwall\utility\social_clients\twitter\TwitterClient;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;

/**
 * Class SocialwallTokensController 
 * This is the class for controller "SocialwallTokensController".
 * @package open20\socialwall\controllers 
 */
class SocialwallTokensController extends \open20\socialwall\controllers\base\SocialwallTokensController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'facebook-accounts-selection',
                            'save-facebook-accounts-selection',
                            'instagram-account-handler',
                            'force-instagram-cache',
                        ],
                        'roles' => ['SOCIALWALL_ADMIN']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    public function actionInstagramAccountHandler() {
        $state = \Yii::$app->request->get('state', null);
        $code = \Yii::$app->request->get('code', null);
        $socialWallTokensModel = null;
        $socialWallTokensData = [];

        // Check Socialwall Tokens Record Exists (also verifying the secret token sent to instagram and bounced back after login process)
        if(!empty($state)) {
            $state = Json::decode($state);
            if(!empty($state)) {
                $socialWallTokensModel = SocialwallTokens::findOne(['id' => $state['socialwallTokensId'], 'type' => Social::INSTAGRAM, 'secret' => $state['secret']]);
                if(empty($socialWallTokensModel)) {
                    throw new ForbiddenHttpException("SocialwallTokens model not found");
                }
            } else {
                throw new ForbiddenHttpException("State decoding error");
            }
        } else {
            throw new ForbiddenHttpException("Empty state");
        }

        // Change SocialwallTokens secret
        // FOR DEVS: FOR DEVELOPING PURPOSES COMMENT THE FOLLOWING LINE TO AVOID CREATING A NEW SECRET EACH TIME
        // THE FOLLOWING ROW PREVENTS VARIOUS TYPES OF BAD/MALICIOUS REQUESTS
        $socialWallTokensModel->save(false);

        $instagramClient = new InstagramClient();

        // Get long access token using response code
        $longAccessToken = $instagramClient->getLongAccessTokenFromCode($code);

        // Using long access token to get long access tokens for authorized profiles (private and public profiles)
        $accessToken = $socialWallTokensData['long_access_token'] = $longAccessToken['access_token'];

        $profileData = $instagramClient->getLoggedProfileData($accessToken);

        $socialWallTokensData['accounts'][$profileData['id']] = [
            "id" => $profileData['id'],
            "name" => $profileData['username'],
            "access_token" => $accessToken,
        ];

        $socialWallTokensModel->data = Json::encode($socialWallTokensData);
        $socialWallTokensModel->expires_at = InstagramClient::defineTokenExpireDate($longAccessToken['expires_in'], false);
        if($socialWallTokensModel->save(false)) {
            \Yii::$app->session->addFlash('success', Module::t('socialwall', 'Profilo instagram salvato correttamente'));
        } else {
            \Yii::$app->session->addFlash('success', Module::t('danger', 'C\'è stato un errore nel salvataggio del profilo instagram desiderato'));
        }

        return $this->redirect('/socialwall/socialwall-tokens');
    }

    public function actionFacebookAccountsSelection() {
        $this->setUpLayout('form');

        $state = \Yii::$app->request->get('state', null);
        $socialWallTokensModel = null;
        $socialWallTokensData = [];

        // Check Socialwall Tokens Record Exists (also verifying the secret token sent to facebook and bounced back after login process)
        if(!empty($state)) {
            $state = Json::decode($state);
            if(!empty($state)) {
                $socialWallTokensModel = SocialwallTokens::findOne(['id' => $state['socialwallTokensId'], 'type' => Social::FACEBOOK, 'secret' => $state['secret']]);
                if(empty($socialWallTokensModel)) {
                    throw new ForbiddenHttpException("SocialwallTokens model not found");
                }
            } else {
                throw new ForbiddenHttpException("State decoding error");
            }
        } else {
            throw new ForbiddenHttpException("Empty state");
        }

        // Change SocialwallTokens secret
        // FOR DEVS: COMMENT THE FOLLOWING LINE TO AVOID CREATING A NEW SECRET EACH TIME FOR DEVELOPING PURPOSES
        // THE FOLLOWING ROW PREVENTS VARIOUS TYPES OF BAD/MALICIOUS REQUESTS
        $socialWallTokensModel->save(false);

        $socialWallTokensData['socialwallTokensName'] = $socialWallTokensModel['name'];
        $socialWallTokensData['socialwallTokensId'] = $state['socialwallTokensId'];

        $facebookClient = new FacebookClient();

        // Get long access token using response code
        $longAccessToken = $facebookClient->getLongAccessTokenFromCode(\Yii::$app->request->get('code', null));

        // Using long access token to get long access tokens for authorized profiles (private and public profiles)
        $accessToken = $socialWallTokensData['longAccessToken'] = $longAccessToken['access_token'];

        // Get private account info
        // Private profile currently disabled
//        $privateAccountInfo = $facebookClient->getAccountInfo($accessToken);
//        $socialWallTokensData['accounts'][] = [
//            'id' => $privateAccountInfo['id'],
//            'name' => $privateAccountInfo['name'],
//            'access_token' => $accessToken,
//            'privateProfile' => true,
//        ];

        // Get public accounts info
        $publicAccountsInfo = $facebookClient->getPublicAccountsInfo($accessToken);

        foreach ($publicAccountsInfo['data'] as $publicAccount) {
            $socialWallTokensData['accounts'][] = [
                'id' => $publicAccount['id'],
                'name' => $publicAccount['name'],
                'access_token' => $publicAccount['access_token'],
            ];
        }

        // Get token expire date
        $socialWallTokensData['expire_date'] = $facebookClient->getTokenExpireDate($accessToken, $accessToken);

        return $this->render('facebook-accounts-selection-form', [
            'dummyModel' => new SocialwallTokensFacebookAccounts(),
            'accountsList' => $socialWallTokensData,
        ]);
    }

    public function actionSaveFacebookAccountsSelection() {
        $post = \Yii::$app->request->post();

        if(!empty($post)) {
            $socialwallTokensModel = SocialwallTokens::findOne(['id' => $post['socialwall_tokens_id']]);

            $socialwallTokensData = [];
            $socialwallTokensData['long_access_token'] = $post['long_access_token'];

            foreach($post['Accounts'] as $account) {
                if(array_key_exists('selected', $account) && $account['selected']) {
                    unset($account['selected']);
                    $socialwallTokensData['accounts'][$account['id']] = $account;
                }
            }

            $socialwallTokensModel->data = Json::encode($socialwallTokensData);
            $socialwallTokensModel->expires_at = $post['expire_date'];

            if($socialwallTokensModel->save(false)) {
                \Yii::$app->session->addFlash('success', Module::t('socialwall', 'Profili facebook selezionati salvati correttamente'));
            } else {
                \Yii::$app->session->addFlash('success', Module::t('danger', 'C\'è stato un errore nel salvataggio dei profili facebook selezionati'));
            }

            return $this->redirect('/socialwall/socialwall-tokens');
        } else {
            throw new ForbiddenHttpException();
        }
    }

    public function actionForceInstagramCache($socialwallTokensId) {
        Social::cacheInstagramPosts($socialwallTokensId);
        \Yii::$app->session->addFlash('success', Module::t('socialwall', 'Cache instagram aggiornata'));
        $this->redirect(Url::previous());
    }

}
