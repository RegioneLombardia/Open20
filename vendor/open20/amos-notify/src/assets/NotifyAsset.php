<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\base\builder
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\assets;

use yii\web\AssetBundle;

/**
 * Class NotifyAsset
 * @package open20\amos\notificationmanager\assets
 */
class NotifyAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-notify/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
        'less/notify.less',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/notify.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if (!is_null($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}
