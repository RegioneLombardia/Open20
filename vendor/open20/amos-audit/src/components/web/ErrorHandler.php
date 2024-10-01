<?php
/**
 * Error handler version for web based modules
 */

namespace open20\amos\audit\components\web;

use open20\amos\audit\components\base\ErrorHandlerTrait;

/**
 * ErrorHandler
 * @package open20\amos\audit\components\web
 */
class ErrorHandler extends \yii\web\ErrorHandler
{
    use ErrorHandlerTrait;
}