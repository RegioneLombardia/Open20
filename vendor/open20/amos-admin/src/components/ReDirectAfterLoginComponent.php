<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\components
 * @category   CategoryName
 */

namespace open20\amos\admin\components;

use yii\base\Component;

/**
 * Class ReDirectAfterLoginComponent
 * @package open20\amos\admin\components
 */
class ReDirectAfterLoginComponent extends Component
{
    /**
     * @param $url
     * @return \yii\web\Response
     */
    public function redirectToUrl($url)
    {
        if (!is_null(\Yii::$app->controller)) {
            return \Yii::$app->controller->redirect([$url]);
        } else {
            return \Yii::$app->response->redirect([$url]);
        }
    }
}
