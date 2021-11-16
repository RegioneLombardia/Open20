<?php
namespace preference\userprofile\utility;

use open20\amos\admin\models\UserProfile;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\models\Topic;
use preference\userprofile\models\PreferenceCrossTopicView;
use preference\userprofile\models\PreferenceTopicChannelMm;
use preference\userprofile\models\Tag;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    preference\userprofile\utilty
 * @category   CategoryName
 */

/**
 * Description of TopicTagUtility
 *
 */
class UserInterestTagUtility 
{

    /**
     * tutti i tag in interesse dell'utente per albero... tipo tutti gli interessi selzionati sotto cittadino
     *
     * @param [type] $userId
     * @param [type] $targetCode
     * @return Tag[]
     */
    public static function getUserInterestsTagByTarget($userId, $targetCode)
    {
        $rootTag = TargetTagUtility::getTargetByCode($targetCode);
        $className = UserProfile::className();
        $recordId = UserProfile::findOne(['user_id' => $userId])->id;
        $query = Tag::find()->joinWith('pcCwhTagOwnerInterestMm',true,'INNER JOIN')
            ->where(['cwh_tag_owner_interest_mm.root_id' => $rootTag->id])
            ->andWhere(['cwh_tag_owner_interest_mm.classname' => $className])
            ->andWhere(['cwh_tag_owner_interest_mm.record_id' => $recordId])
            ->andWhere(['like', 'tag.codice',  TopicTagUtility::getPrefixCode() . '%', false])
            ->orderBy('cwh_tag_owner_interest_mm.created_at');
        return $query->all();
    }

    /**
     * tutti i tag in interesse dell'utente per albero... tipo tutti gli interessi selzionati sotto cittadino
     *
     * @param [type] $userId
     * @param [type] $targetCode
     * @return Tag[]
     */
    public static function getUserInterests($userId, $channelId = null)
    {
        $className = UserProfile::className();
        $recordId = UserProfile::findOne(['user_id' => $userId])->id;
        $query = Tag::find()->joinWith('pcCwhTagOwnerInterestMm',true,'INNER JOIN')
            ->andWhere(['cwh_tag_owner_interest_mm.classname' => $className])
            ->andWhere(['cwh_tag_owner_interest_mm.record_id' => $recordId])
            ->andWhere(['cwh_tag_owner_interest_mm.deleted_at' => null])
            ->orderBy('cwh_tag_owner_interest_mm.created_at');

        if (!is_null($channelId)) {
            $query->leftJoin('preference_topic_channel_mm', 'tag.codice = (preference_topic_channel_mm.topic_code COLLATE utf8_general_ci)');
            $query->andWhere(['OR',
                ['preference_topic_channel_mm.id' => null], // per includere i Target
                ['preference_topic_channel_mm.preference_channel_id' => $channelId],
            ]);
            $query->andWhere(['OR',
                ['preference_topic_channel_mm.id' => null], // per includere i Target
                ['preference_topic_channel_mm.user_id' => $userId]
            ]);
            $query->andWhere(['preference_topic_channel_mm.deleted_at' => null]);
        }

        //echo $query->createCommand()->rawSql; die;

        return $query->all();
    }

    /**
     * Undocumented function
     *
     * @param UserProfile $model
     * @param string $interest_classname
     * @param Tag $tag
     * @return bool
     */
    public static function saveRegisteredUserInterestTag($model, $interest_classname, $tag) 
    {
        $topic = self::getRegisteredUserInterestTag($model, $tag);
        if (empty($topic)){
            $modelTagModel = new CwhTagOwnerInterestMm();
            $modelTagModel->classname = $model::className();
            $modelTagModel->record_id = $model->id;
            $modelTagModel->interest_classname = $interest_classname;
            $modelTagModel->tag_id = $tag->id;
            $modelTagModel->root_id = $tag->root;
            $modelTagModel->save(false);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Undocumented function
     *
     * @param string $user_id
     * @param Tag $topic
     * @return void
     */
    public static function deletePreferenceTopicChannelByUserAndCode($user_id, $topic)
    {
        $listOfitems = PreferenceTopicChannelMm::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['topic_code' => $topic->codice])
            ->andWhere(['deleted_at' => null])
            ->all();

        foreach ($listOfitems as $item) {
            $item->delete();
        }
    }

    /**
     * Undocumented function
     *
     * @param string $user_id
     * @param Tag $topic
     * @return void
     */
    public static function deletePreferenceTopicChannel($user_id, $topic, $channel_id)
    {
        $item = PreferenceTopicChannelMm::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['topic_code' => $topic->codice])
            ->andWhere(['preference_channel_id' => $channel_id])
            ->andWhere(['deleted_at' => null])
            ->one();

        if (!empty($item)) {
            $item->delete();
        }
    }

