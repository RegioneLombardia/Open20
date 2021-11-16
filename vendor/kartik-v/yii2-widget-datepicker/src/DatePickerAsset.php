<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-datepicker
 * @version 1.4.6
 */

namespace kartik\date;

use kartik\base\AssetBundle;

/**
 * Asset bundle for DatePicker Widget
 *
 * @since 1.0
 */
class DatePickerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $bsCss = 'bootstrap-datepicker' . ($this->isBs4() ? '4' : '3');
        $this->setupAssets('css', ['css/' . $bsCss, 'css/datepicker-kv']);
        $this->setupAssets('js', ['js/bootstrap-datepicker', 'js/datepicker-kv']);
        parent::init();
    }
}
