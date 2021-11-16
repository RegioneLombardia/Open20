<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-touchspin
 * @version 1.2.3
 */

namespace kartik\touchspin;

use kartik\base\InputWidget;

/**
 * TouchSpin widget is a Yii2 wrapper for the bootstrap-touchspin plugin by István Ujj-Mészáros. This input widget is a
 * mobile and touch friendly input spinner component for Bootstrap 3.
 *
 * @since 1.0
 */
class TouchSpin extends InputWidget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'TouchSpin';

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        parent::run();
        $this->setPluginOptions();
        $this->registerAssets();
        echo $this->getInput('textInput');
    }

    /**
     * Set the plugin options
     * @throws \yii\base\InvalidConfigException
     */
    protected function setPluginOptions()
    {
        $css = 'btn ' . $this->getDefaultBtnCss();
        $iconPrefix = $this->getDefaultIconPrefix();
        if ($this->disabled) {
            $css .= ' disabled';
        }
        $defaultPluginOptions = [
            'buttonup_class' => $css,
            'buttondown_class' => $css,
            'buttonup_txt' => "<i class='{$iconPrefix}forward'></i>",
            'buttondown_txt' => "<i class='{$iconPrefix}backward'></i>",
        ];
        $this->pluginOptions = array_replace_recursive($defaultPluginOptions, $this->pluginOptions);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        TouchSpinAsset::registerBundle($view, $this->bsVersion);
        $this->registerPlugin($this->pluginName);
    }
}
