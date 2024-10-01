<?php

namespace open20\socialwall\utility\social_clients\twitter;

use open20\socialwall\models\SocialwallTokens;
use open20\socialwall\utility\Social;
use Yii;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\httpclient\Client;
use yii\log\Logger;

class TwitterClient {

    const API_URL = "https://api.twitter.com";
    private $users = [];

    public static function getQuery($socialwallTokensId) {
        $query = "";

        if(!empty($socialwallTokensId)) {
            $socialwallTokens = SocialwallTokens::findOne(['id' => $socialwallTokensId]);
            if(!empty($socialwallTokens)) {
                $query = "from:" . $socialwallTokens->twitter_username;
            }
        }

        return $query;
    }

    public static function getToken($socialwallTokensId) {
        $token = "";

        $socialwallTokens = SocialwallTokens::findOne(['id' => $socialwallTokensId]);
        if(!empty($socialwallTokens)) {
            $token = $socialwallTokens->twitter_token;
        }

        return $token;
    }

    public function createNewClient($baseUrl = null)
    {
        return new Client(['baseUrl' => empty($baseUrl) ? self::API_URL : $baseUrl]);
    }

    public function isOkStatus($result) {
        $ret = false;
        if (!is_null($result)) {
            $ret = true;
        }
        return $ret;
    }

    public function searchMedia($since = null, $until = null, $tags = [], $socialwallTokensId = null, $maxResults = null) {
        if(empty($maxResults) && !is_numeric($maxResults)) {
            $maxResults = Yii::$app->params['twitter']['max_results'];
        }
        $result = [];
        $query = "";
        $data = [];
        $headers = ["Authorization" => "Bearer " . self::getToken($socialwallTokensId)];
        if (!empty($since)) {
            $data['start_time'] = date(DATE_ATOM, $since);
        }

        if (!empty($until)) {
            $data['end_time'] = date(DATE_ATOM, $until);
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

        $data = $data + [
            'query' => $query . " " .  self::getQuery($socialwallTokensId),
            'tweet.fields' => 'author_id,text,created_at,entities,attachments,in_reply_to_user_id,public_metrics',
            'expansions' => 'attachments.media_keys,author_id',
            'max_results' => $maxResults,
            'media.fields' => 'preview_image_url,url',
            'user.fields' => 'created_at,description,entities,id,location,name,pinned_tweet_id,profile_image_url,protected,public_metrics,url,username,verified,withheld',
        ];

        $client = $this->createNewClient();

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
                    if(array_key_exists('includes', $list)) {
                        $item['mediaUrls'] = $list['includes'];
                    }
                    $result[] = $item;
                }
            }
        } else {
            Yii::getLogger()->log(json_encode($response->getContent()), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    public function getFeedForSocialwallTokensId($socialwallTokensId, $searchKeys = [], $feedLimit = 30, $getFormattedFeed = false, $since = null, $until = null) {
        $result = [];

        if(!empty($socialwallTokensId) && !empty($feedLimit)) {
            $socialwallTokensModel = SocialwallTokens::findOne(['id' => $socialwallTokensId, 'type' => Social::TWITTER]);
            if(!empty($socialwallTokensModel)) {
                try {
                    $socialwallTokens = Json::decode($socialwallTokensModel->data);

                    if(array_key_exists('username', $socialwallTokens) && array_key_exists('token', $socialwallTokens) &&
                        !empty($socialwallTokens['username']) && !empty($socialwallTokens['token'])) {
                        if(!$getFormattedFeed) {
                            $feed = $this->searchMedia($since, $until, $searchKeys, $socialwallTokensId, $feedLimit);
                            if(!empty($feed)) {
                                foreach($feed as $post) {
                                    if(!array_key_exists($post['author_id'], $result)) {
                                        $result[$post['author_id']] = [
                                            "id" => $post['author_id'],
                                            "name" => $post['userinfo']['name'],
                                            "profile_picture" => str_replace("_normal.jpg", ".jpg", $post['userinfo']['profile_image_url']),
                                            "feed" => [],
                                        ];
                                    }
                                    $result[$post['author_id']]["feed"][] = $post;
                                }
                            }
                        } else {
                            $feed = $this->searchMedia($since, $until, $searchKeys, $socialwallTokensId, $feedLimit);
                            if(!empty($feed)) {
                                foreach($feed as $post) {
                                    $relevantPostData = $this->getRelevantPostData($post);
                                    $result[$post['author_id']][] = Social::formatPost(
                                        Social::TWITTER,
                                        $post['userinfo']['name'],
                                        str_replace("_normal.jpg", ".jpg", $post['userinfo']['profile_image_url']),
                                        $post['text'],
                                        $this->getPostImage($post),
                                        "https://twitter.com/{$post['userinfo']['username']}/status/{$post['id']}",
                                        $post['created_at'],
                                        "https://twitter.com/{$post['userinfo']['username']}"
                                    );
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

    public function getPostImage($post) {
        if(array_key_exists('attachments', $post) && array_key_exists('media_keys', $post['attachments']) && is_array($post['attachments']['media_keys'])) {
            $keyToSearch = $post['attachments']['media_keys'][0];
            if(array_key_exists('mediaUrls', $post) && array_key_exists('media', $post['mediaUrls'])) {
                foreach ($post['mediaUrls']['media'] as $entity) {
                    if(array_key_exists('media_key', $entity) && $entity['media_key'] == $keyToSearch && array_key_exists("url", $entity)) {
                        return $entity['url'];
                    }
                }
            }
        }
        return null;
    }

    public function getRelevantPostData($post) {
        $result = [];

        if(!empty($post['text'])) {
            // GET MESSAGE (MUST BE SET - IF IT DOES NOT EXIST, POST WILL BE EMPTY)
            $result['postText'] = $post['text'];
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

        $headers = ["Authorization" => "Bearer " . self::getToken()];
        $data = [
            'user.fields' => 'created_at,description,entities,id,location,name,pinned_tweet_id,profile_image_url,protected,public_metrics,url,username,verified,withheld',
        ];

        $client = $this->createNewClient();

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