    /**
     * Undocumented function
     *  TODO la frequenza impostata è a 1, da definire come fa scelta la frequenza sul TOPIC! anche perchè sembra essere solo una.
     *  TODO Da capire anche come deve essere definito il default... se in backend su ogni topic? in questo caso va aggiunta una tabella per definire i default
     * @param string $user_id
     * @param Tag $topic
     * @param integer $frequencyId
     * @return void
     */
    public static function addAllPreferenceTopicChannelByUserAndCode($user_id, $topic, $frequencyId = 1)
    {
        $listOfChannels = PreferenceChannel::find()->andWhere(['active' => true])->all();
        foreach ($listOfChannels as $channel) {
            $tcmm = new PreferenceTopicChannelMm();
            $tcmm->preference_channel_id = $channel->id;
            $tcmm->preference_sending_frequency_id = $frequencyId;
            $tcmm->user_id = $user_id;
            $tcmm->topic_code = $topic->codice;
            $tcmm->save(false);
        }
    }

    /**
     * Undocumented function
     *  TODO la frequenza impostata è a 1, da definire come fa scelta la frequenza sul TOPIC! anche perchè sembra essere solo una.
     *  TODO Da capire anche come deve essere definito il default... se in backend su ogni topic? in questo caso va aggiunta una tabella per definire i default
     * @param string $user_id
     * @param Tag $topic
     * @param integer $frequencyId
     * @return void
     */
    public static function addSinglePreferenceTopicChannel($user_id, $topic, $channel_id, $frequencyId = 1)
    {
        $tcmm = new PreferenceTopicChannelMm();
        $tcmm->preference_channel_id = $channel_id;
        $tcmm->preference_sending_frequency_id = $frequencyId;
        $tcmm->user_id = $user_id;
        $tcmm->topic_code = $topic->codice;
        $tcmm->save(false);
    }

    /**
     * Undocumented function
     *
     * @param UserProfile $model
     * @param Tag $tag
     * @return void
     */
    public static function removeRegisteredUserInterestTag($model, $tag) 
    {
        /** @var CwhTagOwnerInterestMm $interestToRemove */
        $interestToRemove = CwhTagOwnerInterestMm::find()
            ->where(['classname' => $model::className()])
            ->andWhere(['record_id' => $model->id])
            ->andWhere(['tag_id' =>$tag->id])
            ->andWhere(['deleted_at' => null])
            ->one();
        // se trova elimina
        if (!empty($interestToRemove)) {
            $interestToRemove->delete();
        }
    }    
    
    /**
    * Undocumented function
    *
    * @param UserProfile $model
    * @param Tag $tag
    * @return void
    */
    public static function getRegisteredUserInterestTag($model, $tag) 
    {
        /** @var CwhTagOwnerInterestMm $interestToRemove */
        $interestToRemove = CwhTagOwnerInterestMm::find()
            ->where(['classname' => $model::className()])
            ->andWhere(['record_id' => $model->id])
            ->andWhere(['tag_id' =>$tag->id])
            ->andWhere(['deleted_at' => null])
            ->one();
        return $interestToRemove;
    }

    public static function getUserChannelsIds($tag, $userId)
    {
        return ArrayHelper::map(PreferenceTopicChannelMm::find()
            ->where(['topic_code' => $tag->codice])
            ->andWhere(['user_id' => $userId])
            ->andWhere(['deleted_at' => null])
            ->all(),'id','preference_channel_id');
    }

    public static function isSetUserChannel($tag, $userId, $channelId)
    {
        $item = PreferenceTopicChannelMm::find()
            ->where(['topic_code' => $tag->codice])
            ->andWhere(['user_id' => $userId])
            ->andWhere(['preference_channel_id' => $channelId])            
            ->andWhere(['deleted_at' => null])
            ->one();

        if(!empty($item)) {
            return true;
        }

        return false;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public static function getAllChannels(int $all = null)
    {
        $channels=PreferenceChannel::find();
        if(empty ($all)){
            $channels->andWhere(['active' => true]);
        }
        return $channels->all();
    }

}
