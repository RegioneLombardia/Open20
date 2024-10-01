<?php

namespace open20\socialwall\utility\social_clients\instagram;

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

class InstagramClient {

    const API_URL = "https://api.instagram.com";
    const API_GRAPH_URL = "https://graph.instagram.com";
    const API_VERSION = "v17.0";
    const MAIN_API = self::API_URL;
    const GRAPH_API = self::API_GRAPH_URL . '/' . self::API_VERSION;
    public $access_token;
    public $client_id;
    public $client_secret;

    public static function getClientId()
    {
        return \Yii::$app->params['instagram']['client_id'];
    }

    public function getClientSecret()
    {
        return \Yii::$app->params['instagram']['client_secret'];
    }

    public static function getRedirectUri()
    {
        return \Yii::$app->params['instagram']['redirect_uri'];
    }

    public static function mainApiUrl()
    {
        return self::MAIN_API;
    }

    public static function graphApiUrl()
    {
        if(array_key_exists('instagram', \Yii::$app->params) &&
            array_key_exists('apiVersion', \Yii::$app->params['instagram']) &&
            !empty(\Yii::$app->params['instagram']['apiVersion'])) {
            return self::API_GRAPH_URL . '/' . \Yii::$app->params['instagram']['apiVersion'];
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
        return Social::instagramLoginUrlGenerator();
    }

    public static function defineTokenExpireDate($expires_in = 0, $asTimestamp = true) {
        $timestamp = time() + $expires_in;
        if($asTimestamp) {
            return $timestamp;
        } else {
            return date('Y-m-d H:i:s', $timestamp);
        }
    }

    public function isOkStatus($result) {
        $ret = false;
        if (!is_null($result)) {
            $ret = true;
        }
        return $ret;
    }

    /**
     * 
     * @return array
     */
    public function getShortAccessToken($code = null) {
        $result = null;

        if (!empty($code)) {
            $client = $this->createNewClient(self::API_URL);

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
                        'grant_type' => 'authorization_code',
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
                throw new \Exception("Cannot get instagram short access token: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code provided in Instagram getShortAccessToken function");
        }

        return $result;
    }

    /**
     * 
     * @param string $short_access_token
     * @return array
     */
    public function getLongAccessToken($short_access_token) {
        $result = null;

        if (!empty($short_access_token)) {
            $client = $this->createNewClient();

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl('access_token')
                    ->setMethod('GET')
                    ->setData([
                        "grant_type" => "ig_exchange_token",
                        "client_secret" => self::getClientSecret(),
                        "access_token" => $short_access_token,
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
                throw new \Exception("Cannot get instagram long access token: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty code provided in Instagram getLongAccessToken function");
        }

        return $result;
    }

    public function getLongAccessTokenFromCode($code = null) {
        // Get short access token using code response from FB
        $shortAccessToken = $this->getShortAccessToken($code);
        // Get long access token using short access token
        return $this->getLongAccessToken($shortAccessToken['access_token']);
    }

    /**
     * 
     * @param type $long_access_token
     * @return type
     */
    public function refreshLongAccessToken($long_access_token) {
        $result = null;

        if (!empty($long_access_token)) {
            $client = $this->createNewClient();

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl('refresh_access_token')
                    ->setMethod('GET')
                    ->setData([
                        "grant_type" => "ig_refresh_token",
                        "access_token" => $long_access_token,
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
                throw new \Exception("Cannot get refresh instagram long access token: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty long_access_token provided in Instagram refreshLongAccessToken function");
        }

        return $result;
    }

    public function getLoggedProfileData($accessToken) {
        $result = null;

        if (!empty($accessToken)) {
            $client = $this->createNewClient();

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl('me')
                    ->setMethod('GET')
                    ->setData([
                        "access_token" => $accessToken,
                        "fields" => "id,username",
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
                throw new \Exception("Cannot get facebook get debug token: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty accessToken provided in Facebook getDebugToken function");
        }

        return $result;
    }

    public function getFeedForSocialwallTokensId($socialwallTokensId, $searchKeys = [], $feedLimit = 30, $getFormattedFeed = false, $cached = true, $since = null, $until = null) {
        $result = [];

        if(!empty($socialwallTokensId)) {
            $socialwallTokensModel = SocialwallTokens::findOne(['id' => $socialwallTokensId, 'type' => Social::INSTAGRAM]);
            if(!empty($socialwallTokensModel)) {
                try {
                    $socialwallTokens = Json::decode($socialwallTokensModel->data);

                    foreach ($socialwallTokens["accounts"] as $account) {
                        if(!$getFormattedFeed) {
                            $result[$account['id']] = [
                                "id" => $account['id'],
                                "name" => $account['name'],
                                "profile_picture" => !empty(Yii::$app->params['instagram']['profile_pictures_url'][$account['id']]) ? Yii::$app->params['instagram']['profile_pictures_url'][$account['id']] : "",
                                "feed" => !empty($searchKeys) ?
                                            ($cached ? $this->getCachedFeedWithSearchKey($account['id'], $feedLimit, $searchKeys, $since, $until) : $this->getFeedWithSearchKey($account["access_token"], $account['id'], $feedLimit, $searchKeys, $since, $until)) :
                                            ($cached ? $this->getCachedFeed($account['id'], $feedLimit, $since, $until) : $this->getFeed($account["access_token"], $account['id'], $feedLimit, $since, $until)),
                            ];
                        } else {
                            $feed = !empty($searchKeys) ?
                                ($cached ? $this->getCachedFeedWithSearchKey($account['id'], $feedLimit, $searchKeys, $since, $until) : $this->getFeedWithSearchKey($account["access_token"], $account['id'], $feedLimit, $searchKeys, $since, $until)) :
                                ($cached ? $this->getCachedFeed($account['id'], $feedLimit, $since, $until) : $this->getFeed($account["access_token"], $account['id'], $feedLimit, $since, $until));
                            if(array_key_exists('data', $feed)) {
                                foreach($feed['data'] as $post) {
                                    $relevantPostData = $this->getRelevantPostData($post);
                                    if(!empty($relevantPostData)) {
                                        $result[$account['id']][] = Social::formatPost(
                                            Social::INSTAGRAM,
                                            $account['name'],
                                            !empty(Yii::$app->params['instagram']['profile_pictures_url'][$account['id']]) ? Yii::$app->params['instagram']['profile_pictures_url'][$account['id']] : "",
                                            $relevantPostData['postText'],
                                            $relevantPostData['postImageUrl'],
                                            $relevantPostData['permalink'],
                                            $relevantPostData['postDatetime'],
                                            "https://instagram.com/{$account['name']}",
                                            $relevantPostData['media_type']
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

        $imageUrl = null;
        if(array_key_exists('media_url', $post)) {
            $imageUrl = $post['media_url'];
        }
//        if(array_key_exists('thumbnail_url', $post)) {
//            $imageUrl = $post['thumbnail_url'];
//        }

        if(!empty($imageUrl)) {
            // GET MESSAGE IF IT DOES EXIST
            $result['postText'] = "";
            if(array_key_exists('caption', $post)) {
                $result['postText'] = $post['caption'];
            }
            // GET IMAGE (MUST BE SET - IF IT DOES NOT EXIST, POST WILL BE EMPTY)
            $result['postImageUrl'] = $imageUrl;
            // GET PERMALINK IF IT DOES EXIST
            $result['permalink'] = null;
            if(array_key_exists('permalink', $post)) {
                $result['permalink'] = $post['permalink'];
            }
            // GET POST DATETIME IF IT DOES EXIST
            $result['postDatetime'] = null;
            if(array_key_exists('timestamp', $post)) {
                $result['postDatetime'] = $post['timestamp'];
            }
            // GET POST media_type IF IT DOES EXIST
            $result['media_type'] = null;
            if(array_key_exists('media_type', $post)) {
                $result['media_type'] = $post['media_type'];
            }
        }

        return $result;
    }

    public function getFeed($token, $userId, $limit = 30, $since = null, $until = null, $usermediaFields = "IMAGE,VIDEO,CAROUSEL_ALBUM", $mediaFields = "caption,id,media_type,media_url,permalink,thumbnail_url,timestamp,username") {
        $feed = [];

        $userMediaResult = $this->getUserMedia($token, $userId, $since, $until, $usermediaFields);
        $userMedia = array_slice($userMediaResult['data'], 0, $limit, true);

        if(!empty($userMedia) && is_array($userMedia)) {
            foreach($userMedia as $mediaId) {
                if(!empty($mediaId['id'])) {
                    try {
                        $feed['data'][$mediaId['id']] = $this->getMedia($token, $mediaId['id'], $mediaFields);
                    } catch (\Exception $e) {
                        // DO NOTHING (exclude data with errors)
                    }
                }
            }

            if(!empty($userMediaResult['paging'])) {
                $feed['paging'] = $userMediaResult['paging'];
            }
        }

        return $feed;
    }

    public function getCachedFeed($userId, $limit = 30, $since = null, $until = null) {
        $result = [];

        if(!empty($userId) && !empty($limit)) {
            $instagramStore = SocialwallInstagramStore::findOne(['id' => 1]);
            try {
                if(!empty($instagramStore) && !empty($instagramStore->json_store)) {
                    $cachedResults = json_decode($instagramStore->json_store, true);
                    if(array_key_exists($userId, $cachedResults)) {
                        // If there is a timespan, filter contents
                        if(!empty($since) || !empty($until)) {
                            foreach($cachedResults[$userId]['data'] as $key => $post) {
                                $postUnixtime = strtotime($post['timestamp']);
                                $includeResult = true;
                                if(!empty($since)) {
                                    $includeResult = $since < $postUnixtime;
                                }
                                if($includeResult && !empty($until)) {
                                    $includeResult = $until > $postUnixtime;
                                }
                                if(!$includeResult) {
                                    unset($cachedResults[$userId]['data'][$key]);
                                }
                            }
                        }
                        $result['data'] = array_slice($cachedResults[$userId]['data'], 0, $limit, true);
                    }
                } else {
                    throw new \Exception("Empty instagram store or json_store in Instagram getCachedFeed function");
                }
            } catch (\Exception $e) {
                // DO NOTHING (exclude data with errors)
            }
        } else {
            throw new \Exception("Empty userId and/or limit provided in Instagram getCachedFeed function");
        }


        return $result;
    }

    public function getFeedWithSearchKey($accessToken, $userId, $limitResults = 6, $searchKeys = [], $since = null, $until = null, $limitPagesSearch = 3, $usermediaFields = "IMAGE,VIDEO,CAROUSEL_ALBUM", $mediaFields = "caption,id,media_type,media_url,permalink,thumbnail_url,timestamp,username") {
        $result = [];

        if (!empty($userId) && !empty($accessToken) && !empty($limitResults) && !empty($searchKeys) && is_array($searchKeys)) {
            try {
                $feedPerPage = $this->getFeed($accessToken, $userId, 30, $since, $until, $usermediaFields, $mediaFields);

                for($pagesSearched = 1; $pagesSearched <= $limitPagesSearch; $pagesSearched++) {
                    if (array_key_exists('data', $feedPerPage) && is_array($feedPerPage['data'])) {
                        $data = $feedPerPage['data'];
                        if (!empty($data) && is_array($data)) {
                            foreach ($data as $post) {
                                $includeResult = false;
                                foreach ($searchKeys as $key) {
                                    if ((array_key_exists('caption', $post)) && stripos($post['caption'], $key) !== false) {
                                        $includeResult = true;
                                    }
                                }
                                if ($includeResult) {
                                    $result[] = $post;
                                    if (count($result) === $limitResults) {
                                        return ["data" => $result];
                                    }
                                }
                            }
                        }
                    }
                    if ($pagesSearched !== $limitPagesSearch) {
                        $feedPerPage = $this->getNextPage($feedPerPage);
                    }
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get instagram public feed by id with search key: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty id, accessToken, limitResults, searchKey provided in Instagram getFeedWithSearchKey function");
        }

        return ["data" => $result];
    }

    public function getCachedFeedWithSearchKey($userId, $limitResults = 6, $searchKeys = [], $since = null, $until = null) {
        $result = [];

        if (!empty($userId) && !empty($limitResults) && !empty($searchKeys) && is_array($searchKeys)) {
            $instagramStore = SocialwallInstagramStore::findOne(['id' => 1]);
            try {
                if(!empty($instagramStore) && !empty($instagramStore->json_store)) {
                    $cachedResults = json_decode($instagramStore->json_store, true);
                    if(array_key_exists($userId, $cachedResults) && is_array($cachedResults[$userId])) {
                        if (array_key_exists('data', $cachedResults[$userId]) && is_array($cachedResults[$userId]['data'])) {
                            $data = $cachedResults[$userId]['data'];
                            if (!empty($data) && is_array($data)) {
                                foreach ($data as $post) {
                                    $includeResult = false;
                                    foreach ($searchKeys as $key) {
                                        if ((array_key_exists('caption', $post)) && stripos($post['caption'], $key) !== false) {
                                            $includeResult = true;
                                        }
                                    }
                                    if($includeResult && (!empty($since) || !empty($until))) {
                                        $postUnixtime = strtotime($post['timestamp']);
                                        $includeResult = false;
                                        if(!empty($since)) {
                                            $includeResult = $since < $postUnixtime;
                                        }
                                        if($includeResult && !empty($until)) {
                                            $includeResult = $until > $postUnixtime;
                                        }
                                    }
                                    if ($includeResult) {
                                        $result[] = $post;
                                        if (count($result) === $limitResults) {
                                            return ["data" => $result];
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    throw new \Exception("Empty instagram store or json_store in Instagram getCachedFeedWithSearchKey function");
                }
            } catch (\Exception $e) {
                // DO NOTHING (exclude data with errors)
            }
        } else {
            throw new \Exception("Empty id, accessToken, limitResults, searchKey provided in Instagram getCachedFeedWithSearchKey function");
        }

        return ["data" => $result];
    }

    public function getUserMedia($accessToken, $userId, $since = null, $until = null, $fields = "IMAGE,VIDEO,CAROUSEL_ALBUM") {
        $result = null;

        if (!empty($accessToken) && !empty($userId) && !empty($fields)) {
            $client = $this->createNewClient();

            $data = [
                "access_token" => $accessToken,
                "fields" => $fields,
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
                    ->setUrl("{$userId}/media")
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
                }
            } catch (\Exception $e) {
                throw new \Exception("Cannot get instagram get list of user media: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty accessToken, userId and fields provided in Instagram getUserMedia function");
        }

        return $result;
    }

    public function getMedia($accessToken, $mediaId, $fields = "caption,id,media_type,media_url,permalink,thumbnail_url,timestamp,username") {
        $result = null;

        if (!empty($accessToken) && !empty($mediaId) && !empty($fields)) {
            $client = $this->createNewClient(self::API_GRAPH_URL);

            try {
                $response = $client
                    ->createRequest()
                    ->setUrl($mediaId)
                    ->setMethod('GET')
                    ->setData([
                        "access_token" => $accessToken,
                        "fields" => $fields,
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
                throw new \Exception("Cannot get instagram get media: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty accessToken, mediaId and fields provided in Facebook getMedia function");
        }

        return $result;
    }

    /**
     * Returns next page from paginated feed
     *
     * @param $feedResult
     * @return array|mixed|null
     * @throws \Exception
     */
    public function getNextPage($feedResult) {
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
    public function getPreviousPage($feedResult) {
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
                throw new \Exception("Cannot get instagram url: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Empty url provided in Instagram getUrl function");
        }

        return $result;
    }

    public function cacheFeed($socialwallTokensId, $pages = 6, $since = null, $until = null, $usermediaFields = "IMAGE,VIDEO,CAROUSEL_ALBUM", $mediaFields = "caption,id,media_type,media_url,permalink,thumbnail_url,timestamp,username") {
        $results = [];

        $socialwallTokensModel = SocialwallTokens::findOne(['id' => $socialwallTokensId, 'type' => Social::INSTAGRAM]);
        if(!empty($socialwallTokensModel)) {
            try {
                $socialwallTokens = Json::decode($socialwallTokensModel->data);

                foreach ($socialwallTokens["accounts"] as $account) {
                    $fetchedRes = $this->getUserMedia($account["access_token"], $account['id'], $since, $until, $usermediaFields);

                    if(!empty($fetchedRes) && is_array($fetchedRes)) {
                        for ($page = 1; $page <= $pages; $page++) {
                            if (!empty($fetchedRes) && is_array($fetchedRes)) {
                                foreach ($fetchedRes['data'] as $mediaId) {
                                    if (!empty($mediaId['id'])) {
                                        try {
                                            $results[$account['id']]['data'][$mediaId['id']] = $this->getMedia($account["access_token"], $mediaId['id'], $mediaFields);
                                        } catch (\Exception $e) {
                                            // DO NOTHING (exclude data with errors)
                                        }
                                    }
                                }

                                if (!empty($fetchedRes['paging'])) {
                                    try {
                                        $fetchedRes = $this->getNextPage($fetchedRes);
                                    } catch (\Exception $e) {
                                        // DO NOTHING (exclude data with errors)
                                    }
                                } else {
                                    return $results;
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // DO NOTHING
            }
        }

        $instagramStore = SocialwallInstagramStore::findOne(['id' => 1]);
        if(empty($instagramStore)) {
            $instagramStore = new SocialwallInstagramStore();
            $instagramStore->id = 1;

            $instagramStore->json_store = Social::jsonEncodeData($results);
        } else {
            $storedResults = json_decode($instagramStore->json_store, true);

            // Override stored results with new data found for each user id found
            // (avoid overriding data of users not found during a caching session)
            foreach ($results as $userId => $data) {
                $storedResults[$userId] = $data;
            }

            $instagramStore->json_store = Social::jsonEncodeData($storedResults);
        }

        $instagramStore->save(false);
    }

    /**
     * 
     * @param string $id
     * @return array
     */
//    public function getMedia($id) {
//        $result = [];
//        $model = SocialwallInstagramToken::find(array('order' => 'id ASC'))->one();
//        if (!is_null($model)) {
//            $data = [
//                'access_token' => $model->long_time_token,
//                'fields' => 'caption, media_url, media_type, permalink, thumbnail_url, timestamp, username'
//            ];
//            $client = new Client(['baseUrl' => self::API_GRAPH_URL]);
//            $response = $client->createRequest()
//                    ->setUrl($id)
//                    ->setMethod('GET')
//                    ->setData($data)
//                    ->send();
//
//            if ($response->getStatusCode() == '200') {
//                $arrayRet = Json::decode($response->getContent(), true);
//                if ($this->isOkStatus($arrayRet)) {
//                    $result = $arrayRet;
//                }
//            } else {
//                Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
//            }
//        }
//        return $result;
//    }

    /**
     * 
     * @param string $since
     * @param string $until
     * @return array
     */
    public function searchMedia($since = null, $until = null) {
        $result = [];
        $model = SocialwallInstagramToken::find(array('order' => 'id ASC'))->one();
        if (!is_null($model)) {
            $data = [
                'access_token' => $model->long_time_token,
            ];

            if (!is_null($since)) {
                $data['since'] = $since;
            }

            if (!is_null($until)) {
                $data['until'] = $until;
            }

            $client = new Client(['baseUrl' => self::API_GRAPH_URL]);
            $response = $client->createRequest()
                    ->setUrl($model->user_id . '/media')->setMethod('GET')
                    ->setData($data)
                    ->send();

            if ($response->getStatusCode() == '200') {
                $list = Json::decode($response->getContent(), true);
                if ($this->isOkStatus($list)) {
                    foreach ($list['data'] as $item) {
                        $media = $this->getMedia($item['id']);
                        $result[] = $media;
                    }
                    $store = $this->getInstagramJson();
                    $store->json_store = Json::encode($result);
                    $store->save(false);
                }
            } else {
                Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
            }
        }
        return $result;
    }

    /**
     * 
     * @param array $tags
     * @return array
     */
    public function searchMediaJson($tags = []) {
        $result = [];
        $model = $this->getInstagramJson();
        if (!is_null($model)) {
            $isTags = count($tags);
            if (!empty($model->json_store)) {
                $list = Json::decode($model->json_store, true);

                foreach ($list as $item) {
                    if ($isTags) {
                        if ($this->filterByTags($item, $tags)) {
                            $result[] = $item;
                        }
                    } else {
                        $result[] = $item;
                    }
                }
            }
        }
        return $result;
    }

    public function getInstagramJson() {
        $store = SocialwallInstagramStore::find(array('order' => 'id ASC'))->one();
        if (is_null($store)) {
            $store = new SocialwallInstagramStore();
        }
        return $store;
    }

    /**
     * 
     * @param array $media
     * @param array $tags
     * @return boolean
     */
    protected function filterByTags($media, $tags) {
        $ret = false;
        if (!empty($media['caption'])) {
            foreach ($tags as $tag) {
                if (strstr($media['caption'], $tag)) {
                    $ret = true;
                    break;
                }
            }
        }
        return $ret;
    }

}
