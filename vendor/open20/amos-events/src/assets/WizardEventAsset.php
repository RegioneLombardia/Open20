<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

namespace open20\amos\events\assets;

use yii\web\AssetBundle;

/**
 * Class AmosEventsAsset
 * @package open20\amos\events\assets
 */
class WizardEventAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-events/src/assets/web/';
    public $baseUrl = '@web';

    /**
     * @inheritdoc
     */
    public $js = [
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        //'scss/main-amos-events.scss',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
         //'open20\amos\layout\assets\BootstrapItaliaCustomAsset',
    ];

}
