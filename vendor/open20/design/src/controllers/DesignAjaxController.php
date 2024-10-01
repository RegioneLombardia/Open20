<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    DesignPackage
 */

namespace open20\design\controllers;

use open20\amos\admin\models\UserProfile;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Class DesignAjaxController
 * @package open20\design\controllers
 */
class DesignAjaxController extends DesignBaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $result = ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className(),
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'my-profile-widget-ajax'
                            ],
                            'roles' => ['@']
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
            ]);

        return $result;
    }

    /**
     * @param int $id This is an user profile id, NOT user id!!!
     * @return string
     */
    public function actionMyProfileWidgetAjax($id)
    {
        /** @var UserProfile $userProfileModel */
        $userProfileModel = $this->adminModule->createModel('UserProfile');
        $model = $userProfileModel::findOne($id);
        return $this->renderPartial('@vendor/open20/design/src/widgets/views/widget_my_profile', [
            'model' => $model
        ]);
    }
}
