<?php
namespace backend\modules\campains\utility;

use backend\modules\campains\models\PreferenceCampainChannelMm;
use open20\amos\admin\models\UserProfile;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\models\Topic;
use preference\userprofile\models\PreferenceCrossTopicView;
use preference\userprofile\models\PreferenceTopicChannelMm;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\models\Tag;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
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
class HardBouncedEmailUtlity
{

    public static function allHBEmailForUser($userId)
    {
        $toret = [];

        $channels = PreferenceCampainChannelMm::findAll([
            'status' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT,
            'preference_channel_id' => PreferenceChannel::NEWSLETTER_ID,
        ]);

        $q = null;
        /** @var PreferenceCampainChannelMm $channel */
        foreach ($channels as $channel) {
            if (!is_null(Yii::$app->dbstats->schema->getTableSchema(TableMessageBouncesUtility::getTableName($channel->id), true))) {
                if (is_null($q)) { 
                    $q = new Query();                  
                    $q->from(TableMessageBouncesUtility::getTableName($channel->id));
                    $q->select(new Expression("*"));
                } else {
                    $q->union('SELECT * FROM ' . TableMessageBouncesUtility::getTableName($channel->id));
                }
            }
        }
        
        if (is_null($q)) {
            return $toret;
        }
        // $q->andwhere(['type' => 'HB']);
        $queryResult = $q->all();

        if (empty($queryResult)) {
            return $toret;
        }

        foreach ($queryResult as $value) {
            $hbMails[] = $value['email'];
        }

        $attributes = PreferenceUserTargetAttribute::find()
            ->andWhere(['user_id' => $userId])
            ->andWhere(['user_id' => $userId])
            ->all();
        if (empty($attributes)) {
            return $toret;
        }

        foreach ($attributes as $value) {
            $userMails[] = $value['email'];
        }

        $toret = array_intersect($hbMails,$userMails);
        return array_unique($toret);
    }

}
