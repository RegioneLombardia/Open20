<?php

namespace open20\socialwall\utility;

use open20\socialwall\models\Socialwall;
use open20\socialwall\models\SocialwallLiveChoices;
use open20\socialwall\models\SocialwallLiveContents;
use open20\socialwall\models\SocialwallPosts;
use open20\socialwall\models\SocialwallPostsInSocialwall;
use open20\socialwall\models\SocialwallTokens;
use open20\socialwall\Module;
use open20\socialwall\utility\social_clients\facebook\FacebookClient;
use open20\socialwall\utility\social_clients\instagram\InstagramClient;
use open20\socialwall\utility\social_clients\twitter\TwitterClient;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;

class Social
{
    // SOCIAL CONSTS
    const FACEBOOK = "facebook";
    const INSTAGRAM = "instagram";
    const TWITTER = "twitter";

    // SOCIALWALL TYPES
    const LIVE_WALL = 'livewall';
    const PICKED_POSTS_WALL = 'pickedposts';

    // CONTEXTS FOR socialwall_posts_search TABLE/MODEL
    const CONTEXT_POSTS_SEARCH = 'posts-search';
    const CONTEXT_LIVE_PREVIEW = 'live-preview';

    const SOCIAL_TYPES = [
        self::INSTAGRAM => '1',
        self::TWITTER => '2',
        self::FACEBOOK => '3',
    ];

    const SOCIAL_LABELS = [
        self::INSTAGRAM => 'Instagram',
        self::TWITTER => 'Twitter',
        self::FACEBOOK => 'Facebook',
    ];

    public static function enabledSocialwallTypesList() {
        return \Yii::$app->getModule('socialwall')->enabledSocialwallTypes;
    }

    public static function enabledSocialsList() {
        return \Yii::$app->getModule('socialwall')->enabledSocials;
    }

    public static function getTypeCode($type) {
        return self::SOCIAL_TYPES[$type];
    }

    public static function getSocialwallTokensEnabled() {
        return ArrayHelper::map(SocialwallTokens::find()->asArray()->all(), 'id', 'name');
    }

    public static function getSocialsEnabled() {
        $socials = ArrayHelper::map(SocialwallTokens::find()->groupBy('type')->asArray()->all(), 'type', 'type');
        foreach ($socials as $key => $social) {
            $socials[$key] = self::SOCIAL_LABELS[$social];
        }
        return $socials;
    }

    public static function facebookLoginUrlGenerator($socialwallTokensId = null, $secret = null) {
        return FacebookClient::mainApiUrl().'/dialog/oauth?' .
                http_build_query([
                    'client_id' => \Yii::$app->params['facebook']['client_id'],
                    'redirect_uri' => FacebookClient::getRedirectUri(),
                    'scope' => 'pages_show_list,pages_read_engagement',
                    'state' => (!empty($socialwallTokensId) && !empty($secret))
                                ? "{\"socialwallTokensId\":\"{$socialwallTokensId}\",\"secret\":\"{$secret}\"}"
                                : "",
                ]);
    }

    public static function instagramLoginUrlGenerator($socialwallTokensId = null, $secret = null) {
        return InstagramClient::mainApiUrl().'/oauth/authorize?' .
            http_build_query([
                'client_id' => \Yii::$app->params['instagram']['client_id'],
                'redirect_uri' => InstagramClient::getRedirectUri(),
                'scope' => 'user_profile,user_media',
                'response_type' => 'code',
                'state' => (!empty($socialwallTokensId) && !empty($secret))
                    ? "{\"socialwallTokensId\":\"{$socialwallTokensId}\",\"secret\":\"{$secret}\"}"
                    : "",
            ]);
    }

    /**
     * This is a utility function that allows to create a "standard" structure for each post
     *
     * EACH POST WILL HAVE THE FOLLOWING STRUCTURE
     * [
     *  'name' => '<ACCOUNT NAME TO DISPLAY>',
     *  'profile_picture_url' => '<PROFILE PICTURE URL>',
     *  'post_text' => '<POST TEXT\MESSAGE>',
     *  'post_image_url' => '<POST IMAGE URL>',
     *  'permalink' => '<PERMALINK URL>',
     *  'post_datetime' => '<POST TIME>',
     *  'profile_url' => '<PROFILE URL>',
     *  'media_type' => '<MEDIA TYPE> (may be null)'
     * ]
     *
     * @return array
     */
    public static function formatPost($type, $name, $profilePictureUrl, $postText, $postImageUrl, $permalink, $postDatetime, $profileUrl, $mediaType = null) {
        return [
            'type' => $type,
            'name' => $name,
            'profile_picture_url' => $profilePictureUrl,
            'post_text' => $postText,
            'post_image_url' => $postImageUrl,
            'permalink' => $permalink,
            'post_datetime' => strtotime($postDatetime),
            'post_nice_datetime' => \Yii::$app->formatter->asDatetime(
                strtotime($postDatetime),
                isset(
                    \Yii::$app->components['formatter']['datetimeFormat']) ?
                    \Yii::$app->components['formatter']['datetimeFormat'] :
                    'd-m-Y H:i:s'),
            'profile_url' => $profileUrl,
            'media_type' => $mediaType,
        ];
    }

