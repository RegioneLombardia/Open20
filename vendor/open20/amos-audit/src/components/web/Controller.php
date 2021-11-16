<?php

namespace open20\amos\audit\components\web;

use open20\amos\audit\components\Access;
use open20\amos\audit\web\AuditAsset;
use open20\amos\audit\Audit;
use Yii;
use yii\web\View;

/**
 * Base Controller
 * @package open20\amos\audit\components\web
 *
 * @property Audit $module
 * @property View  $view
 */
class Controller extends \yii\web\Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => Access::getAccessControlFilter()
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        AuditAsset::register($this->view);
        return parent::beforeAction($action);
    }

}
