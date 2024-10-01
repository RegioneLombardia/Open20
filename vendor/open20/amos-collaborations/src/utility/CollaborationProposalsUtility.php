<?php

namespace open20\amos\collaborations\utility;

use open20\amos\collaborations\models\CollaborationProposals;
use yii\base\BaseObject;

/**
 *
 */
class CollaborationProposalsUtility extends BaseObject
{
    /**
     * Get Vimeo embed video url
     * @param $url
     * @return array|string|string[]
     */
    public function getVimeoEmbedVideoUrl($url)
    {
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

        return $videoUrl;
    }

    /**
     * Get embedded video (works with YouTube and Vimeo)
     * @param $videoUrl
     * @param $videoTitle
     * @return string
     */
    public function getEmbeddedVideo($videoUrl, $videoTitle)
    {
        return '<iframe width="100%" height="450" src="'.$videoUrl.'" title="'.$videoTitle.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }

    /**
     * Check if proposal is archived
     * @param $id
     * @return bool
     */
    public static function isArchived($id)
    {
        $proposal = CollaborationProposals::findOne($id);
        if (!empty($proposal)) {
            if ($proposal->end_date < date('Y-m-d')) {
                return true;
            }
        }

        return false;
    }
}