    /**
     * Ordered formatted posts (since and until params will be converted to dates recognized in a format allowed by
     * respective apis)
     *
     * @return array
     */
    public static function getOrderedPosts($socialwallTokensId = [], $searchKeys = [], $feedLimit = 30, $since = null, $until = null) {
        $orderedPosts = [];

        if(!empty($socialwallTokensId) && is_array($socialwallTokensId)) {
            foreach ($socialwallTokensId as $socialwallTokenId) {
                $socialwallToken = SocialwallTokens::findOne(['id' => $socialwallTokenId]);
                if(!empty($socialwallToken)) {
                    switch ($socialwallToken->type) {
                        case self::FACEBOOK:
                            // GET FACEBOOK FEED
                            $facebookClient = new FacebookClient();
                            $facebookPosts = $facebookClient->getFeedForSocialwallTokensId($socialwallTokenId, $searchKeys, $feedLimit, true, (!empty($since) ? strtotime($since) : null), (!empty($until) ? strtotime($until) : null));

                            if(!empty($facebookPosts) && is_array($facebookPosts)) {
                                foreach($facebookPosts as $postList) {
                                    if(!empty($postList) && is_array($postList)) {
                                        foreach($postList as $post) {
                                            $orderedPosts[] = $post;
                                        }
                                    }
                                }
                            }
                            break;
                        case self::INSTAGRAM:
                            // GET INSTAGRAM FEED
                            $instagramClient = new InstagramClient();
                            $instagramPosts = $instagramClient->getFeedForSocialwallTokensId($socialwallTokenId, $searchKeys, $feedLimit,true, true, (!empty($since) ? strtotime($since) : null), (!empty($until) ? strtotime($until) : null));

                            if(!empty($instagramPosts) && is_array($instagramPosts)) {
                                foreach($instagramPosts as $postList) {
                                    if(!empty($postList) && is_array($postList)) {
                                        foreach($postList as $post) {
                                            $orderedPosts[] = $post;
                                        }
                                    }
                                }
                            }
                            break;
                        case self::TWITTER:
                            // GET TWITTER FEED
                            $twitterClient = new TwitterClient();
                            $twitterPosts = $twitterClient->getFeedForSocialwallTokensId($socialwallTokenId, $searchKeys, $feedLimit, true, (!empty($since) ? strtotime($since) : null), (!empty($until) ? strtotime($until) : null));

                            if(!empty($twitterPosts) && is_array($twitterPosts)) {
                                foreach($twitterPosts as $postList) {
                                    if(!empty($postList) && is_array($postList)) {
                                        foreach($postList as $post) {
                                            $orderedPosts[] = $post;
                                        }
                                    }
                                }
                            }
                            break;
                    }
                }
            }

            if(!empty($orderedPosts)) {
                // ORDER DESC BY post_datetime
                usort($orderedPosts, function ($a, $b) {
                    $aPDT = $a['post_datetime'];
                    $bPDT = $b['post_datetime'];

                    if($aPDT === $bPDT) {
                        return 0;
                    }
                    return ($aPDT < $bPDT) ? 1 : -1; // ORDER DESC
                });
            }
        } else {
            throw new \Exception("socialwallTokensId list is not an array or is empty in getOrderedPosts");
        }

        return $orderedPosts;
    }

    /**
     * Cache Instagram posts
     *
     * @param $socialwallTokensId socialwall_tokens.id of the instagram accounts to cache
     * @param $pagesToStore number of pages to cache (max 6 pages recommended, ~ 186 api calls to instagram apis)
     * @return true
     */
    public static function cacheInstagramPosts($socialwallTokensId, $pagesToStore = 6) {
        $instagramClient = new InstagramClient();
        $instagramClient->cacheFeed($socialwallTokensId, $pagesToStore);
        return true;
    }

