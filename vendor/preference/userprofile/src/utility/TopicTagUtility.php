<?php
namespace preference\userprofile\utility;

use preference\userprofile\models\base\PreferenceTopicChannelFrequency;
use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\models\PreferenceTopicChannelMm;
use preference\userprofile\models\Topic;
use preference\userprofile\models\PreferenceCrossTopicView;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use preference\userprofile\models\Tag;
use open20\amos\admin\models\UserProfile;
use yii\db\ActiveQuery;
use yii\helpers\StringHelper;
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
class TopicTagUtility 
{
    private static $prefixCode = 'pctopic';

    /**
     *
     * @return ActiveQuery
     */
    private static function baseQuery(int $all = null)
    {
        $tag=Tag::find()
            ->where(['like', 'codice',  self::$prefixCode.'%', false]);
        if(empty($all) ){
            $tag->andWhere(['pc_active' => true]);
        }
        $tag->orderBy('pc_topic_order');
        return $tag;
    }

    /**
     *
     * @return Tag
     */
    public static function getTopicByTargetCode(string $targetStringIdentifier, int $numericTopicCode) 
    { 
        $code = self::getPrefixCode() . '_' . $targetStringIdentifier . '_' . $numericTopicCode;
        /** @var ActiveQuery $query */
        $query = Tag::find()->where(['codice' => $code]);
        // VarDumper::dump( $query->createCommand()->rawSql,1,true);
        return $query->one();
    }

    /**
     * Example of return:<br />
     * [<br />
     *     code-topic => label,<br />
     *     0 => Topic { id -> 1, label -> 'Innovazione' ...},<br />
     *     1 => Topic { id -> 2 , label -> 'Ambiente e Sviluppo sostenibile' ...},<br />
     *     ...<br />
     * ]<br />
     * <br />
     * @return array
     */
    public static function getCrossTopicArray()
    {
        $toret = [];
        $crossView = PreferenceCrossTopicView::find()->all();
        foreach ($crossView as $value) {
            $firstTag = Tag::findOne($value->first_id);
            if (!empty($firstTag)) {
                $topic = new Topic();
                $topic->id = $value->cod_topic;
                $topic->label = $firstTag->nome;
                $topic->description = $firstTag->descrizione;
                $topic->icon = $firstTag->icon;
                $toret[] = $topic;
            }
        }
        return $toret;
    }

    /**
     * Work with cross topic!
     *
     * @param [type] $name
     * @return void
     */
    public static function getCrossTopicCodeByName($name)
    {
        foreach (self::getCrossTopicArray() as $value) {
            if (strtoupper($value->label) == strtoupper($name)){
                return $value->id;
            }
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public static function getPrefixCode()
    {
        return self::$prefixCode;
    }

    /**
     *
     * @return Tag[]
     */
    public static function getAllTopicByTargetCode(string $targetCode,int $all = null)
    {
        $rootTag = Tag::findOne(['codice' => $targetCode]);
        /** @var ActiveQuery $query */
        $query = self::baseQuery($all)->andWhere(['root' => $rootTag->id]);
        // VarDumper::dump( $query->createCommand()->rawSql,1,true);
        return $query->all();
    }

    /**
     *
     * @return Tag[]
     */
    public static function getAllTopicByTargetId(int $id, int $all = null)
    {
        /** @var ActiveQuery $query */
        $query = self::baseQuery($all)->andWhere(['root' => $id]);
        return $query->all();
    }

    public static function getFrequencyByChannelAndTopicCode($channel_id, $topic_code)
    {
        $tcf = PreferenceTopicChannelFrequency::find()
            ->where(['preference_channel_id' => $channel_id])
            ->andWhere(['topic_code' => $topic_code])
            ->one();
        return $tcf->preferenceSendingFrequency;
    }
    public static function destroyAllInterestTag($user_id)
    {
        $userProfile = UserProfile::findOne(['user_id' => $user_id]);

        $interestsToRemove = CwhTagOwnerInterestMm::find()
            ->where(['classname' => $userProfile::className()])
            ->andWhere(['record_id' => $userProfile->id])
            ->andWhere(['deleted_at' => null])
            ->all();
        // se trova elimina
        foreach ($interestsToRemove as $interestToRemove){
            $interestToRemove->delete();
        }

        return true;
    }

    public static function destroyAllChannelsByUser($user_id)
    {
        $userProfile = UserProfile::findOne(['user_id' => $user_id]);

        $topicChannels = PreferenceTopicChannelMm::find()->andWhere(['user_id' => $user_id])->all();
        foreach ($topicChannels as $topicChannel){
            $topicChannel->delete();
        }
      return true;
    }

    public static function isTopicSelectedForUser($tag, $user_id)
    {
        $userProfile = UserProfile::findOne(['user_id' => $user_id]);
        $query = self::baseQuery()->joinWith('pcCwhTagOwnerInterestMm', true, 'INNER JOIN')
            ->andWhere(['record_id' => $userProfile->id])
            ->andWhere(['classname' => UserProfile::className()])
            ->andWhere(['tag_id' => $tag->id])
        ;
        $tagFound = $query->one();
        return empty($tagFound)? false: true;
    }
    public static function disableAllChannels()
    {
        $channels =UserInterestTagUtility::getAllChannels();
        foreach ($channels as $channel){
            $channel->active = 0;
            $channel->save();
        }
        return true;
    }
    public static function disableAllTopicTag()
    {
        $targets = self::baseQuery()->all();
        foreach ($targets as $target){
            $target->pc_active = 0;
            $target->save();
        }
        return true;
    }

    public static function tagIsTopic($tag)
    {
        if (StringHelper::startsWith($tag->codice, self::$prefixCode, false)) {
            return true;
        }

        return false;
    }

}
