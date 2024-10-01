<?php

/**
 * @package   yii2-krajee-base
 * @version   3.0.5
 */

namespace kartik\base;

use yii\base\InvalidConfigException;
use yii\base\Widget as YiiWidget;

/**
 * Widget is the base class for widgets extending [[YiiWidget]] used in Krajee extensions.
 *
 */
class Widget extends YiiWidget implements BootstrapInterface
{
    use TranslationTrait;
    use WidgetTrait;
    use BootstrapTrait;

    /**
     * @var array HTML attributes or other settings for widgets.
     */
    public $options = [];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->initBsVersion();
        parent::init();
        $this->mergeDefaultOptions();
        if (empty($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $this->initDestroyJs();
    }
}
