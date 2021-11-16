<?php

/**
 * @package   yii2-datecontrol
 * @version   1.9.5
 */

namespace kartik\datecontrol;

use Yii;
use kartik\base\AssetBundle;

/**
 * Asset bundle for the [[DateControl]] widget.
 *
 * @since 1.0
 */
class DateControlAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge($this->depends, ['kartik\datecontrol\DateFormatterAsset']);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/datecontrol']);
        parent::init();
    }
}