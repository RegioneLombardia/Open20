<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\core\record\Record;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\ActionEditItem;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\ActionListItems;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\ActionViewItem;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\User;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class EntityDataController extends DefaultController
{
    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [];
    }

    public function actions()
    {
        return [
            'list-items' => [
                'class' => ActionListItems::className(),
                'modelClass' => Record::className(),
            ],
            'edit-item' => [
                'class' => ActionEditItem::className(),
                'modelClass' => Record::className(),
            ],
            'view-item' => [
                'class' => ActionViewItem::className(),
                'modelClass' => Record::className(),
            ],
        ];
    }
}