<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin
 * @category   CategoryName
 */

namespace open20\amos\admin;

use mdm\admin\Module;
use Yii;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;

/**
 * Class AmosAdmin
 * @package open20\amos\admin
 */
class RoleManager extends Module
{
    public $controllerNamespace = "mdm\admin\controllers";

    public function init()
    {
        //Views on the original plugin
        $this->viewPath = '@vendor/mdmsoft/yii2-admin/views';

        if (!(\Yii::$app instanceof \yii\console\Application) && strpos(\Yii::$app->request->url, 'amministra-utenti') !== false
            && !Yii::$app->user->can('ADMIN')) {
            Yii::$app->response->redirect('/403')->send();
        }

        return parent::init();
    }
}