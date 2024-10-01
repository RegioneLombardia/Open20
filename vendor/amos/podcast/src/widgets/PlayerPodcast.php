<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 14/10/2020
 * Time: 16:00
 */

namespace amos\podcast\widgets;


use amos\podcast\models\PodcastEpisode;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class PlayerPodcast extends Widget
{
    public $model;
    public $player_id = PodcastEpisode::PLAYER_SPREAKER;
    public $usePersonalizedCover = false;
    public $pluginOptions = [];

    public function init()
    {
        parent::init();
        if (empty($this->model)) {
            throw new InvalidConfigException("The parameter 'model' is mandatory");
        }
    }

    public function run()
    {
        switch ($this->player_id) {
            case PodcastEpisode::PLAYER_SPREAKER;
                $player = $this->getPlayerSpreaker();
                break;
        }
        return $player;
    }

    /**
     *
     */
    public function getPlayerSpreaker()
    {
        $url = '';
        $found_v1 = strpos($this->model->url_spreaker, "https://www.spreaker.com/episode/");
        if ($found_v1 >= 0) {
            $explode = explode("https://www.spreaker.com/episode/", $this->model->url_spreaker);
            if (count($explode) == 2) {
                $episode_id = $explode[1];
            }
        }
        if (empty($episode_id)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.spreaker.com/oembed?url=' . $this->model->url_spreaker);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $decodedOutput = json_decode($output);

            $found = preg_match('/episode_id=([0-9]+)/', $decodedOutput->html, $matches);
            if ($found) {
                $episode_id = $matches[1];
                $url = str_replace('t_widget_player_cover_medium/', '', $decodedOutput->thumbnail_url);
                $url = urlencode($url);
            }

            $foundShow = preg_match('/show_id=([0-9]+)/', $decodedOutput->html, $matches);
            if ($foundShow) {
                $show_id = $matches[1];
                $url = str_replace('t_widget_player_cover_medium/', '', $decodedOutput->thumbnail_url);
                $url = urlencode($url);
            }

            $enableCover = 'true';
            if ($this->usePersonalizedCover) {
                if ($this->model->mainImage) {
                    $url = \Yii::$app->params['platform']['backendUrl'] . $this->model->mainImage->getWebUrl();
                }
            }
        }

//        $url = 'https://backend.openinnovationlombardia.it/attachments/file/view?hash=69d83aee7ed7273fa137468537d90b0e';
        return $this->render('spreaker', [
            'model' => $this->model,
            'episode_id' => $episode_id,
            'show_id' => $show_id,
            'url' => $url,
            'enableCover' => $enableCover,
            'pluginOptions' => $this->pluginOptions
        ]);
    }
}