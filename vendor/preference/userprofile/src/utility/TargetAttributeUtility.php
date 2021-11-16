<?php
namespace preference\userprofile\utility;

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
class TargetAttributeUtility
{

    public static function generateNewEmailToken()
    {
        return Yii::$app->security->generateRandomString();
    }

    public static function generateNewPhoneToken()
    {
        return sprintf("%06d", mt_rand(1, 999999));
    }

    public static function getAttributesByUserCode($userId, $targetCode)
    {
        $targetAttributes = PreferenceUserTargetAttribute::find()->where(['user_id' => $userId, 'target_code' => $targetCode])->one();
        return $targetAttributes;
    }

    public static function getTargetIcon($targetName)
    {
        if (strpos($targetName, 'Cittadino') !== false) {
            return 'ic_person';
        } elseif (strpos($targetName, 'Impresa') !== false) {
            return 'ic_business';
        } elseif (strpos($targetName, 'Ente') !== false) {
            return 'ic_assignment_ind';
        }
        return null;
    }

}
