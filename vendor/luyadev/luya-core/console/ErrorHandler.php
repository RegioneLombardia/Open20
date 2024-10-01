<?php

namespace luya\console;

use luya\traits\ErrorHandlerTrait;

/**
 * Console/CLI ErrorHandler.
 *
 * @since 1.0.0
 */
class ErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
    
    /**
     * @var string This propertie has been added in order to make sure console commands config
     * does also work in console env.
     */
    public $errorAction;
}
