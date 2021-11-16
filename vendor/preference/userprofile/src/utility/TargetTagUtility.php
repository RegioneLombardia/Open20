<?php

namespace preference\userprofile\utility;

use open20\amos\admin\models\UserProfile;
use preference\userprofile\models\Tag;
use yii\db\ActiveQuery;
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
 * Description of TargetTagUtility
 *
 */
class TargetTagUtility
{

    static private $prefixCode = 'pctarget';

    /**
     *
     * @return ActiveQuery
     */
    private static function baseQuery()
    {
        return Tag::find()
            ->where(['like', 'codice', 'pctarget%', false])->orderBy('pc_target_order');
    }

    /**
     * example key: 'cittadino'
     * @return ActiveQuery
     */
    public static function getTargetByKey($key)
    {
        return Tag::findOne(['codice' => 'pctarget_' . $key]);
    }

    /**
     * example key: 'cittadino'
     * @return ActiveQuery
     */
    public static function getTargetByCode($code)
    {
        return Tag::findOne(['codice' => $code]);
    }


    /**
     * All root node, the target of preferences
     * 
     * @return Tag[]
     */
    public static function getAllTargetTag()
    {
        /** @var ActiveQuery $query */
        $query = self::baseQuery();
        $listOfTag = $query->all();
        return $listOfTag;
    }

    /**
     * The first target
     * 
     * @return Tag
     */
    public static function getFirstTargetTag($user_id)
    {
        $userProfile = UserProfile::findOne(['user_id' => $user_id]);
        $query = self::baseQuery()->joinWith('pcCwhTagOwnerInterestMm', true, 'INNER JOIN')
            ->andWhere(['record_id' => $userProfile->id])
            ->andWhere(['classname' => UserProfile::className()]);
        //VarDumper::dump( $query->createCommand()->rawSql, $depth = 10, $highlight = true); die;
        return $query->one();
    }

    public static function getAllTargetTagForUSer($user_id)
    {
        $userProfile = UserProfile::findOne(['user_id' => $user_id]);
        $query = self::baseQuery()->joinWith('pcCwhTagOwnerInterestMm', true, 'INNER JOIN')
            ->andWhere(['record_id' => $userProfile->id])
            ->andWhere(['classname' => UserProfile::className()])
            ;
        //VarDumper::dump( $query->createCommand()->rawSql, $depth = 10, $highlight = true); die;
        return $query->all();
    }

    public static function isTargetSelectedForUser($tag, $user_id)
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

    /**
     * Example: ['enteeoperatore', 'impresa', 'cittadino']
     *
     * @return array
     */
    public static function getAllTargetCodeIdString()
    {
        $listToRet = [];
        foreach (self::getAllTargetTag() as $tag) {
            $idString = self::getCodeByName($tag->nome);
            $listToRet[] = $idString;
        }
        return $listToRet;
    }

    /**
     * If $target->nome == 'Ente e Operatore' return 'enteeoperatore'
     *
     * @param Tag $target
     * @return string 
     */
    public static function getCodeIdByTarget(Tag $target)
    {
        $toRet = null;
        if ($target->isRoot()) {
            $toRet = self::getCodeByName($target->nome);
        }
        return $toRet;
    }

    /**
     * code ID 
     *
     * @param string $name
     * @return string
     */
    private static function getCodeByName(string $name)
    {
        return str_replace(' ', '', strtolower($name));
    }

    public static function getPrefixCode()
    {
        self::$prefixCode;
    }


    // public static function getUserContatsDinamicAttributes()
    // {
    //     $modelAttributes = new PreferenceUserTargetAttribute();
    //     VarDumper::dump( $modelAttributes->attributes, $depth = 1, $highlight = true);
    //     $excludeAttr = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    //     $attrs = [];
    //     foreach ($modelAttributes->attributes as $key => $value){
    //         if (!in_array($key ,$excludeAttr) && !StringHelper::startsWith($key, 'validated_',true)) {
    //             $attrs[] = $key;
    //         }
    //     }

    //     $ret = [];
    //     foreach (self::getAllTargetCodeIdString() as $keyTarget) {
    //         $ret[] = $keyTarget;
    //         foreach ($attrs as $attr) {
    //             $ret[] = $keyTarget . '_' . $attr;
    //         }
    //     } 

    //     return $ret;
    // }
}
