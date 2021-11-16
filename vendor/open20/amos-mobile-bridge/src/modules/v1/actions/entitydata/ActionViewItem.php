<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata;

use open20\amos\admin\models\UserProfile;
use open20\amos\community\models\Community;
use open20\amos\core\record\Record;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\DiscussioniParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\ItemsParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\NewsParser;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\User;
use open20\amos\news\models\News;
use yii\base\Exception;
use yii\helpers\Json;
use yii\rest\Action;

class ActionViewItem extends Action
{
    public function run()
    {
        //Request params
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();

        //Refference namespace
        $namespace = $bodyParams['namespace'];

        //Default definition items array
        $itemsArray = [];

        switch ($namespace) {
            case News::className():
                {
                    $itemsArray = NewsParser::getItem($bodyParams);
                }
                break;
            case DiscussioniTopic::className():
                {
                    $itemsArray = DiscussioniParser::getItem($bodyParams);
                }
                break;
            default:
                {
                    $itemsArray = ItemsParser::getItem($bodyParams);
                }
        }

        return $itemsArray;
    }
}