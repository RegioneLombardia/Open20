<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\models\PreferenceTopicChannelFrequency;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use yii\db\Migration;
use yii\rbac\Permission;

/**
 * Class m200719_141500_add_topic_frequency */
class m200719_141500_add_topic_frequency extends Migration
{

    public function safeUp()
    {

        $listOfTag = TopicTagUtility::getAllTopicByTargetCode('pctarget_cittadino');   
        foreach ($listOfTag as $tag) {
            
            $listChannels = PreferenceChannel::find()->all();

            foreach ($listChannels as $channel) {
                $tcf = new PreferenceTopicChannelFrequency();
                $tcf->topic_code = $tag->codice;
                $tcf->preference_channel_id = $channel->id;
                if ($channel->id == 1) {
                    $tcf->preference_sending_frequency_id = 1;
                } else if ($channel->id == 2) {
                    $tcf->preference_sending_frequency_id = 2;
                } else {
                    $tcf->preference_sending_frequency_id = 3;
                }

                $tcf->save();
            }

        }   

        $listOfTag = TopicTagUtility::getAllTopicByTargetCode('pctarget_impresa');   
        foreach ($listOfTag as $tag) {
            
            $listChannels = PreferenceChannel::find()->all();

            foreach ($listChannels as $channel) {
                $tcf = new PreferenceTopicChannelFrequency();
                $tcf->topic_code = $tag->codice;
                $tcf->preference_channel_id = $channel->id;
                if ($channel->id == 1) {
                    $tcf->preference_sending_frequency_id = 1;
                } else if ($channel->id == 2) {
                    $tcf->preference_sending_frequency_id = 2;
                } else {
                    $tcf->preference_sending_frequency_id = 3;
                }

                $tcf->save();
            }

        }  


        $listOfTag = TopicTagUtility::getAllTopicByTargetCode('pctarget_enteeoperatore');   
        foreach ($listOfTag as $tag) {
            
            $listChannels = PreferenceChannel::find()->all();

            foreach ($listChannels as $channel) {
                $tcf = new PreferenceTopicChannelFrequency();
                $tcf->topic_code = $tag->codice;
                $tcf->preference_channel_id = $channel->id;
                if ($channel->id == 1) {
                    $tcf->preference_sending_frequency_id = 1;
                } else if ($channel->id == 2) {
                    $tcf->preference_sending_frequency_id = 2;
                } else {
                    $tcf->preference_sending_frequency_id = 3;
                }

                $tcf->save();
            }

        }  
    }
    
    public function safeDown()
    {

        $this->truncateTable('preference_topic_channel_frequency');
       
    }

}
