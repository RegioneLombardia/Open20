<?php

namespace open20\socialwall\utility\social_clients\facebook;

use open20\socialwall\models\SocialwallInstagramStore;
use open20\socialwall\models\SocialwallInstagramToken;
use open20\socialwall\models\SocialwallTokens;
use open20\socialwall\utility\Social;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\httpclient\Client;
use yii\log\Logger;

class FacebookClient
{

    const MAIN_URL = "https://www.facebook.com";
    const API_GRAPH_URL = "https://graph.facebook.com";
    const API_VERSION = "v17.0";
    const MAIN_API = self::MAIN_URL . '/' . self::API_VERSION;
    const GRAPH_API = self::API_GRAPH_URL . '/' . self::API_VERSION;

    public $access_token;
    public $client_secret;

    public function isOkStatus($result)
    {
        $ret = false;
        if (!is_null($result)) {
            $ret = true;
        }
        return $ret;
    }

    public static function getClientId()
    {
        return \Yii::$app->params['facebook']['client_id'];
    }

    public function getClientSecret()
    {
        return \Yii::$app->params['facebook']['client_secret'];
    }

    public static function getRedirectUri()
    {
        return \Yii::$app->params['facebook']['redirect_uri'];
    }

    public static function mainApiUrl()
    {
        if(array_key_exists('facebook', \Yii::$app->params) &&
            array_key_exists('apiVersion', \Yii::$app->params['facebook']) &&
            !empty(\Yii::$app->params['facebook']['apiVersion'])) {
            return self::MAIN_URL . '/' . \Yii::$app->params['facebook']['apiVersion'];
        } else {
            return self::MAIN_API;
        }
    }

    public static function graphApiUrl()
    {
        if(array_key_exists('facebook', \Yii::$app->params) &&
            array_key_exists('apiVersion', \Yii::$app->params['facebook']) &&
            !empty(\Yii::$app->params['facebook']['apiVersion'])) {
            return self::API_GRAPH_URL . '/' . \Yii::$app->params['facebook']['apiVersion'];
        } else {
            return self::GRAPH_API;
        }
    }

    public function createNewClient($baseUrl = null)
    {
        return new \yii\httpclient\Client(['baseUrl' => empty($baseUrl) ? self::graphApiUrl() : $baseUrl]);
    }

    public static function getAuthorizeUrl()
    {
        return Social::facebookLoginUrlGenerator();
    }

