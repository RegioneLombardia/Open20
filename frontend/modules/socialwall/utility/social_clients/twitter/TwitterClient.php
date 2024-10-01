<?php

namespace frontend\modules\socialwall\utility\social_clients\twitter;

use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\log\Logger;

class TwitterClient {

    private $apiUrl = "https://api.twitter.com";
    private $users = [];

    public function getApiUrl() {
        return $this->apiUrl;
    }

    public function isOkStatus($result) {
        $ret = false;
        if (!is_null($result)) {
            $ret = true;
        }
        return $ret;
    }

    public function searchMedia($since = null, $until = null, $tags = []) {
        $result = [];
        $query = "";
        $headers = ["Authorization" => "Bearer " . Yii::$app->params['twitter']['token']];
        if (!is_null($since)) {
            $data['start_time'] = date(DATE_ISO8601, $since);
        }

        if (!is_null($until)) {
            $data['end_time'] = date(DATE_ISO8601, $until);
        }
        if (count($tags)) {
            $query .= "(";
            $i = 0;
            foreach ($tags as $tag) {
                if ($i++) {
                    $query .= "OR ";
                }
                $query .= $tag . " ";
            }
            $query .= ") ";
        }

        $data = [
            'query' => $query .  Yii::$app->params['twitter']['query'],
            'tweet.fields' => 'author_id,text,created_at,entities,attachments,in_reply_to_user_id,public_metrics',
            'expansions' => 'attachments.media_keys,author_id',
            'max_results' => Yii::$app->params['twitter']['max_results'],
            'media.fields' => 'preview_image_url',
            'user.fields' => 'created_at,description,entities,id,location,name,pinned_tweet_id,profile_image_url,protected,public_metrics,url,username,verified,withheld',
        ];

        $client = new Client(['baseUrl' => $this->apiUrl]);
        $response = $client->createRequest()
                ->addHeaders($headers)
                ->setUrl("/2/tweets/search/recent")->setMethod('GET')
                ->setData($data)
                ->send();

        if ($response->getStatusCode() == '200') {
            $list = Json::decode($response->getContent(), true);
            if ($this->isOkStatus($list)) {
                $this->storeUsers($list['includes']['users']);
                foreach ($list['data'] as $item) {
                    $item['userinfo'] = $this->getStoreUser($item['author_id']);
                    if (isset($item['entities']['hashtags'])) {
                        foreach ($item['entities']['hashtags'] as $tag) {
                            $link = "<a href=\"https://twitter.com/hashtag/" . $tag['tag'] . "?src=hashtag_click\" target=\"_blank\">#" . $tag['tag'] . "</a>&#160;";
                            $item['text'] = str_replace("#" . $tag['tag'],$link,$item['text']);
                        }
                    }
                    if (isset($item['entities']['urls'])) {
                        foreach ($this->unique_multidim_array($item['entities']['urls'], "url") as $url) {
                            $link = "<a href=\"" . $url['url'] . "\" target=\"_blank\">" . $url['url'] . "</a>&#160;";
                            $item['text'] = str_replace($url['url'],$link,$item['text']);
                        }
                    }
                    $result[] = $item;
                }
            }
        } else {
            Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * 
     * @param array $array
     * @param string $key
     * @return array
     */
    private function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    /**
     * 
     * @param string $id
     * @return array
     */
    private function getStoreUser($id) {
        if (isset($this->users[$id])) {
            return $this->users[$id];
        }
        return [];
    }

    /**
     * 
     * @param array $users
     */
    private function storeUsers($users) {
        foreach ($users as $user) {
            $this->users[$user['id']] = $user;
        }
    }

    /**
     * 
     * @param string $id
     * @return array
     */
    public function getUser($id) {
        $result = [];

        $headers = ["Authorization" => "Bearer " . Yii::$app->params['tweeter']['token']];
        $data = [
            'user.fields' => 'created_at,description,entities,id,location,name,pinned_tweet_id,profile_image_url,protected,public_metrics,url,username,verified,withheld',
        ];

        $client = new Client(['baseUrl' => $this->apiUrl]);
        $response = $client->createRequest()
                ->addHeaders($headers)
                ->setUrl("/2/users/" . $id)->setMethod('GET')
                ->setData($data)
                ->send();

        if ($response->getStatusCode() == '200') {
            $user = Json::decode($response->getContent(), true);
            if ($this->isOkStatus($user)) {
                $result = $user;
            }
        } else {
            Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
        }
        return $result;
    }

}
