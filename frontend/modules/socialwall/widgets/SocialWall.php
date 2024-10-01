<?php

namespace frontend\modules\socialwall\widgets;

use frontend\modules\socialwall\models\SocialwallContentStore;
use frontend\modules\socialwall\utility\social_clients\instagram\InstagramClient;
use frontend\modules\socialwall\utility\social_clients\twitter\TwitterClient;
use frontend\modules\socialwall\utility\SocialWallItem;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class SocialWall extends Widget
{

    private $instagram;
    private $twitter;
    private $tags;
    public $model;

    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    public function __construct() {
        $this->instagram = new InstagramClient();
        $this->twitter = new TwitterClient();
    }

    public function buildWall() {
        $list = $this->instagram->searchMediaJson($this->tags);
        $medias = [];
//        VarDumper::dump($list, 20, true);
        foreach ($list as $item) {
//            echo Json::encode($item);
//            echo "<br \>";
//            echo "<br \>";
            $media = new SocialWallItem(SocialWallItem::INSTAGRAM_TYPE, $item);
            $medias[] = $media;
        }

        $list = $this->twitter->searchMedia(null, null, $this->tags);
//        VarDumper::dump($list, 20, true);die;
        foreach ($list as $item) {
//            echo Json::encode($item);
//            echo "<br \>";
//            echo "<br \>";
            $media = new SocialWallItem(SocialWallItem::TWITTER_TYPE, $item);
            $medias[] = $media;
        }
//        die;
        usort($medias, function ($first, $second) {
            return $first->crTime() < $second->crTime();
        });
        return $medias;
    }

    public function buildStoredWall(String $moduleId, int $moduleRecordId) {
        $list = SocialwallContentStore::find()->andWhere(['module_id' => $moduleId])->andWhere(['module_record_id' => $moduleRecordId])->all();
        $medias = [];

        /** @var SocialwallContentStore $item */
        foreach ($list as $item) {
//            VarDumper::dump($item->json_data_content);echo "<br />";echo "<br />";
            $itemContent = JSON::decode($item->json_data_content);
            switch($item->social) {
                case SocialwallContentStore::SOCIAL_INSTAGRAM:
                    $media = new SocialWallItem(SocialWallItem::INSTAGRAM_TYPE, $itemContent);
                    $medias[] = $media;
                    break;
                case SocialwallContentStore::SOCIAL_TWITTER:
                    $media = new SocialWallItem(SocialWallItem::TWITTER_TYPE, $itemContent);
                    $medias[] = $media;
                    break;
            }
        }

        usort($medias, function ($first, $second) {
            return $first->crTime() < $second->crTime();
        });

        return $medias;
    }

    public function run()
    {
        if(empty($this->model)) {
            throw new InvalidConfigException("You must specify a model.");
        }

        // Search for mapped module between modulesIdClassMap array in components' module configuration,
        // if not found, throw error
        $moduleId = "";
        if(array_key_exists($this->model::className(), \Yii::$app->getModule('socialwall')->modulesIdClassMap)) {
            $moduleId = \Yii::$app->getModule('socialwall')->modulesIdClassMap[$this->model::className()];
        }

        if(empty($moduleId)) {
            throw new InvalidConfigException("You must map a valid moduleId in the socialwall component.");
        }

        $moduleRecordId = $this->model->id;

        $viewPath = '@frontend/modules/socialwall/widgets/views/';
        $viewToRender = $viewPath . 'list';
        $wall = new \frontend\modules\socialwall\widgets\SocialWall();
        $wall->setTags($this->tags);
        $medias = $wall->buildStoredWall($moduleId, $moduleRecordId);
//        $medias = $wall->buildWall();

        return $this->render('social-wall', [
            'tags' => $this->tags,
            'viewToRender' => $viewToRender,
            'widget' => $this,
            'medias' => $medias,
            'viewPath' => $viewPath,
        ]);
    }

}