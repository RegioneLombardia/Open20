<?php

namespace frontend\modules\socialwall\utility\social_clients\instagram;

use frontend\modules\socialwall\models\SocialwallInstagramStore;
use frontend\modules\socialwall\models\SocialwallInstagramToken;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\log\Logger;

class InstagramClient {

    private $apiUrl = "https://api.instagram.com";
    private $apiGraphUrl = "https://graph.instagram.com";
    private $access_token;
    private $client_id;
    private $client_secret;
    private $redirect_url;

    public function getApiUrl() {
        return $this->apiUrl;
    }

    public function getAccess_token() {
        return $this->access_token;
    }

    public function getClient_id() {
        return $this->client_id;
    }

    public function getClient_secret() {
        return $this->client_secret;
    }

    public function getRedirect_url() {
        return $this->redirect_url;
    }

    public function setApiUrl($apiUrl) {
        $this->apiUrl = $apiUrl;
    }

    public function setAccess_token($access_token) {
        $this->access_token = $access_token;
    }

    public function setClient_id($client_id) {
        $this->client_id = $client_id;
    }

    public function setClient_secret($client_secret) {
        $this->client_secret = $client_secret;
    }

    public function setRedirect_url($redirect_url) {
        $this->redirect_url = $redirect_url;
    }

    public function getApiGraphUrl() {
        return $this->apiGraphUrl;
    }

    public function setApiGraphUrl($apiGraphUrl) {
        $this->apiGraphUrl = $apiGraphUrl;
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
     * @return string
     */
    public function getAuthorizeUrl() {
        $url = $this->apiUrl . "/oauth/authorize";
        return Url::to([$url, "client_id" => $this->client_id, "redirect_uri" => $this->redirect_url
                    , "scope" => "user_profile,user_media", "response_type" => "code"], 'https');
    }

    /**
     * 
     * @return array
     */
    public function getShortAccessToken() {
        $result = [];
        $data = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect_url,
            'code' => $this->access_token
        ];
        $client = new Client(['baseUrl' => $this->apiUrl]);
        $response = $client->createRequest()
                ->setUrl('oauth/access_token')->setMethod('POST')
                ->setData($data)
                ->send();
        if ($response->getStatusCode() == '200') {
            $arrayRet = Json::decode($response->getContent(), true);
            if ($this->isOkStatus($arrayRet)) {
                $result = $arrayRet;
            }
        } else {
            Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * 
     * @param string $short_access_token
     * @return array
     */
    public function getLongAccessToken($short_access_token) {
        $result = [];
        $data = [
            'client_secret' => $this->client_secret,
            'grant_type' => 'ig_exchange_token',
            'access_token' => $short_access_token
        ];
        $client = new Client(['baseUrl' => $this->apiGraphUrl]);
        $response = $client->createRequest()
                ->setUrl('access_token')->setMethod('GET')
                ->setData($data)
                ->send();
        if ($response->getStatusCode() == '200') {
            $arrayRet = Json::decode($response->getContent(), true);
            if ($this->isOkStatus($arrayRet)) {
                $result = $arrayRet;
            }
        } else {
            Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * 
     * @param type $long_access_token
     * @return type
     */
    public function refreshLongAccessToken($long_access_token) {
        $result = [];
        $data = [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $long_access_token
        ];
        $client = new Client(['baseUrl' => $this->apiGraphUrl]);
        $response = $client->createRequest()
                ->setUrl('refresh_access_token')->setMethod('GET')
                ->setData($data)
                ->send();
        if ($response->getStatusCode() == '200') {
            $arrayRet = Json::decode($response->getContent(), true);
            if ($this->isOkStatus($arrayRet)) {
                $result = $arrayRet;
            }
        } else {
            Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * 
     * @param string $id
     * @return array
     */
    public function getMedia($id) {
        $result = [];
        $model = SocialwallInstagramToken::find(array('order' => 'id ASC'))->one();
        if (!is_null($model)) {
            $data = [
                'access_token' => $model->long_time_token,
                'fields' => 'caption, media_url, media_type, permalink, thumbnail_url, timestamp, username'
            ];
            $client = new Client(['baseUrl' => $this->apiGraphUrl]);
            $response = $client->createRequest()
                    ->setUrl($id)->setMethod('GET')
                    ->setData($data)
                    ->send();

            if ($response->getStatusCode() == '200') {
                $arrayRet = Json::decode($response->getContent(), true);
                if ($this->isOkStatus($arrayRet)) {
                    $result = $arrayRet;
                }
            } else {
                Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
            }
        }
        return $result;
    }

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

            $client = new Client(['baseUrl' => $this->apiGraphUrl]);
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
