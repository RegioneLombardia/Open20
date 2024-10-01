<?php


namespace amos\podcast\events;


use amos\podcast\models\Podcast;
use amos\podcast\utility\PodcastEmailUtility;
use yii\base\Event;

class PodcastWorkflowEvent
{

    /**
     * @param Event $event
     */
    public function afterEnterStatus(Event $event){
        /** @var  $podcast Podcast */
        $podcast = $event->data;
        if($podcast->status == Podcast::PODCAST_WORKFLOW_STATUS_REQUESTPUBLICATION) {
            PodcastEmailUtility::sendEmailRequestPublication($podcast);
        }elseif ($podcast->status == Podcast::PODCAST_WORKFLOW_STATUS_PUBLISHED && empty($podcast->published_at)){
            $podcast->published_at = date('Y-m-d H:i:s');
            $podcast->save(false);
        }
    }

}