<?php

namespace amos\sitemanagement\utility;

use amos\sitemanagement\models\SiteManagementSliderElem;

class EmbeddedUtility  {
    /**
     *  Method to get embedded Youtube url video
     * @param $urlVideo
     * @return array|string|string[]|null
     */
    public static function getUrlEmbeddedYoutubeVideo($urlVideo = null, $hideWatchOnYoutube = false){
        return SiteManagementSliderElem::getUrlEmbedVideoStatic($urlVideo, $hideWatchOnYoutube);
    }

    /**
     * Get Vimeo embed video url
     * @param $url
     * @return array|string|string[]
     */
    public static function getVimeoEmbedVideoUrl($url)
    {
        $videoUrl = null;
        if (!strpos($url, 'player.')) {
            if (strstr($url, 'https://')) {
                $videoUrl = substr_replace($url, 'player.', strlen('https://'), 0);
            } else if (strstr($url, 'http://')) {
                $videoUrl = substr_replace($url, 'player.', strlen('http://'), 0);
            }
        }
        if (!strpos($url, 'video/')) {
            if ($videoUrl) {
                $oldUrl = $videoUrl;
            }
            if (strstr($url, 'https://')) {
                $videoUrl = substr_replace($oldUrl, 'video/', strlen('https://player.vimeo.com/'), 0);
            } else if (strstr($url, 'http://')) {
                $videoUrl = substr_replace($oldUrl, 'video/', strlen('https://player.vimeo.com/'), 0);
            }
        }
        if(is_null($videoUrl)) { // Rimuovo tutti i parametri che ci sono nell'url
            $videoUrl = explode('?', $url)[0];
        }
        return $videoUrl;
    }

    /**
     * Get embedded video (works with YouTube and Vimeo)
     * @param $videoUrl
     * @return string
     */
    public static function getEmbeddedVideo($videoUrl)
    {
        return '<iframe width="100%" height="450" src="'.$videoUrl.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }

    /**
     * @param $videoUrl
     * @param $params
     * - 'iframe' or 'url', default 'url'
     * - hideWatchOnYoutube: boolean, default false. Hides watch on Youtube button
     * @return array|string|string[]|null
     */
    public static function getUrlEmbeddedVideo($videoUrl, $params=[]) {
        // Nasconde il pulsante che permette di vedere il video direttamente su Youtube
        $hideWatchOnYoutube = (isset($params['hideWatchOnYoutube'])) ? $params['hideWatchOnYoutube'] : false;
        // Ritorna o solo l'url embedded o direttamente l'iframe con il link
        $type = (isset($params['type'])) ? $params['type'] : 'url';

        $embeddedUrl = '';
        if(strpos($videoUrl,'youtube')) {
            $embeddedUrl = self::getUrlEmbeddedYoutubeVideo($videoUrl, $hideWatchOnYoutube);
        } elseif(strpos($videoUrl,'vimeo')) {
            $embeddedUrl = self::getVimeoEmbedVideoUrl($videoUrl);
        }
        if($type == 'iframe') {
            return self::getEmbeddedVideo($embeddedUrl);
        } else {
            return $embeddedUrl;
        }
    }
}