    public static function postAssetToSocialObject($socialwallPostsId) {
        $socialwallPosts = SocialwallPosts::findOne(['id' => $socialwallPostsId]);
        if(!empty($socialwallPosts)) {
            return self::formatPost(
                $socialwallPosts->type,
                $socialwallPosts->profile_name,
                $socialwallPosts->getProfilePicture()->getWebUrl(),
                $socialwallPosts->text,
                $socialwallPosts->getPostImage()->getWebUrl(),
                $socialwallPosts->permalink,
                \Yii::$app->formatter->asDate(
                    strtotime($socialwallPosts->publication_date),
                    isset(
                        \Yii::$app->components['formatter']['datetimeFormat']) ?
                        \Yii::$app->components['formatter']['datetimeFormat'] :
                        'd-m-Y H:i:s'),
                $socialwallPosts->profile_url,
                $socialwallPosts->media_type
            );
        }
        return [];
    }

    public static function jsonEncodeData($arrayData) {
        return json_encode($arrayData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    public static function jsonDecodeData($json) {
        return json_decode($json, true);
    }

    public static function encodedStringForDb($string) {
//        return self::encodeString(nl2br($string));
        return nl2br(self::encodeString($string));
    }

    public static function encodeString( $string ) {
        $stringBuilder = "";
        $offset = 0;

        if ( empty( $string ) ) {
            return "";
        }

        while ( $offset >= 0 ) {
            $decValue = self::ordutf8( $string, $offset );
            $char = self::unichr($decValue);

            $htmlEntited = htmlentities( $char );
            if( $char != $htmlEntited ){
                $stringBuilder .= $htmlEntited;
            } elseif( $decValue >= 128 ){
                $stringBuilder .= "&#" . $decValue . ";";
            } else {
                $stringBuilder .= $char;
            }
        }

        return $stringBuilder;
    }

// source - http://php.net/manual/en/function.ord.php#109812
    public static function ordutf8($string, &$offset) {
        $code = ord(substr($string, $offset,1));
        if ($code >= 128) {        //otherwise 0xxxxxxx
            if ($code < 224) $bytesnumber = 2;                //110xxxxx
            else if ($code < 240) $bytesnumber = 3;        //1110xxxx
            else if ($code < 248) $bytesnumber = 4;    //11110xxx
            $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesnumber; $i++) {
                $offset ++;
                $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
                $codetemp = $codetemp*64 + $code2;
            }
            $code = $codetemp;
        }
        $offset += 1;
        if ($offset >= strlen($string)) $offset = -1;
        return $code;
    }

// source - http://php.net/manual/en/function.chr.php#88611
    public static function unichr($u) {
        return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
    }

    /* ---- */

    public static function jsLoadMoreContents($showPostCheckboxes, $loadMoreContentsType, $socialwallModelId, $postsToLoad, $postsContainerId) {
        $typeFacebook = \open20\socialwall\utility\Social::FACEBOOK;
        $typeInstagram = \open20\socialwall\utility\Social::INSTAGRAM;
        $typeTwitter = \open20\socialwall\utility\Social::TWITTER;

        $socialTemplates = [
            $typeFacebook => \Yii::$app->view->render('@vendor/open20/social-wall/src/widgets/views/social-cards/facebook_detail', ['showPostCheckboxes' => $showPostCheckboxes]),
            $typeInstagram => \Yii::$app->view->render('@vendor/open20/social-wall/src/widgets/views/social-cards/instagram_detail', ['showPostCheckboxes' => $showPostCheckboxes]),
            $typeTwitter => \Yii::$app->view->render('@vendor/open20/social-wall/src/widgets/views/social-cards/twitter_detail', ['showPostCheckboxes' => $showPostCheckboxes]),
        ];

        $showPostCheckboxesText = ($showPostCheckboxes ? "true" : "false");

        return <<<JS
loadMoreContents = function (button) {
  $(button).attr("disabled", "disabled");
  $(button).addClass("disabled");
  $.ajax({
    timeout: 8000,
    url: '/socialwall/socialwall/load-more-contents',
    method: 'GET',
    data: {'type':'{$loadMoreContentsType}','contentsId':{$socialwallModelId},'offset':$(button).attr('data-elements-shown'),length:{$postsToLoad}}
  }).done(function (res) {
    res = JSON.parse(res);
    const postContainer = $("#{$postsContainerId}");

    for(let post in res) {
      let key = post;
      post = res[post];
      
      if(typeof post === 'object' &&
            !Array.isArray(post) &&
            post !== null) {
        
          let {
              type,
              name,
              profile_picture_url,
              post_text,
              post_image_url,
              post_nice_datetime,
              permalink,
              profile_url,
              media_type,
          } = post;
          
          let showPostCheckboxes = {$showPostCheckboxesText};
          
          switch (type) {
            case "{$typeFacebook}":
              postContainer.append(`{$socialTemplates[$typeFacebook]}`);
              break;
            case "{$typeInstagram}":
              postContainer.append(`{$socialTemplates[$typeInstagram]}`);
              break;
            case "{$typeTwitter}":
              postContainer.append(`{$socialTemplates[$typeTwitter]}`);
              break;
          }
      }
    }
    
    if(res['has_more_results']) {
      $(button).attr('data-elements-shown', parseInt($(button).attr('data-elements-shown'))+{$postsToLoad});
    } else {
      $(button).remove();
    }
  })
  .always(function () {
    $(button).attr("disabled", false);
    $(button).removeClass("disabled");
  });
}
JS;
    }

    public static function cacheAllTokensInstagramPosts() {
        $socialwallTokensIds = ArrayHelper::getColumn(SocialwallTokens::findAll(['type' => self::INSTAGRAM]), 'id');
        foreach($socialwallTokensIds as $socialwallTokensId) {
            Social::cacheInstagramPosts($socialwallTokensId);
        }
    }

    public static function updateSocialwallLiveContents($socialwallId, $forceModuleConfig = null) {
        $socialwall = Socialwall::findOne(['id' => $socialwallId]);
        if(!empty($socialwall) && $socialwall->type === Social::LIVE_WALL) {
            // if update enabled
            if($socialwall->update) {
                $linkedModel = null;
                // check if referring model exists
                $moduleConfig = null;
                if(!empty($forceModuleConfig) && is_array($forceModuleConfig)) {
                    $moduleConfig = (object) $forceModuleConfig;
                } else {
                    $moduleConfig = \Yii::$app->getModule(Module::getModuleName());
                }
                if(!empty($moduleConfig) &&
                !empty($moduleConfig->modulesIdClassMapEnable) &&
                is_array($moduleConfig->modulesIdClassMapEnable)) {
                    $search = array_search($socialwall->module_id, $moduleConfig->modulesIdClassMapEnable);
                    if(!empty($search)) {
                        if($search !== 'luya\cms\models\Nav') {
                            try {
                                $model = new $search();
                                $linkedModel = $model->findOne(['id' => $socialwall->module_record_id]);
                            } catch (\Exception $e) {
                                // do nothing
                            }
                        } else {
                            try {
                                $model = new $search();
                                $linkedModel = $model::findOne(['id' => $socialwall->module_record_id, 'is_deleted' => 0, 'is_offline' => 0]); // TODO RIMUOVERE is_offline => 0 SE SI VOGLIONO AGGIORNARE ANCHE I SOCIALWALL DI PAGINE OFFLINE
                            } catch (\Exception $e) {
                                // do nothing
                            }
                        }

                    }
                }

                // if referring model exists, update data content for selected socialwall
                if(!empty($linkedModel)) {
                    $selectedSocials = SocialwallLiveChoices::find()->andWhere(['socialwall_id' => $socialwall->id])->asArray()->all();
                    if(!empty($selectedSocials)) {
                        $selectedSocialsList = ArrayHelper::getColumn($selectedSocials, 'socialwall_tokens_id');
                        $posts = self::getOrderedPosts($selectedSocialsList, explode(",", $selectedSocials[0]['keywords']));

                        $socialwallLiveContents = SocialwallLiveContents::findOne(['socialwall_id' => $socialwall->id]);
                        if(!empty($socialwallLiveContents)) {
                            $socialwallLiveContents->json_data_content = self::jsonEncodeData($posts);
                            $socialwallLiveContents->save(false);
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function saveSocialwallSelection($moduleMappedId, $moduleMappedRecordId, $socialwallPostData) {
        if(!empty($socialwallPostData) && is_array($socialwallPostData) && array_key_exists('socialwallType', $socialwallPostData)) {
            // Socialwall Model
            $socialwall = Socialwall::findOne(['module_id' => $moduleMappedId, 'module_record_id' => $moduleMappedRecordId]);
            if(!empty($socialwallPostData['socialwallType'])) {
                // If not found, create a new record
                if(empty($socialwall)) {
                    $socialwall = new Socialwall();
                    $socialwall->module_id = $moduleMappedId;
                    $socialwall->module_record_id = $moduleMappedRecordId;
                }

                $socialwallType = $socialwallPostData['socialwallType'];
                $socialwall->type = $socialwallType;
                $socialwall->update = $socialwallPostData['update'];
                $socialwall->save(false);

                // IF livewall TYPE, save data
                if($socialwallType === Social::LIVE_WALL) {
                    // CHECK CURRENT AND PREVIOUS DATA TO AVOID FETCHING AND SAVING DATA AGAIN
                    $saveData = false;
                    $tags = $socialwallPostData['socialwallLiveTags'];
                    $oldChoices = SocialwallLiveChoices::findAll(['socialwall_id' => $socialwall->id]);

                    if(empty($oldChoices)) {
                        $saveData = true;
                    } else if (!array_key_exists('socialwallLiveChoices', $socialwallPostData) || empty($socialwallPostData['socialwallLiveChoices'])) {
                        // Remove all Socialwall live choices models
                        SocialwallLiveChoices::deleteAll(['socialwall_id' => $socialwall->id]);
                    } else {
                        if(count($oldChoices) !== count($socialwallPostData['socialwallLiveChoices'])) {
                            $saveData = true;
                        } else {
                            $oldChoicesCount = count($oldChoices);
                            foreach ($socialwallPostData['socialwallLiveChoices'] as $socialwallTokensId) {
                                $oldSelectedChoice = SocialwallLiveChoices::findOne(['socialwall_id' => $socialwall->id, 'socialwall_tokens_id' => $socialwallTokensId]);
                                if(empty($oldSelectedChoice)) {
                                    $saveData = true;
                                    break;
                                } else {
                                    $oldChoicesCount--;
                                    if($oldSelectedChoice->keywords !== $tags) {
                                        $saveData = true;
                                        break;
                                    }
                                }
                            }
                            if($oldChoicesCount > 0) {
                                $saveData = true;
                            }
                        }
                    }

                    if($saveData) {
                        // Remove all Socialwall live choices models
                        SocialwallLiveChoices::deleteAll(['socialwall_id' => $socialwall->id]);
                        if(!empty($tags)) {
                            // Create new choices
                            foreach($socialwallPostData['socialwallLiveChoices'] as $choice) {
                                $choiceModel = new SocialwallLiveChoices();
                                $choiceModel->socialwall_id = $socialwall->id;
                                $choiceModel->socialwall_tokens_id = $choice;
                                $choiceModel->keywords = $tags;
                                $choiceModel->save(false);
                            }
                            // Search new data and save (cache) them
                            $contentsModel = SocialwallLiveContents::findOne(['socialwall_id' => $socialwall->id]);
                            if(empty($contentsModel)) {
                                $contentsModel = new SocialwallLiveContents();
                                $contentsModel->socialwall_id = $socialwall->id;
                            }
                            // Save data content
                            $contentsModel->json_data_content = Social::jsonEncodeData(Social::getOrderedPosts($socialwallPostData['socialwallLiveChoices'], explode(",", $tags)));
                            $contentsModel->save(false);
                        }
                    }
                    // Remove unrelated data
                    self::removePickedPostsData($socialwall->id);
                }
                // IF pickedposts (assets social) TYPE, save data
                if($socialwallType === Social::PICKED_POSTS_WALL) {
                    self::removePickedPostsData($socialwall->id);

                    if(!empty($socialwallPostData['socialwallPickedPosts'])) {
                        $selectedPosts = explode(",", $socialwallPostData['socialwallPickedPosts']);
                        foreach ($selectedPosts as $selectedPost) {
                            $postToLink = new SocialwallPostsInSocialwall();
                            $postToLink->socialwall_id = $socialwall->id;
                            $postToLink->socialwall_posts_id = $selectedPost;
                            $postToLink->save(false);
                        }
                    }

                    // Remove unrelated data
                    self::removeLiveData($socialwall->id);
                }
            } else {
                if(!empty($socialwall)) {
                    self::removeAllSocialwallData($socialwall->id);
                }
            }
        }
    }

    public static function removeAllSocialwallData($socialwallModelId) {
        self::removeLiveData($socialwallModelId);
        self::removePickedPostsData($socialwallModelId);
        Socialwall::deleteAll(['id' => $socialwallModelId]);
    }

    public static function removeLiveData($socialwallModelId) {
        SocialwallLiveContents::deleteAll(['socialwall_id' => $socialwallModelId]);
        SocialwallLiveChoices::deleteAll(['socialwall_id' => $socialwallModelId]);
    }

    public static function removePickedPostsData($socialwallModelId) {
        SocialwallPostsInSocialwall::deleteAll(['socialwall_id' => $socialwallModelId]);
    }

}