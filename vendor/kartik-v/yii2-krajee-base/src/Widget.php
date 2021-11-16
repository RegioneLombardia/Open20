<?php

/**
 * @package   yii2-krajee-base
 * @version   1.9.9
 */

namespace kartik\base;

use yii\base\InvalidConfigException;
use yii\base\Widget as YiiWidget;

/**
 * Base class for widgets extending [[YiiWidget]] used in Krajee extensions.
 *
 * @since  1.0
 */
class Widget extends YiiWidget implements BootstrapInterface
{
    use TranslationTrait;
    use WidgetTrait;

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
