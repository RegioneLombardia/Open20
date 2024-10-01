<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\assets
 * @category   CategoryName
 */

namespace open20\amos\documenti\assets;

use yii\web\AssetBundle;
/**
 * Class ModuleDocumentiAsset
 * @package open20\amos\documenti\assets
 */
class ModuleSpinnerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-documenti/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
        'less/spinner.less',
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

        if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}
