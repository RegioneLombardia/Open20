<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\assets;

use yii\web\AssetBundle;

class ModuleDiscussioniAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-discussioni/src/assets/web';

    public $css = [
    ];
    public $js = [
        'js/discussioni-js.js'
    ];
    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if(!empty($moduleL))
        { $this->depends [] = 'open20\amos\layout\assets\BaseAsset'; }
        else
        { $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset'; }
        parent::init(); // TODO: Change the autogenerated stub
    }
}