    /**
     * Get short (lifespan) access token
     *
     * @param $code
     * @return mixed|null Array with oauth/access_token API response
     * @throws \Exception
     */
    public function getShortAccessToken($code = null)
    {
        $result = null;

        if (!empty($code)) {
            $client = $this->createNewClient();

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl('oauth/access_token')
                    ->setMethod('POST')
                    ->setData([
                        "client_id" => self::getClientId(),
                        "redirect_uri" => self::getRedirectUri(),
                        "client_secret" => self::getClientSecret(),
                        "code" => $code,
                    ])
                    ->send();

                if ($response->getStatusCode() == '200') {
                    $arrayRet = Json::decode($response->getContent(), true);
                    if (!empty($arrayRet)) {
                        $result = $arrayRet;
                    }
                } else {
                    \Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook short access token: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code provided in Facebook getShortAccessToken function");
        }

        return $result;
    }

    /**
     * Get long (lifespan) access token, expires in ~ 60 days
     *
     * @param $accessToken
     * @return mixed|null Array with oauth/access_token response
     * @throws \Exception
     */
    public function getLongAccessToken($accessToken = null)
    {
        $result = null;

        if (!empty($accessToken)) {
            $client = $this->createNewClient();

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl('oauth/access_token')
                    ->setMethod('GET')
                    ->setData([
                        "grant_type" => "fb_exchange_token",
                        "client_id" => self::getClientId(),
                        "client_secret" => self::getClientSecret(),
                        "fb_exchange_token" => $accessToken,
                    ])
                    ->send();

                if ($response->getStatusCode() == '200') {
                    $arrayRet = Json::decode($response->getContent(), true);
                    if (!empty($arrayRet)) {
                        $result = $arrayRet;
                    }
                } else {
                    \Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook long access token: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code provided in Facebook getLongAccessToken function");
        }

        return $result;
    }

    /**
     * (Logged) private account infos
     *
     * @param $longAccessToken
     * @return mixed|null
     * @throws \Exception
     */
    public function getAccountInfo($longAccessToken = null)
    {
        $result = null;

        if (!empty($longAccessToken)) {
            $client = $this->createNewClient();

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl('me')
                    ->setMethod('GET')
                    ->setData([
                        "access_token" => $longAccessToken,
                        "format" => "json",
                    ])
                    ->send();

                if ($response->getStatusCode() == '200') {
                    $arrayRet = Json::decode($response->getContent(), true);
                    if (!empty($arrayRet)) {
                        $result = $arrayRet;
                    }
                } else {
                    \Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook private account info: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code provided in Facebook getAccountInfo function");
        }

        return $result;
    }

    /**
     * Public accounts with granted access infos
     *
     * @param $longAccessToken
     * @return mixed|null Array with me/accounts API response
     * @throws \Exception
     */
    public function getPublicAccountsInfo($longAccessToken = null)
    {
        $result = null;

        if (!empty($longAccessToken)) {
            $client = $this->createNewClient();

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl('me/accounts')
                    ->setMethod('GET')
                    ->setData([
                        "access_token" => $longAccessToken,
                        "format" => "json",
                    ])
                    ->send();

                if ($response->getStatusCode() == '200') {
                    $arrayRet = Json::decode($response->getContent(), true);
                    if (!empty($arrayRet)) {
                        $result = $arrayRet;
                    }
                } else {
                    \Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook public accounts infos: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code provided in Facebook getPublicAccountsInfo function");
        }

        return $result;
    }

    public function getDebugToken($accessToken = null, $inputTokenToCheck = null)
    {
        $result = null;

        if (!empty($accessToken) && !empty($inputTokenToCheck)) {
            $client = $this->createNewClient();

            try {
                $responseDebugToken = $client
                    ->createRequest()
                    ->setUrl('debug_token')
                    ->setMethod('GET')
                    ->setData([
                        "access_token" => $accessToken,
                        "input_token" => $inputTokenToCheck,
                    ])
                    ->send();

                if ($responseDebugToken->getStatusCode() == '200') {
                    $arrayRet = Json::decode($responseDebugToken->getContent(), true);
                    if (!empty($arrayRet)) {
                        $result = $arrayRet;
                    }
                } else {
                    \Yii::getLogger()->log(json_encode($responseDebugToken->getContent()), Logger::LEVEL_ERROR);
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook get debug token: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty accessToken and inputTokenToCheck provided in Facebook getDebugToken function");
        }

        return $result;
    }

    public function getTokenExpireDate($accessToken = null, $inputTokenToCheck = null)
    {
        $result = null;

        if (!empty($accessToken) && !empty($inputTokenToCheck)) {
            try {
                $result = date('Y-m-d H:i:s', $this->getDebugToken($accessToken, $inputTokenToCheck)['data']['data_access_expires_at']);
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook get token expire date: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty accessToken and inputTokenToCheck provided in Facebook getTokenExpireDate function");
        }

        return $result;
    }

    public function getLongAccessTokenFromCode($code = null) {
        // Get short access token using code response from FB
        $shortAccessToken = $this->getShortAccessToken($code);
        // Get long access token using short access token
        return $this->getLongAccessToken($shortAccessToken['access_token']);
    }

    public function getFeedForSocialwallTokensId($socialwallTokensId, $searchKeys = [], $feedLimit = 30, $getFormattedFeed = false, $since = null, $until = null) {
        $result = [];

        if(!empty($socialwallTokensId) && !empty($feedLimit)) {
            $socialwallTokensModel = SocialwallTokens::findOne(['id' => $socialwallTokensId, 'type' => Social::FACEBOOK]);
            if(!empty($socialwallTokensModel)) {
                try {
                    $socialwallTokens = Json::decode($socialwallTokensModel->data);

                    foreach ($socialwallTokens["accounts"] as $account) {
                        if(!$getFormattedFeed) {
                            $result[$account['id']] = [
                                "id" => $account['id'],
                                "name" => $account['name'],
                                "profile_picture" => $this->getProfilePicture($account['id'], $account["access_token"]),
                                "feed" => !empty($searchKeys) ?
                                            $this->getFeedWithSearchKey($account['id'], $account["access_token"], $feedLimit, $searchKeys, $since, $until) :
                                            $this->getFeed($account['id'], $account["access_token"], $feedLimit, $since, $until),
                            ];
                        } else {
                            $feed = !empty($searchKeys) ?
                                        $this->getFeedWithSearchKey($account['id'], $account["access_token"], $feedLimit, $searchKeys, $since, $until) :
                                        $this->getFeed($account['id'], $account["access_token"], $feedLimit, $since, $until);
                            if(array_key_exists('data', $feed)) {
                                foreach($feed['data'] as $post) {
                                    $relevantPostData = $this->getRelevantPostData($post);
                                    if(!empty($relevantPostData)) {
                                        $result[$account['id']][] = Social::formatPost(
                                            Social::FACEBOOK,
                                            $account['name'],
                                            $this->getProfilePictureUrl($account['id'], $account["access_token"]),
                                            $relevantPostData['postText'],
                                            $relevantPostData['postImageUrl'],
                                            $relevantPostData['permalink'],
                                            $relevantPostData['postDatetime'],
                                            "https://www.facebook.com/profile.php?id={$account['id']}"
                                        );
                                    }
                                }
                            }
                        }

                    }
                } catch (\Exception $e) {
                    // DO NOTHING
                }
            }
        }

        return $result;
    }

    public function getRelevantPostData($post) {
        $result = [];

        // THERE MUST BE THE POST MESSAGE OR FULL PICTURE AT LEAST
        if(!empty($post['message']) || !empty($post['full_picture'])) {
            // TODO BEFORE ---- GET MESSAGE (MUST BE SET - IF IT DOES NOT EXIST, POST WILL BE EMPTY)
            // $result['postText'] = $post['message'];
            // GET MESSAGE IF IT DOES EXIST
            $result['postText'] = "";
            if(array_key_exists('message', $post)) {
                $result['postText'] = $post['message'];
            }
            // GET IMAGE IF IT DOES EXIST
            $result['postImageUrl'] = null;
            if(array_key_exists('full_picture', $post)) {
                $result['postImageUrl'] = $post['full_picture'];
            }
            // GET PERMALINK IF IT DOES EXIST
            $result['permalink'] = null;
            if(array_key_exists('permalink_url', $post)) {
                $result['permalink'] = $post['permalink_url'];
            }
            // GET POST DATETIME IF IT DOES EXIST
            $result['postDatetime'] = null;
            if(array_key_exists('updated_time', $post)) {
                $result['postDatetime'] = $post['updated_time'];
            }
        }

        return $result;
    }

    public function getProfilePictureUrl($id, $accessToken, $type = "large") {
        $result = null;

        try {
            $result = $this->getProfilePicture($id, $accessToken, $type)['data']['url'];
        } catch (\Exception $e) {
            // DO NOTHING
        }

        return $result;
    }

    public function getProfilePicture($id, $accessToken, $type = "large") {
        $result = null;

        if (!empty($id) && !empty($accessToken) && !empty($type)) {
            $client = $this->createNewClient();

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl("{$id}/picture")
                    ->setMethod('GET')
                    ->setData([
                        "access_token" => $accessToken,
                        "type" => $type,
                        "redirect" => false,
                    ])
                    ->send();

                if ($response->getStatusCode() == '200') {
                    $arrayRet = Json::decode($response->getContent(), true);
                    if (!empty($arrayRet)) {
                        $result = $arrayRet;
                    }
                } else {
                    \Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook public picture: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code, type and accessToken provided in Facebook getProfilePicture function");
        }

        return $result;
    }

    public function getFeed($id, $accessToken, $limit = 30, $since = null, $until = null, $fields = "id,full_picture,message,updated_time,permalink_url") {
        $result = null;

        if (!empty($id) && !empty($accessToken) && !empty($fields)) {
            $client = $this->createNewClient();

            $data = [
                "access_token" => $accessToken,
                "fields" => $fields,
                "limit" => $limit,
            ];

            if(!empty($since)) {
                $data['since'] = $since;
            }

            if(!empty($until)) {
                $data['until'] = $until;
            }

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl("{$id}/feed")
                    ->setMethod('GET')
                    ->setData($data)
                    ->send();

                if ($response->getStatusCode() == '200') {
                    $arrayRet = Json::decode($response->getContent(), true);
                    if (!empty($arrayRet)) {
                        $result = $arrayRet;
                    }
                } else {
                    \Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
                    $result = [
                        'data' => []
                    ];
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook public feed by id: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code, fields and accessToken provided in Facebook getFeed function");
        }

        return $result;
    }

    public function getFeedWithSearchKey($id, $accessToken, $limitResults = 6, $searchKeys = [], $since = null, $until = null, $limitPagesSearch = 3, $limitResultsPerPage = 30, $fields = "id,full_picture,message,updated_time,permalink_url") {
        $result = [];

        if (!empty($id) && !empty($accessToken) && !empty($limitResults) && !empty($searchKeys) && is_array($searchKeys) && !empty($fields)) {
            try {
                $feedPerPage = $this->getFeed($id, $accessToken, $limitResultsPerPage, $since, $until, $fields);
                for($pagesSearched = 1; $pagesSearched <= $limitPagesSearch; $pagesSearched++) {
                    if(array_key_exists('data', $feedPerPage) && is_array($feedPerPage['data'])) {
                        $data = $feedPerPage['data'];
                        if(!empty($data) && is_array($data)) {
                            foreach($data as $post) {
                                $includeResult = false;
                                foreach($searchKeys as $key) {
                                    if((array_key_exists('message', $post)) && stripos($post['message'], $key) !== false) {
                                        $includeResult = true;
                                    }
                                }
                                if($includeResult) {
                                    $result[] = $post;
                                    if(count($result) === $limitResults) {
                                        return ["data" => $result];
                                    }
                                }
                            }
                        }
                    }
                    if($pagesSearched !== $limitPagesSearch) {
                        $feedPerPage = $this->getNextPageFeed($feedPerPage);
                    }
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook public feed by id with search key: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code, fields, searchKey and accessToken provided in Facebook getFeedWithSearchKey function");
        }

        return ["data" => $result];
    }

    /**
     * Returns next page from paginated feed
     *
     * @param $feedResult
     * @return array|mixed|null
     * @throws \Exception
     */
    public function getNextPageFeed($feedResult) {
        if(!empty($feedResult) && array_key_exists('paging', $feedResult) && array_key_exists('next', $feedResult['paging'])) {
            return $this->getUrl($feedResult['paging']['next']);
        } else {
            return [];
        }

    }

    /**
     * Returns previous page from paginated feed
     *
     * @param $feedResult
     * @return array|mixed|null
     * @throws \Exception
     */
    public function getPreviousPageFeed($feedResult) {
        if(!empty($feedResult) && array_key_exists('paging', $feedResult) && array_key_exists('previous', $feedResult['paging'])) {
            return $this->getUrl($feedResult['paging']['previous']);
        } else {
            return [];
        }

    }

    public function getUrl($url) {
        $result = null;

        if (!empty($url)) {
            $client = $this->createNewClient($url);

            try {
                $response = $client
                    ->createRequest()
                    ->send();

                if ($response->getStatusCode() == '200') {
                    $arrayRet = Json::decode($response->getContent(), true);
                    if (!empty($arrayRet)) {
                        $result = $arrayRet;
                    }
                } else {
                    \Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get facebook url: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty url provided in Facebook getUrl function");
        }

        return $result;
    }

}
