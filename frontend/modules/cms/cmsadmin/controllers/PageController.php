<?php

namespace app\modules\cms\cmsadmin\controllers;

use Yii;
use luya\admin\base\Controller;
use luya\admin\components\Auth;

/**
 * Page Controller.
 *
 * Provie Page Templates for create update and drafts.
 *
 * @since 1.0.0
 */
class PageController extends \luya\cms\admin\controllers\PageController
{
    public function init() {
        $this->viewPath = "@frontend/modules/cms/cmsadmin/views/page";
        parent::init();
    }
    
    public function actionCreate()
    {
        return $this->render('create');
    }

    public function actionUpdate()
    {
        return $this->render('update', [
            'canBlockUpdate' => Yii::$app->auth->matchApi(Yii::$app->adminuser->id, 'api-cms-navitempageblockitem', Auth::CAN_UPDATE),
            'canBlockDelete' => Yii::$app->auth->matchApi(Yii::$app->adminuser->id, 'api-cms-navitempageblockitem', Auth::CAN_DELETE),
            'canBlockCreate' => Yii::$app->auth->matchApi(Yii::$app->adminuser->id, 'api-cms-navitempageblockitem', Auth::CAN_CREATE),
        ]);
    }
    
    public function actionDrafts()
    {
        return $this->render('drafts');
    }
}
