<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\utility
 * @category   CategoryName
 */

namespace open20\amos\discussioni\utility;

use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use yii\base\BaseObject;

/**
 * Class DiscussionsUtility
 * @package open20\amos\discussioni\utility
 */
class DiscussionsUtility extends BaseObject
{
    /**
     * @param DiscussioniTopic $topic
     * @return string
     */
    public static function getViewContributeString($topic)
    {
        $numeroContributi = $topic->getDiscussionComments()->count();
        if ($numeroContributi > 0) {
            $contributesString = $numeroContributi . ' ';
            if ($numeroContributi > 1) {
                $contributesString .= AmosDiscussioni::tHtml('amosdiscussioni', 'contributi');
            } else {
                $contributesString .= AmosDiscussioni::tHtml('amosdiscussioni', 'contributo');
            }
        } else {
            $contributesString = AmosDiscussioni::tHtml('amosdiscussioni', 'Nessun contributo');;
        }
        return $contributesString;
    }
}
