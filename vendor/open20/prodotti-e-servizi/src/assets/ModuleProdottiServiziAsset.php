<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi\assets
 * @category   CategoryName
 */

namespace open20\prodottiservizi\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class ModuleProdottiServiziAsset
 * @package open20\prodottiservizi\assets
 */
class ModuleProdottiServiziAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/prodotti-e-servizi/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
        'less/prodotti-servizi-design-bi.less'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/prodotti-servizi.js'
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
