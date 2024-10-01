<?php
/**
 * Console compatible error handler
 */

namespace open20\amos\audit\components\console;

use open20\amos\audit\components\base\ErrorHandlerTrait;

/**
 * ErrorHandler
 * @package open20\amos\audit\components\console
 */
class ErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
}