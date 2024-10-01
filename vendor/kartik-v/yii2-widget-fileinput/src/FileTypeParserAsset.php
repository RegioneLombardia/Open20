<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-fileinput
 * @version 1.1.1
 */

namespace kartik\file;

/**
 * File type parser asset bundle for FileInput Widget
 *
 * @since 1.0
 */
class FileTypeParserAsset extends BaseAsset
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setupAssets('js', ['js/plugins/buffer', 'js/plugins/filetype']);
        parent::init();
    }
}